<?php
// Türkçe Açıklama: Son 10 bilgi mesajı
require_once '../config/db.php';
$rows=$pdo->query("SELECT yeni_durum AS mesaj,tarih FROM durum_loglari WHERE tip='bilgi' ORDER BY id DESC LIMIT 10")->fetchAll();
echo json_encode(['items'=>$rows]);

