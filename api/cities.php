<?php
// Türkçe Açıklama: Depoların bulunduğu şehirlerin listesi
require_once '../config/db.php';
$cities=$pdo->query("SELECT DISTINCT sehir FROM tedarikciler ORDER BY sehir")->fetchAll(PDO::FETCH_COLUMN);
echo json_encode(['cities'=>$cities]);
