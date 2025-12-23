<?php
// Türkçe Açıklama: Basit bildirim/log kaydı
class Notify {
  public static function push(PDO $pdo,string $tip,string $mesaj):void{
    $ins=$pdo->prepare("INSERT INTO durum_loglari(tip,referans_id,eski_durum,yeni_durum,tarih) VALUES(?,?,?,?,NOW())");
    $ins->execute([$tip,0,null,$mesaj]);
  }
}


