<?php
// Türkçe Açıklama: Talep takibi verisi
require_once '../config/db.php';
$talep_id=intval($_GET['talep_id']??0); if($talep_id<=0){ http_response_code(400); echo json_encode(['ok'=>false,'msg'=>'Talep ID gerekli']); exit; }
$tq=$pdo->prepare("SELECT id,sehir,mahalle,sokak,latitude,longitude,aciliyet_seviyesi,durum FROM talepler WHERE id=?"); $tq->execute([$talep_id]); $t=$tq->fetch();
if(!$t){ echo json_encode(['ok'=>false,'msg'=>'Talep bulunamadı']); exit; }

$tes=$pdo->prepare("SELECT te.id,te.tasima_modu,te.durum,td.ad AS depo,td.sehir AS depo_sehir,td.latitude AS depo_lat,td.longitude AS depo_lng FROM teslimatlar te LEFT JOIN tedarikciler td ON td.id=te.tedarikci_id WHERE te.talep_id=?"); $tes->execute([$talep_id]); $deliveries=$tes->fetchAll();
$rot=$pdo->prepare("SELECT * FROM rotalar WHERE teslimat_id IN (SELECT id FROM teslimatlar WHERE talep_id=?)"); $rot->execute([$talep_id]); $routes=$rot->fetchAll();

echo json_encode(['ok'=>true,'talep'=>$t,'deliveries'=>$deliveries,'routes'=>$routes]);


