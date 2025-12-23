<?php
// Türkçe Açıklama: Taşıma ve rota planı — tekil teslimat mantığı ile
require_once '../config/db.php'; require_once '../lib/Transport.php'; require_once '../lib/Sourcing.php'; require_once '../lib/Notify.php';
$talep_id=intval($_POST['talep_id']??0); if($talep_id<=0){ http_response_code(400); echo json_encode(['ok'=>false,'msg'=>'Talep ID gerekli']); exit; }
$alloc=Sourcing::allocate($pdo,$talep_id); // Türkçe Açıklama: Tahsis yoksa boş döner
$planned=Transport::plan($pdo,$talep_id,$alloc);
$pdo->prepare("UPDATE talepler SET durum='Planlandi' WHERE id=?")->execute([$talep_id]);
$pdo->prepare("INSERT INTO durum_loglari(tip,referans_id,eski_durum,yeni_durum,tarih) VALUES(?,?,?,?,NOW())")->execute(['talep',$talep_id,'Tedarik Atandi','Planlandi']);
Notify::push($pdo,'bilgi',"Taşıma planlandı: Talep #$talep_id");
echo json_encode(['ok'=>true,'planned'=>$planned]);




