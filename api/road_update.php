<?php
require_once '../config/db.php';
$lat=floatval($_POST['lat']??0); $lng=floatval($_POST['lng']??0); $durum=$_POST['durum']??'Kapali';
$pdo->prepare("INSERT INTO yol_durumu(lat,lng,durum,created_at) VALUES(?,?,?,NOW())")->execute([$lat,$lng,$durum]);
echo json_encode(['ok'=>true]);

