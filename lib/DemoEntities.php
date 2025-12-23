<?php
// lib/DemoEntities.php
class Talep {
    private $id;
    private $sehir;
    private $mahalle;
    private $kisiSayisi;
    private $aciliyet;
    private $latitude;
    private $longitude;

    public function __construct($id, $sehir, $mahalle, $kisiSayisi, $aciliyet, $lat, $lng){
        $this->id = $id;
        $this->sehir = $sehir;
        $this->mahalle = $mahalle;
        $this->kisiSayisi = $kisiSayisi;
        $this->aciliyet = $aciliyet;
        $this->latitude = $lat;
        $this->longitude = $lng;
    }
    public function getOncelikPuani(){
        $puan = $this->kisiSayisi;
        if($this->aciliyet === 'Yüksek') $puan += 50;
        elseif($this->aciliyet === 'Orta') $puan += 20;
        return $puan;
    }
    public function getKonum(){
        return [$this->latitude, $this->longitude];
    }
    public function getBilgi(){
        return "Talep #{$this->id} ({$this->sehir}/{$this->mahalle}) - Öncelik: " . $this->getOncelikPuani();
    }
}

class Depo {
    private $id;
    private $ad;
    private $stoklar = [];
    private $latitude;
    private $longitude;

    public function __construct($id, $ad, $lat, $lng){
        $this->id = $id;
        $this->ad = $ad;
        $this->latitude = $lat;
        $this->longitude = $lng;
    }

    public function stokEkle($malzeme, $miktar){
        $this->stoklar[$malzeme] = $miktar;
    }

    public function getKonum(){
        return [$this->latitude, $this->longitude];
    }

    public function getBilgi(){
        $stokStr = "";
        foreach($this->stoklar as $malzeme => $miktar){
            $stokStr .= "$malzeme: $miktar\n";
        }
        return "Depo #{$this->id} - {$this->ad}\nStoklar:\n" . $stokStr;
    }
}

class Teslimat {
    private $id;
    private $talep;
    private $depo;
    private $tasimaModu;
    private $durum;
    public function __construct($id, Talep $talep, Depo $depo, $tasimaModu, $durum){
        $this->id = $id;
        $this->talep = $talep;
        $this->depo = $depo;
        $this->tasimaModu = $tasimaModu;
        $this->durum = $durum;
    }
    public function getRota(){
        return [
            'from' => $this->depo->getKonum(),
            'to' => $this->talep->getKonum()
        ];
    }
    public function getBilgi(){
        return "Teslimat #{$this->id}\n" .
               $this->depo->getBilgi() . "\n" .
               $this->talep->getBilgi() . "\n" .
               "Taşıma: {$this->tasimaModu} | Durum: {$this->durum}";
    }
}   //ornek 
$talep = new Talep(101, "Bartın", "Kavaklı", 30, "Yüksek", 41.63, 32.34);
$depo = new Depo(201, "Bartın Ana Depo", 41.65, 32.30);
$depo->stokEkle("Battaniye", 120);
$depo->stokEkle("Su", 300);
$teslimat = new Teslimat(301, $talep, $depo, "Kara", "Planlandı");
echo nl2br($teslimat->getBilgi());
