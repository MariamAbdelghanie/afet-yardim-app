<?php
// Türkçe Açıklama: Tedarikçi kabulü — talep durumunu güncelle
require_once '../config/db.php';
$talep_id=intval($_POST['talep_id']??0); $mid=intval($_POST['malzeme_id']??0);
if($talep_id<=0||$mid<=0){ http_response_code(400); echo json_encode(['ok'=>false]); exit; }
$pdo->prepare("UPDATE talepler SET durum='Hazirlaniyor' WHERE id=?")->execute([$talep_id]);
$pdo->prepare("INSERT INTO durum_loglari(tip,referans_id,eski_durum,yeni_durum,tarih) VALUES(?,?,?,?,NOW())")->execute(['talep',$talep_id,'Tedarik Atandi','Hazirlaniyor']);
echo json_encode(['ok'=>true]);


