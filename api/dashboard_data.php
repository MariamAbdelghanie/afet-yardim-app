<?php
require_once '../config/db.php';
$agg=$pdo->query("SELECT sehir,mahalle,COUNT(id) AS talep_sayisi,SUM(kisi_sayisi) AS toplam_kisi FROM talepler GROUP BY sehir,mahalle ORDER BY toplam_kisi DESC")->fetchAll();
$depolar=$pdo->query("SELECT id,ad,sehir,latitude,longitude,kapasite,yerel_mi FROM tedarikciler")->fetchAll();
$stok=$pdo->query("SELECT tedarikci_id,m.ad AS malzeme,SUM(s.miktar) AS toplam_miktar FROM stoklar s JOIN malzemeler m ON m.id=s.malzeme_id GROUP BY tedarikci_id,m.ad")->fetchAll();
$tes=$pdo->query("SELECT t.id,t.talep_id,t.tasima_modu,t.durum,tl.sehir,tl.mahalle FROM teslimatlar t JOIN talepler tl ON tl.id=t.talep_id ORDER BY t.id DESC")->fetchAll();
$rotalar=$pdo->query("SELECT teslimat_id,baslangic_lat,baslangic_lng,bitis_lat,bitis_lng,mesafe_km,sure_saat,durum FROM rotalar")->fetchAll();
echo json_encode(['agg'=>$agg,'depolar'=>$depolar,'stok'=>$stok,'teslimatlar'=>$tes,'rotalar'=>$rotalar]);


