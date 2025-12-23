<?php
// Türkçe Açıklama: Tedarik tahsisi — tekrar çalıştırıldığında mevcut teslimat varsa yeni üretmez
require_once '../config/db.php'; require_once '../lib/Sourcing.php'; require_once '../lib/Notify.php';
$talep_id=intval($_POST['talep_id']??0); if($talep_id<=0){ http_response_code(400); echo json_encode(['ok'=>false,'msg'=>'Talep ID gerekli']); exit; }
$alloc=Sourcing::allocate($pdo,$talep_id);
if(!empty($alloc)){
  $pdo->prepare("UPDATE talepler SET durum='Tedarik Atandi' WHERE id=?")->execute([$talep_id]);
  $pdo->prepare("INSERT INTO durum_loglari(tip,referans_id,eski_durum,yeni_durum,tarih) VALUES(?,?,?,?,NOW())")->execute(['talep',$talep_id,'Beklemede','Tedarik Atandi']);
  Notify::push($pdo,'bilgi',"Tedarik atandı: Talep #$talep_id");
}
echo json_encode(['ok'=>true,'allocations'=>$alloc]);



