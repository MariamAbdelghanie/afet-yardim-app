<?php
// Türkçe Açıklama: Gönüllü için uygun talepler
require_once '../config/db.php';
$tasks=$pdo->query("SELECT id,sehir,mahalle,kisi_sayisi,latitude,longitude,durum FROM talepler WHERE durum IN ('Beklemede','Tedarik Atandi','Planlandi','Yolda') ORDER BY id DESC LIMIT 100")->fetchAll();
echo json_encode(['tasks'=>$tasks]);


