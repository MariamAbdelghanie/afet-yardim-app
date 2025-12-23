<?php
// Türkçe Açıklama: Yol durumu sorguları için basit API
class Road {
  public static function hasClosures(PDO $pdo):bool{
    $q=$pdo->query("SELECT COUNT(*) FROM yol_durumu WHERE durum='Kapali'"); return intval($q->fetchColumn())>0;
  }
}

