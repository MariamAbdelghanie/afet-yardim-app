-- Veritabanı: afet_yardim_db

-- 1. Kullanıcılar (roller için)
CREATE TABLE kullanicilar (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ad VARCHAR(100),
  email VARCHAR(200),
  rol VARCHAR(50)
);

INSERT INTO kullanicilar (ad,email,rol) VALUES
('Yönetici','admin@example.com','Admin'),
('Tedarikçi','supplier@example.com','Tedarikci'),
('Gönüllü','vol@example.com','Gonullu'),
('Sürücü','driver@example.com','Surucu');

-- 2. Malzemeler
CREATE TABLE malzemeler (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ad VARCHAR(100),
  kategori VARCHAR(50),
  birim VARCHAR(20)
);

INSERT INTO malzemeler (ad,kategori,birim) VALUES
('Gıda','Standart','kg'),
('Su','Kritik','adet'),
('Tıbbi','Kritik','adet'),
('Hijyen','Standart','adet'),
('Barınma','Standart','adet');

-- 3. Tedarikçiler
CREATE TABLE tedarikciler (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ad VARCHAR(100),
  sehir VARCHAR(100),
  latitude DOUBLE,
  longitude DOUBLE,
  kapasite INT,
  yerel_mi BOOLEAN
);

INSERT INTO tedarikciler (ad,sehir,latitude,longitude,kapasite,yerel_mi) VALUES
('Bartın Yerel Depo','Bartın',41.635000,32.337000,500,1),
('Karabük Bölgesel Depo','Karabük',41.208000,32.627000,1000,0);

-- 4. Stoklar
CREATE TABLE stoklar (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tedarikci_id INT,
  malzeme_id INT,
  miktar INT
);

INSERT INTO stoklar (tedarikci_id,malzeme_id,miktar) VALUES
(1,1,200),(1,4,150),(1,5,50),
(2,1,800),(2,2,300),(2,3,200);

-- 5. Talepler
CREATE TABLE talepler (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kullanici_id INT,
  sehir VARCHAR(100),
  mahalle VARCHAR(100),
  sokak VARCHAR(200),
  latitude DOUBLE,
  longitude DOUBLE,
  kisi_sayisi INT,
  aciliyet_seviyesi VARCHAR(50),
  aciklama TEXT,
  ikamet VARCHAR(200),
  telefon VARCHAR(50),
  durum VARCHAR(50),
  created_at DATETIME
);

-- 6. Talep Malzemeleri
CREATE TABLE talep_malzemeler (
  id INT AUTO_INCREMENT PRIMARY KEY,
  talep_id INT,
  malzeme_id INT,
  miktar VARCHAR(100)
);

-- 7. Teslimatlar
CREATE TABLE teslimatlar (
  id INT AUTO_INCREMENT PRIMARY KEY,
  talep_id INT,
  tedarikci_id INT,
  tasima_modu VARCHAR(50),
  durum VARCHAR(50)
);

-- 8. Rotalar
CREATE TABLE rotalar (
  id INT AUTO_INCREMENT PRIMARY KEY,
  teslimat_id INT,
  baslangic_lat DOUBLE,
  baslangic_lng DOUBLE,
  bitis_lat DOUBLE,
  bitis_lng DOUBLE,
  mesafe_km DOUBLE,
  sure_saat DOUBLE,
  durum VARCHAR(50)
);

-- 9. Durum Logları
CREATE TABLE durum_loglari (
  id INT AUTO_INCREMENT PRIMARY KEY,
  tip VARCHAR(50),
  referans_id INT,
  eski_durum VARCHAR(50),
  yeni_durum VARCHAR(50),
  tarih DATETIME
);

-- 10. Yol Durumu (kapalı yollar vs.)
CREATE TABLE yol_durumu (
  id INT AUTO_INCREMENT PRIMARY KEY,
  lat DOUBLE,
  lng DOUBLE,
  durum VARCHAR(50),
  created_at DATETIME
);


