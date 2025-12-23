<?php
// Türkçe Açıklama: Yakınlık öncelikli tedarik planlama (depo seçimi)
// Not: Bu sınıf stok rezervasyonu veya düşümü anında yapmaz; planlama döner.
// Stok düşümü, teslimat sürecinde "Başlatıldı" veya "Teslim Edildi" aşamasında yapılması önerilir.

require_once __DIR__.'/Geo.php';

class Sourcing {

  // Bu talep için malzemeleri depolara tahsis et (yakın ve stok yeterli olan)
  public static function allocate(PDO $pdo, int $talep_id): array {
    // 0) Aynı talep için daha önce teslimat varsa yeniden tahsis yapma (opsiyonel kural)
    $has = $pdo->prepare("SELECT COUNT(*) FROM teslimatlar WHERE talep_id = ?");
    $has->execute([$talep_id]);
    if ((int)$has->fetchColumn() > 0) {
      return []; // Mevcut teslimatlar varken tahsis atlaması
    }

    // 1) Talep konumunu ve şehrini al
    $tq = $pdo->prepare("SELECT sehir, latitude, longitude FROM talepler WHERE id = ?");
    $tq->execute([$talep_id]);
    $t = $tq->fetch();

    $tLat = $t['latitude'] ?? null;
    $tLng = $t['longitude'] ?? null;
    if ($tLat !== null && $tLng !== null && Geo::isValid($tLat, $tLng)) {
      list($tLat, $tLng) = Geo::clampTurkey((float)$tLat, (float)$tLng);
    }

    // 2) Talep edilen malzemeleri al
    $itemsQ = $pdo->prepare("SELECT malzeme_id, miktar FROM talep_malzemeler WHERE talep_id = ?");
    $itemsQ->execute([$talep_id]);
    $items = $itemsQ->fetchAll();

    $result = [];

    foreach ($items as $it) {
      $mid = isset($it['malzeme_id']) ? (int)$it['malzeme_id'] : 0;
      $qty = is_numeric($it['miktar']) ? max(1, (int)$it['miktar']) : 1;

      // 2.a) Özel metinli ihtiyaç (malzeme_id yok)
      if ($mid === 0) {
        $result[] = [
          'malzeme_id'   => null,
          'tedarikci_id' => null,
          'miktar'       => $it['miktar'],
          'seviye'       => 'ozel'
        ];
        continue;
      }

      // 3) Aynı şehirde yerel depo (stok yeterli ise) ve en yakın
      $dep = $pdo->prepare("
        SELECT t.id, t.latitude, t.longitude, s.id AS stok_id, s.miktar
        FROM tedarikciler t
        JOIN stoklar s ON s.tedarikci_id = t.id
        WHERE t.sehir = ? AND (t.yerel_mi = 1 OR t.yerel_mi IS NULL)
          AND s.malzeme_id = ? AND s.miktar >= ?
      ");
      $dep->execute([$t['sehir'] ?? '', $mid, $qty]);
      $local = $dep->fetchAll();

      $candidate = null;
      $bestDist = INF;

      // 3.a) Yerel depo içinden en yakın
      foreach ($local as $d) {
        $dLat = $d['latitude'] ?? null;
        $dLng = $d['longitude'] ?? null;
        if ($tLat !== null && $tLng !== null && $dLat !== null && $dLng !== null && Geo::isValid($dLat, $dLng)) {
          $dist = Geo::distanceKm((float)$tLat, (float)$tLng, (float)$dLat, (float)$dLng);
          if ($dist !== null && $dist < $bestDist) {
            $bestDist = $dist;
            $candidate = $d;
          }
        } else {
          // Konum yoksa stok yeterli ilk depoyu aday al
          $candidate = $d;
          $bestDist = 0;
          break;
        }
      }

      // 4) Yerel aday yoksa Türkiye genelinde en yakın depo
      if (!$candidate) {
        $dep2 = $pdo->prepare("
          SELECT t.id, t.latitude, t.longitude, s.id AS stok_id, s.miktar
          FROM tedarikciler t
          JOIN stoklar s ON s.tedarikci_id = t.id
          WHERE s.malzeme_id = ? AND s.miktar >= ?
        ");
        $dep2->execute([$mid, $qty]);
        $all = $dep2->fetchAll();

        foreach ($all as $d) {
          $dLat = $d['latitude'] ?? null;
          $dLng = $d['longitude'] ?? null;
          if ($tLat !== null && $tLng !== null && $dLat !== null && $dLng !== null && Geo::isValid($dLat, $dLng)) {
            $dist = Geo::distanceKm((float)$tLat, (float)$tLng, (float)$dLat, (float)$dLng);
            if ($dist !== null && $dist < $bestDist) {
              $bestDist = $dist;
              $candidate = $d;
            }
          } else {
            // Konum yoksa stok yeterli ilk depoyu aday al
            $candidate = $d;
            $bestDist = 0;
            break;
          }
        }
      }

      // 5) Sonuç dizisine yaz (stok düşümünü burada yapmıyoruz)
      if ($candidate) {
        $result[] = [
          'malzeme_id'   => $mid,
          'tedarikci_id' => (int)$candidate['id'],
          'miktar'       => $qty,
          'seviye'       => 'yakin',
          'mesafe_km'    => ($bestDist === INF ? null : $bestDist),
          'stok_id'      => (int)$candidate['stok_id'] // ileride stok güncellemesi için faydalı
        ];
      } else {
        $result[] = [
          'malzeme_id'   => $mid,
          'tedarikci_id' => null,
          'miktar'       => $qty,
          'seviye'       => 'yetersiz',
          'mesafe_km'    => null
        ];
      }
    }

    return $result;
  }

  // Eğer planlama aşamasında stok rezervasyonu yapmak istersen:
  // Not: Rezerv mantığı için stoklar tablosunda 'rezerv_miktar' gibi bir kolon önerilir.
  public static function reserveStock(PDO $pdo, int $stok_id, int $qty): void {
    $u = $pdo->prepare("UPDATE stoklar SET miktar = GREATEST(0, miktar - ?) WHERE id = ?");
    $u->execute([$qty, $stok_id]);
  }
}






