<?php
// Türkçe Açıklama: Sürücü teslimat durumu güncelleme ve talep durumunu senkronize etme
require_once '../config/db.php'; require_once '../lib/Notify.php';
$id=intval($_POST['teslimat_id']??0); $durum=$_POST['durum']??''; if($id<=0||$durum===''){ http_response_code(400); echo json_encode(['ok'=>false,'msg'=>'Teslimat ve durum gerekli']); exit; }
$prev=$pdo->prepare("SELECT durum,talep_id FROM teslimatlar WHERE id=?"); $prev->execute([$id]); $row=$prev->fetch();
$pdo->prepare("UPDATE teslimatlar SET durum=? WHERE id=?")->execute([$durum,$id]);

// Türkçe Açıklama: Talep genel durumunu senkronize et
if($durum==='Delivered'){
  $pdo->prepare("UPDATE talepler SET durum='Teslim Edildi' WHERE id=?")->execute([$row['talep_id']]);
} elseif($durum==='En Route'){
  $pdo->prepare("UPDATE talepler SET durum='Yolda' WHERE id=?")->execute([$row['talep_id']]);
}

$pdo->prepare("INSERT INTO durum_loglari(tip,referans_id,eski_durum,yeni_durum,tarih) VALUES(?,?,?,?,NOW())")->execute(['teslimat',$id,$row['durum'],$durum]);
Notify::push($pdo,'bilgi',"Teslimat #$id: $durum");
echo json_encode(['ok'=>true]);



