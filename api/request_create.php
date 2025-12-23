<?php
// Türkçe Açıklama: Talep oluşturma — misafir erişimi serbest
require_once '../config/db.php'; require_once '../lib/Helpers.php'; require_once '../lib/Priority.php'; require_once '../lib/Notify.php';

$sehir=sanitize($_POST['sehir']??''); $mahalle=sanitize($_POST['mahalle']??''); $sokak=sanitize($_POST['sokak']??'');
$kisi=max(1,toInt($_POST['kisi_sayisi']??1)); $acil=sanitize($_POST['aciliyet_seviyesi']??'Normal');
$lat=isset($_POST['latitude'])?floatval($_POST['latitude']):null; $lng=isset($_POST['longitude'])?floatval($_POST['longitude']):null;
$aciklama=sanitize($_POST['aciklama']??''); $ikamet=sanitize($_POST['ikamet']??''); $telefon=sanitize($_POST['telefon']??'');
$needs_list=$_POST['needs_list']??[]; $extra=sanitize($_POST['extra_need']??'');

if($sehir===''||$mahalle===''||$sokak===''){ http_response_code(400); echo json_encode(['ok'=>false,'msg'=>'Eksik alanlar mevcut']); exit; }
if(Priority::isDuplicate($pdo,$sehir,$mahalle,$sokak,$lat,$lng)){ echo json_encode(['ok'=>false,'msg'=>'Benzer talep mevcut (6 saat içinde)']); exit; }

$score=Priority::calculate($kisi,$acil,$needs_list);

// Türkçe Açıklama: Talep kaydı
$stmt=$pdo->prepare("INSERT INTO talepler(kullanici_id,sehir,mahalle,sokak,latitude,longitude,kisi_sayisi,aciliyet_seviyesi,aciklama,ikamet,telefon,durum,created_at) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,NOW())");
$stmt->execute([null,$sehir,$mahalle,$sokak,$lat,$lng,$kisi,$acil,$aciklama,$ikamet,$telefon,'Beklemede']); $talep_id=$pdo->lastInsertId();

// Türkçe Açıklama: İhtiyaçlar — malzeme ID'ye eşleyip miktar=1
foreach($needs_list as $key=>$val){
  if($val){
    $mid=Helpers::mapNeedToMalzeme($pdo,$key);
    if($mid){ $pdo->prepare("INSERT INTO talep_malzemeler(talep_id,malzeme_id,miktar) VALUES(?,?,?)")->execute([$talep_id,$mid,1]); }
  }
}
if($extra){ $pdo->prepare("INSERT INTO talep_malzemeler(talep_id,malzeme_id,miktar) VALUES(?,?,?)")->execute([$talep_id,null,$extra]); }

$pdo->prepare("INSERT INTO durum_loglari(tip,referans_id,eski_durum,yeni_durum,tarih) VALUES(?,?,?,?,NOW())")->execute(['talep',$talep_id,null,'Beklemede']);
Notify::push($pdo,'bilgi',"Yeni talep: #$talep_id ($sehir/$mahalle)");

echo json_encode(['ok'=>true,'talep_id'=>$talep_id,'priority'=>$score]);






