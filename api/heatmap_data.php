<?php
// Türkçe Açıklama: Harita sıcaklık verisi — koordinatı olan talepler
require_once '../config/db.php';
$rows=$pdo->query("SELECT sehir,latitude,longitude,kisi_sayisi FROM talepler WHERE latitude IS NOT NULL AND longitude IS NOT NULL")->fetchAll();
echo json_encode(['points'=>$rows]);

