<?php
// Türkçe Açıklama: Tedarikçi için malzemeler ve atamalar
require_once '../config/db.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $mid=intval($_POST['malzeme_id']??0); $miktar=intval($_POST['miktar']??0);
  // Türkçe Açıklama: Basit stok artırma (demo)
  $pdo->prepare("UPDATE stoklar SET miktar=miktar+? WHERE malzeme_id=? LIMIT 1")->execute([$miktar,$mid]);
  echo json_encode(['ok'=>true]); exit;
}
$materials=$pdo->query("SELECT id,ad FROM malzemeler ORDER BY ad")->fetchAll();
$alloc=$pdo->query("SELECT tl.id AS talep_id,m.id AS malzeme_id,m.ad AS malzeme,tm.miktar FROM talep_malzemeler tm JOIN malzemeler m ON m.id=tm.malzeme_id JOIN talepler tl ON tl.id=tm.talep_id ORDER BY tl.id DESC LIMIT 100")->fetchAll();
echo json_encode(['materials'=>$materials,'allocations'=>$alloc]);

