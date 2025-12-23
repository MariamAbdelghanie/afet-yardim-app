<?php
// Türkçe Açıklama: Seçilen şehir için özet (yoğunluk, depolar, stoklar, teslimatlar, rotalar)
require_once '../config/db.php';
$sehir=$_GET['sehir']??'Bartın';

$agg=$pdo->prepare("SELECT sehir,mahalle,COUNT(id) AS talep_sayisi,SUM(kisi_sayisi) AS toplam_kisi FROM talepler WHERE sehir=? GROUP BY sehir,mahalle ORDER BY toplam_kisi DESC");
$agg->execute([$sehir]); $agg=$agg->fetchAll();

$dep=$pdo->prepare("SELECT id,ad,sehir,latitude,longitude,kapasite,yerel_mi FROM tedarikciler WHERE sehir=?");
$dep->execute([$sehir]); $depolar=$dep->fetchAll();
foreach($depolar as &$d){
  $rows=$pdo->prepare("SELECT m.ad AS malzeme,SUM(s.miktar) AS toplam_miktar FROM stoklar s JOIN malzemeler m ON m.id=s.malzeme_id WHERE s.tedarikci_id=? GROUP BY m.ad");
  $rows->execute([$d['id']]); $d['stok']=$rows->fetchAll();
}

$tes=$pdo->prepare("SELECT t.id,t.talep_id,t.tasima_modu,t.durum,tl.sehir,tl.mahalle FROM teslimatlar t JOIN talepler tl ON tl.id=t.talep_id WHERE tl.sehir=? ORDER BY t.id DESC");
$tes->execute([$sehir]); $teslimatlar=$tes->fetchAll();

$rot=$pdo->prepare("SELECT r.* FROM rotalar r JOIN teslimatlar t ON t.id=r.teslimat_id JOIN talepler tl ON tl.id=t.talep_id WHERE tl.sehir=?");
$rot->execute([$sehir]); $rotalar=$rot->fetchAll();

echo json_encode(['agg'=>$agg,'depolar'=>$depolar,'teslimatlar'=>$teslimatlar,'rotalar'=>$rotalar]);

