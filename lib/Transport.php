<?php
// Türkçe Açıklama: Türkiye içinde rota ve tekil teslimat üretimi
// Bu sınıf, tedarik tahsisi (allocate) sonucuna göre teslimat kaydı ve rota oluşturur.
// Stok düşümü, opsiyonel olarak "Başlatıldı" veya "Teslim Edildi" durumuna geçişte yapılabilir.

require_once __DIR__.'/Geo.php';

class Transport {

  // Tahsise göre teslimat ve rota planla
  public static function plan(PDO $pdo, int $talep_id, array $alloc): array {
    // Talep detayını al
    $t = $pdo->prepare("SELECT id, sehir, mahalle, sokak, latitude, longitude FROM talepler WHERE id = ?");
    $t->execute([$talep_id]);
    $talep = $t->fetch();

    $tLat = $talep['latitude'] ?? null;
    $tLng = $talep['longitude'] ?? null;
    if ($tLat !== null && $tLng !== null && Geo::isValid($tLat, $tLng)) {
      list($tLat, $tLng) = Geo::clampTurkey((float)$tLat, (float)$tLng);
    }

    $planned = [];

    foreach ($alloc as $al) {
      $mid = $al['malzeme_id'] ?? null;
      $tedarikci_id = $al['tedarikci_id'] ?? null;

      // Taşıma modu seçimi (kritik ise hava, aksi halde kara)
      $mode = self::chooseMode($pdo, $mid);

      // Aynı talep + malzeme için tek teslimat kuralı (NULL-safe karşılaştırma)
      $did = self::createDelivery($pdo, $talep_id, $tedarikci_id, $mode, $mid);
      if ($did > 0 && $tedarikci_id) {
        // Rota oluştur (depo → talep)
        $rid = self::createRoute($pdo, $did, $tedarikci_id, $tLat, $tLng, $mode);
        $planned[] = ['delivery_id' => $did, 'route_id' => $rid, 'mode' => $mode];
      } else {
        $planned[] = ['delivery_id' => $did, 'route_id' => 0, 'mode' => $mode];
      }
    }

    return $planned;
  }

  // Basit taşıma modu seçimi
  private static function chooseMode(PDO $pdo, ?int $mid): string {
    if ($mid) {
      $mq = $pdo->prepare("SELECT kategori, ad FROM malzemeler WHERE id = ?");
      $mq->execute([$mid]);
      $m = $mq->fetch();
      $kategori = strtolower($m['kategori'] ?? '');
      $ad       = strtolower($m['ad'] ?? '');

      $critical = ($kategori === 'kritik') || in_array($ad, ['tıbbi', 'ilac', 'su']);
      return $critical ? 'Hava' : 'Kara';
    }
    return 'Kara';
  }

  // Teslimat kaydı oluştur (tekil: talep_id + malzeme_id)
  private static function createDelivery(PDO $pdo, int $talep_id, ?int $tedarikci_id, string $mode, ?int $mid): int {
    // Mevcut var mı? (NULL-safe: <=> MySQL özelliği)
    $chk = $pdo->prepare("SELECT id FROM teslimatlar WHERE talep_id = ? AND malzeme_id <=> ? LIMIT 1");
    $chk->execute([$talep_id, $mid]);
    $found = $chk->fetchColumn();
    if ($found) {
      return (int)$found;
    }

    // Yeni teslimat (durum: Planlandı)
    $s = $pdo->prepare("INSERT INTO teslimatlar (talep_id, tedarikci_id, malzeme_id, tasima_turu, durum, created_at)
                        VALUES (?,?,?,?,? , NOW())");
    $s->execute([$talep_id, $tedarikci_id, $mid, $mode, 'Planlandı']);
    return (int)$pdo->lastInsertId();
  }

  // Rota oluştur (depo → talep) ve süre / mesafe hesapla
  private static function createRoute(PDO $pdo, int $tid, ?int $tedarikci_id, ?float $tLat, ?float $tLng, string $mode): int {
    // Tedarikçi yoksa rota oluşturulamaz
    if (!$tedarikci_id) {
      $pdo->prepare("UPDATE teslimatlar SET durum = 'Planlanamadı' WHERE id = ?")->execute([$tid]);
      return 0;
    }

    // Depo konumu
    $sq = $pdo->prepare("SELECT latitude, longitude FROM tedarikciler WHERE id = ?");
    $sq->execute([$tedarikci_id]);
    $s = $sq->fetch();

    $dLat = $s['latitude'] ?? null;
    $dLng = $s['longitude'] ?? null;

    if ($dLat !== null && $dLng !== null && Geo::isValid($dLat, $dLng)) {
      list($dLat, $dLng) = Geo::clampTurkey((float)$dLat, (float)$dLng);
    }

    // Mesafe ve tahmini süre
    $km = null;
    if ($dLat !== null && $dLng !== null && $tLat !== null && $tLng !== null && Geo::isValid($tLat, $tLng)) {
      $km = Geo::distanceKm((float)$dLat, (float)$dLng, (float)$tLat, (float)$tLng);
    }
    $speed = ($mode === 'Kara') ? 50 : (($mode === 'Hava') ? 150 : 30);
    $hrs   = ($km !== null && $km > 0) ? ($km / $speed) : 0;

    // Rota kaydı
    $ins = $pdo->prepare("
      INSERT INTO rotalar
        (teslimat_id, baslangic_lat, baslangic_lng, bitis_lat, bitis_lng, mesafe_km, sure_saat, durum, created_at)
      VALUES
        (?,?,?,?,?,?,?, 'Açık', NOW())
    ");
    $ins->execute([$tid, $dLat, $dLng, $tLat, $tLng, $km, $hrs]);

    return (int)$pdo->lastInsertId();
  }

  // Durum güncelle ve stok düş (önerilen: Başlatıldı veya Teslim Edildi aşamasında)
  public static function updateStatusAndStock(PDO $pdo, int $teslimat_id, string $newStatus): bool {
    // Teslimat detayları
    $st = $pdo->prepare("SELECT t.tedarikci_id, t.malzeme_id, t.miktar, t.durum FROM teslimatlar t WHERE t.id = ?");
    $st->execute([$teslimat_id]);
    $ts = $st->fetch();

    if (!$ts) return false;

    // İdempotent: aynı duruma tekrar geçme
    if ($ts['durum'] === $newStatus) return true;

    // Stok düşümü (Teslim Edildi aşamasında kesin; Başlatıldı aşamasında opsiyonel rezerv)
    if ($newStatus === 'Başlatıldı') {
      // İsteğe bağlı rezerv mantığı (aktif etmek için yorumları açın)
      // if ($ts['tedarikci_id'] && $ts['malzeme_id'] && $ts['miktar'] > 0) {
      //   $up = $pdo->prepare("UPDATE stoklar SET miktar = GREATEST(0, miktar - ?) WHERE tedarikci_id = ? AND malzeme_id = ?");
      //   $up->execute([(int)$ts['miktar'], (int)$ts['tedarikci_id'], (int)$ts['malzeme_id']]);
      // }
    } elseif ($newStatus === 'Teslim Edildi') {
      if ($ts['tedarikci_id'] && $ts['malzeme_id'] && (int)$ts['miktar'] > 0) {
        $up = $pdo->prepare("
          UPDATE stoklar
          SET miktar = GREATEST(0, miktar - ?)
          WHERE tedarikci_id = ? AND malzeme_id = ?
        ");
        $up->execute([(int)$ts['miktar'], (int)$ts['tedarikci_id'], (int)$ts['malzeme_id']]);
      }
    }

    // Durum güncelle
    $upd = $pdo->prepare("UPDATE teslimatlar SET durum = ?, updated_at = NOW() WHERE id = ?");
    return $upd->execute([$newStatus, $teslimat_id]);
  }
}





