<?php
require_once __DIR__.'/Geo.php';

class Priority {

    // Öncelik puanı hesaplama
    // Girdi: kişi sayısı, aciliyet seviyesi, ihtiyaç listesi, talep konumu (lat/lng), opsiyonel PDO (depolara erişim için)
    public static function calculate(
        int $kisi,
        string $acil,
        array $needsList,
        ?float $lat = null,
        ?float $lng = null,
        PDO $pdo = null
    ): int {
        // Temel puan ve kişi ağırlığı
        $base = 40; // temel puan
        $w_people = min(40, max(0, $kisi) * 4);

        // Aciliyet puanı
        if ($acil === 'CokAcil') {
            $w_acil = 30;
        } elseif ($acil === 'Acil') {
            $w_acil = 20;
        } else {
            $w_acil = 10;
        }

        // Kritik ihtiyaç kontrolü (örnek: su / ilaç)
        $hasCritical = (!empty($needsList['su']) || !empty($needsList['ilac'])) ? 15 : 5;

        // Mesafe puanı (talep konumu ve depolar mevcutsa)
        $w_distance = 0;
        if ($pdo && $lat !== null && $lng !== null && Geo::isValid($lat, $lng)) {
            // Koordinatları Türkiye sınırlarına sıkıştır
            list($lat, $lng) = Geo::clampTurkey($lat, $lng);

            // En yakın depo mesafesi (km)
            $depots = $pdo->query("SELECT latitude, longitude FROM tedarikciler")->fetchAll();
            $minDist = INF;
            foreach ($depots as $d) {
                $dLat = $d['latitude'] ?? null;
                $dLng = $d['longitude'] ?? null;
                if ($dLat !== null && $dLng !== null && Geo::isValid($dLat, $dLng)) {
                    $dist = Geo::distanceKm($lat, $lng, (float)$dLat, (float)$dLng);
                    if ($dist !== null && $dist < $minDist) {
                        $minDist = $dist;
                    }
                }
            }

            // Mesafeye göre puan ekle (yakın talep öncelikli)
            if ($minDist === INF) {
                $w_distance = 0;
            } elseif ($minDist < 10) {
                $w_distance = 0;
            } elseif ($minDist < 50) {
                $w_distance = 10;
            } else {
                $w_distance = 20;
            }
        }

        // Toplam puan ve sınırlandırma
        $score = $base + $w_people + $w_acil + $hasCritical + $w_distance;
        return max(0, min(100, (int)$score));
    }

    // Yinelenen talep kontrolü (son 6 saat içinde aynı şehir/mahalle/sokak)
    public static function isDuplicate(PDO $pdo, string $sehir, string $mahalle, string $sokak, ?float $lat, ?float $lng): bool {
        // Not: lat/lng şu an kullanılmıyor; adres bazlı basit tekrar kontrolü
        $q = $pdo->prepare("
            SELECT COUNT(*) FROM talepler
            WHERE sehir = ? AND mahalle = ? AND sokak = ?
              AND created_at > NOW() - INTERVAL 6 HOUR
        ");
        $q->execute([$sehir, $mahalle, $sokak]);
        return (int)$q->fetchColumn() > 0;
    }
}



