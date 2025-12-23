<?php
// Türkçe Açıklama: Sürücü görevleri ve rotalar
require_once '../config/db.php';
$tasks=$pdo->query("SELECT id,talep_id,tasima_modu,durum FROM teslimatlar ORDER BY id DESC LIMIT 100")->fetchAll();
$routes=$pdo->query("SELECT baslangic_lat,baslangic_lng,bitis_lat,bitis_lng FROM rotalar ORDER BY teslimat_id DESC LIMIT 100")->fetchAll();
echo json_encode(['tasks'=>$tasks,'routes'=>$routes]);
