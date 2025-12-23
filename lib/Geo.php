<?php
// lib/Geo.php
// Coğrafi yardımcı fonksiyonlar: Türkiye sınır kontrolü, koordinat düzeltme, mesafe ve yön hesaplama.

class Geo {

    // Türkiye'nin yaklaşık koordinat sınırları içinde mi?
    // Kullanım: Geo::isInsideTurkey($lat, $lng)
    public static function isInsideTurkey($lat, $lng){
        return ($lat >= 35.8 && $lat <= 42.1) && ($lng >= 25.6 && $lng <= 44.8);
    }

    // Koordinatları Türkiye sınırlarına sıkıştır (clamp)
    // Kullanım: list($lat,$lng) = Geo::clampTurkey($lat,$lng)
    public static function clampTurkey($lat, $lng){
        if ($lat < 35.8) $lat = 35.8;
        if ($lat > 42.1) $lat = 42.1;
        if ($lng < 25.6) $lng = 25.6;
        if ($lng > 44.8) $lng = 44.8;
        return [$lat, $lng];
    }

    // Haversine ile iki nokta arası mesafe (km)
    // Kullanım: Geo::distanceKm($lat1,$lng1,$lat2,$lng2)
    public static function distanceKm($lat1, $lng1, $lat2, $lng2){
        if ($lat1 === null || $lng1 === null || $lat2 === null || $lng2 === null) return null;
        $R = 6371.0; // Dünya yarıçapı (km)
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $R * $c;
    }

    // İki nokta arası tahmini süre (saat) – basit model (ortalama hız parametreli)
    // Kullanım: Geo::etaHours($lat1,$lng1,$lat2,$lng2,$avgKmh=60)
    public static function etaHours($lat1, $lng1, $lat2, $lng2, $avgKmh = 60){
        $dist = self::distanceKm($lat1, $lng1, $lat2, $lng2);
        if ($dist === null) return null;
        if ($avgKmh <= 0) $avgKmh = 60;
        return $dist / $avgKmh;
    }

    // Bearing (yön açısı) hesaplama (derece, 0=Kuzey)
    // Kullanım: Geo::bearingDeg($lat1,$lng1,$lat2,$lng2)
    public static function bearingDeg($lat1, $lng1, $lat2, $lng2){
        $φ1 = deg2rad($lat1);
        $φ2 = deg2rad($lat2);
        $Δλ = deg2rad($lng2 - $lng1);
        $y = sin($Δλ) * cos($φ2);
        $x = cos($φ1)*sin($φ2) - sin($φ1)*cos($φ2)*cos($Δλ);
        $θ = atan2($y, $x);
        $deg = rad2deg($θ);
        // 0..360 normalize
        return fmod(($deg + 360.0), 360.0);
    }

    // Nokta geçerli mi (lat/lng aralık kontrolü)
    // Kullanım: Geo::isValid($lat,$lng)
    public static function isValid($lat, $lng){
        return is_numeric($lat) && is_numeric($lng) &&
               $lat >= -90 && $lat <= 90 && $lng >= -180 && $lng <= 180;
    }
}


