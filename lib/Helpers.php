<?php
// Yardımcı fonksiyonlar dosyası (Helpers.php)
// Bu dosya, genel temizlik (sanitize), tip dönüşümleri ve ihtiyaç -> malzeme eşlemesi gibi yardımcı işlevleri içerir.

/**
 * Türkçe Açıklama:
 * sanitize() fonksiyonu, kullanıcıdan gelen verileri temizler.
 * HTML özel karakterlerini filtreler ve baştaki/sondaki boşlukları kaldırır.
 */
function sanitize(string $s): string {
    return trim(filter_var($s, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
}

/**
 * Türkçe Açıklama:
 * toInt() fonksiyonu, gelen değeri tam sayıya çevirir.
 * Eğer değer null veya boş ise 0 döner.
 */
function toInt($v): int {
    return intval($v ?? 0);
}

class Helpers {

    /**
     * Türkçe Açıklama:
     * mapNeedToMalzeme() fonksiyonu, ihtiyaç kategorisini (gida, su, ilac, hijyen, barinma)
     * ilgili malzeme adına eşler ve veritabanındaki malzeme_id değerini döndürür.
     *
     * Örnek:
     * - 'gida' -> 'Gıda'
     * - 'su'   -> 'Su'
     * - 'ilac' -> 'Tıbbi'
     * - 'hijyen' -> 'Hijyen'
     * - 'barinma' -> 'Barınma'
     */
    public static function mapNeedToMalzeme(PDO $pdo, string $key): ?int {
        // Kategori -> Malzeme adı eşleme tablosu
        $map = [
            'gida'    => 'Gıda',
            'su'      => 'Su',
            'ilac'    => 'Tıbbi',
            'hijyen'  => 'Hijyen',
            'barinma' => 'Barınma'
        ];

        // Eğer eşleme yoksa null döner
        $ad = $map[$key] ?? null;
        if (!$ad) return null;

        // Veritabanında malzeme tablosundan ID çek
        $q = $pdo->prepare("SELECT id FROM malzemeler WHERE ad=? LIMIT 1");
        $q->execute([$ad]);
        $id = $q->fetchColumn();

        // Eğer bulunduysa integer döner, yoksa null
        return $id ? intval($id) : null;
    }
}






