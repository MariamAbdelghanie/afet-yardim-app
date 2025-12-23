-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 22, 2025 at 01:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `afet_yardim_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `durum_loglari`
--

CREATE TABLE `durum_loglari` (
  `id` int(11) NOT NULL,
  `tip` varchar(20) DEFAULT NULL,
  `referans_id` int(11) DEFAULT NULL,
  `eski_durum` varchar(30) DEFAULT NULL,
  `yeni_durum` varchar(30) DEFAULT NULL,
  `tarih` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `durum_loglari`
--

INSERT INTO `durum_loglari` (`id`, `tip`, `referans_id`, `eski_durum`, `yeni_durum`, `tarih`) VALUES
(1, 'talep', 1, NULL, 'Beklemede', '2025-12-21 15:48:28'),
(2, 'talep', 2, NULL, 'Beklemede', '2025-12-21 22:08:59'),
(3, 'bilgi', 0, NULL, 'Yeni talep: #2 (Bartın/Orduyer', '2025-12-21 22:08:59'),
(4, 'talep', 2, 'Beklemede', 'Tedarik Atandi', '2025-12-21 22:08:59'),
(5, 'bilgi', 0, NULL, 'Tedarik atandı: Talep #2', '2025-12-21 22:08:59'),
(6, 'talep', 2, 'Tedarik Atandi', 'Planlandi', '2025-12-21 22:08:59'),
(7, 'bilgi', 0, NULL, 'Taşıma planlandı: Talep #2', '2025-12-21 22:08:59'),
(8, 'talep', 3, NULL, 'Beklemede', '2025-12-21 22:23:15'),
(9, 'bilgi', 0, NULL, 'Yeni talep: #3 (Ankara/Merkez)', '2025-12-21 22:23:15'),
(10, 'talep', 3, 'Beklemede', 'Tedarik Atandi', '2025-12-21 22:23:15'),
(11, 'bilgi', 0, NULL, 'Tedarik atandı: Talep #3', '2025-12-21 22:23:15'),
(12, 'talep', 3, 'Tedarik Atandi', 'Hazirlaniyor', '2025-12-21 22:28:53'),
(13, 'teslimat', 2, 'Planlandi', 'En Route', '2025-12-21 22:30:08'),
(14, 'bilgi', 0, NULL, 'Teslimat #2: En Route', '2025-12-21 22:30:08'),
(15, 'teslimat', 2, 'En Route', 'Delivered', '2025-12-21 22:30:11'),
(16, 'bilgi', 0, NULL, 'Teslimat #2: Delivered', '2025-12-21 22:30:11'),
(17, 'teslimat', 10, 'Planlandi', 'Delivered', '2025-12-21 22:30:50'),
(18, 'bilgi', 0, NULL, 'Teslimat #10: Delivered', '2025-12-21 22:30:50'),
(19, 'teslimat', 9, 'Planlandi', 'Delivered', '2025-12-21 22:30:56'),
(20, 'bilgi', 0, NULL, 'Teslimat #9: Delivered', '2025-12-21 22:30:56'),
(21, 'talep', 4, NULL, 'Beklemede', '2025-12-22 00:43:01'),
(22, 'bilgi', 0, NULL, 'Yeni talep: #4 (Kastamonu/Merk', '2025-12-22 00:43:01'),
(23, 'talep', 4, 'Beklemede', 'Tedarik Atandi', '2025-12-22 00:43:01'),
(24, 'bilgi', 0, NULL, 'Tedarik atandı: Talep #4', '2025-12-22 00:43:01'),
(25, 'talep', 4, 'Tedarik Atandi', 'Planlandi', '2025-12-22 00:43:01'),
(26, 'bilgi', 0, NULL, 'Taşıma planlandı: Talep #4', '2025-12-22 00:43:01'),
(27, 'teslimat', 15, 'Planlandi', 'En Route', '2025-12-22 00:49:54'),
(28, 'bilgi', 0, NULL, 'Teslimat #15: En Route', '2025-12-22 00:49:54'),
(29, 'teslimat', 15, 'En Route', 'Delivered', '2025-12-22 00:49:59'),
(30, 'bilgi', 0, NULL, 'Teslimat #15: Delivered', '2025-12-22 00:49:59'),
(31, 'teslimat', 13, 'Planlandi', 'Delivered', '2025-12-22 00:50:53'),
(32, 'bilgi', 0, NULL, 'Teslimat #13: Delivered', '2025-12-22 00:50:53'),
(33, 'talep', 5, NULL, 'Beklemede', '2025-12-22 00:59:10'),
(34, 'bilgi', 0, NULL, 'Yeni talep: #5 (Bartın/G&ouml;', '2025-12-22 00:59:10'),
(35, 'talep', 5, 'Beklemede', 'Tedarik Atandi', '2025-12-22 00:59:10'),
(36, 'bilgi', 0, NULL, 'Tedarik atandı: Talep #5', '2025-12-22 00:59:10'),
(37, 'talep', 5, 'Tedarik Atandi', 'Planlandi', '2025-12-22 00:59:10'),
(38, 'bilgi', 0, NULL, 'Taşıma planlandı: Talep #5', '2025-12-22 00:59:10'),
(39, 'teslimat', 20, 'Planlandi', 'Delivered', '2025-12-22 01:00:38'),
(40, 'bilgi', 0, NULL, 'Teslimat #20: Delivered', '2025-12-22 01:00:38'),
(41, 'teslimat', 19, 'Planlandi', 'Delivered', '2025-12-22 01:00:40'),
(42, 'bilgi', 0, NULL, 'Teslimat #19: Delivered', '2025-12-22 01:00:40'),
(43, 'teslimat', 18, 'Planlandi', 'Delivered', '2025-12-22 01:00:42'),
(44, 'bilgi', 0, NULL, 'Teslimat #18: Delivered', '2025-12-22 01:00:42'),
(45, 'teslimat', 17, 'Planlandi', 'Delivered', '2025-12-22 01:00:45'),
(46, 'bilgi', 0, NULL, 'Teslimat #17: Delivered', '2025-12-22 01:00:45'),
(47, 'teslimat', 16, 'Planlandi', 'Delivered', '2025-12-22 01:00:47'),
(48, 'bilgi', 0, NULL, 'Teslimat #16: Delivered', '2025-12-22 01:00:47'),
(49, 'talep', 6, NULL, 'Beklemede', '2025-12-22 01:03:34'),
(50, 'bilgi', 0, NULL, 'Yeni talep: #6 (Ankara/Mamak)', '2025-12-22 01:03:34'),
(51, 'talep', 6, 'Beklemede', 'Tedarik Atandi', '2025-12-22 01:03:34'),
(52, 'bilgi', 0, NULL, 'Tedarik atandı: Talep #6', '2025-12-22 01:03:34'),
(53, 'talep', 6, 'Tedarik Atandi', 'Planlandi', '2025-12-22 01:03:34'),
(54, 'bilgi', 0, NULL, 'Taşıma planlandı: Talep #6', '2025-12-22 01:03:34'),
(55, 'talep', 7, NULL, 'Beklemede', '2025-12-22 01:06:07'),
(56, 'bilgi', 0, NULL, 'Yeni talep: #7 (Bartın/Kırtepe', '2025-12-22 01:06:07'),
(57, 'talep', 7, 'Beklemede', 'Tedarik Atandi', '2025-12-22 01:06:07'),
(58, 'bilgi', 0, NULL, 'Tedarik atandı: Talep #7', '2025-12-22 01:06:07'),
(59, 'talep', 7, 'Tedarik Atandi', 'Planlandi', '2025-12-22 01:06:07'),
(60, 'bilgi', 0, NULL, 'Taşıma planlandı: Talep #7', '2025-12-22 01:06:07'),
(61, 'talep', 8, NULL, 'Beklemede', '2025-12-22 02:02:45'),
(62, 'bilgi', 0, NULL, 'Yeni talep: #8 (Zonguldak/Merk', '2025-12-22 02:02:45'),
(63, 'talep', 8, 'Beklemede', 'Tedarik Atandi', '2025-12-22 02:02:45'),
(64, 'bilgi', 0, NULL, 'Tedarik atandı: Talep #8', '2025-12-22 02:02:45'),
(65, 'talep', 9, NULL, 'Beklemede', '2025-12-22 11:08:14'),
(66, 'bilgi', 0, NULL, 'Yeni talep: #9 (Bartın/Orduyer', '2025-12-22 11:08:14'),
(67, 'talep', 9, 'Beklemede', 'Tedarik Atandi', '2025-12-22 11:08:14'),
(68, 'bilgi', 0, NULL, 'Tedarik atandı: Talep #9', '2025-12-22 11:08:14'),
(69, 'teslimat', 29, 'Planlandi', 'Delivered', '2025-12-22 11:13:41'),
(70, 'bilgi', 0, NULL, 'Teslimat #29: Delivered', '2025-12-22 11:13:41'),
(71, 'teslimat', 28, 'Planlandi', 'Delivered', '2025-12-22 11:13:44'),
(72, 'bilgi', 0, NULL, 'Teslimat #28: Delivered', '2025-12-22 11:13:44'),
(73, 'teslimat', 27, 'Planlandi', 'Delivered', '2025-12-22 11:13:46'),
(74, 'bilgi', 0, NULL, 'Teslimat #27: Delivered', '2025-12-22 11:13:46'),
(75, 'teslimat', 26, 'Planlandi', 'Delivered', '2025-12-22 11:13:49'),
(76, 'bilgi', 0, NULL, 'Teslimat #26: Delivered', '2025-12-22 11:13:49'),
(77, 'teslimat', 25, 'Planlandi', 'Delivered', '2025-12-22 11:13:51'),
(78, 'bilgi', 0, NULL, 'Teslimat #25: Delivered', '2025-12-22 11:13:51'),
(79, 'talep', 9, 'Tedarik Atandi', 'Hazirlaniyor', '2025-12-22 12:01:22');

-- --------------------------------------------------------

--
-- Table structure for table `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `id` int(11) NOT NULL,
  `ad` varchar(100) DEFAULT NULL,
  `soyad` varchar(100) DEFAULT NULL,
  `telefon` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `rol` varchar(30) DEFAULT 'Afetzede',
  `kayit_tarihi` datetime DEFAULT current_timestamp(),
  `sifre_hash` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kullanicilar`
--

INSERT INTO `kullanicilar` (`id`, `ad`, `soyad`, `telefon`, `email`, `rol`, `kayit_tarihi`, `sifre_hash`) VALUES
(2, 'mariam', 'yasser', '05057412543', 'mariamabdelghanie@gmail.com', 'Afetzede', '2025-12-21 15:48:28', 'afetzede123'),
(3, 'Yönetici', NULL, NULL, 'admin@example.com', 'Admin', '2025-12-21 20:49:53', 'admin123'),
(4, 'Tedarikçi', NULL, NULL, 'supplier@example.com', 'Tedarikci', '2025-12-21 20:49:53', 'supplier123'),
(5, 'Gönüllü', NULL, NULL, 'vol@example.com', 'Gonullu', '2025-12-21 20:49:53', 'volunteer123'),
(6, 'Sürücü', NULL, NULL, 'driver@example.com', 'Surucu', '2025-12-21 20:49:53', 'driver123'),
(7, 'Yönetici', NULL, NULL, 'admin@example.com', 'Admin', '2025-12-21 23:12:21', 'admin123'),
(8, 'Tedarikçi', NULL, NULL, 'supplier@example.com', 'Tedarikci', '2025-12-21 23:12:21', 'supplier123'),
(9, 'Gönüllü', NULL, NULL, 'vol@example.com', 'Gonullu', '2025-12-21 23:12:21', 'volunteer123'),
(10, 'Sürücü', NULL, NULL, 'driver@example.com', 'Surucu', '2025-12-21 23:12:21', 'driver123');

-- --------------------------------------------------------

--
-- Table structure for table `malzemeler`
--

CREATE TABLE `malzemeler` (
  `id` int(11) NOT NULL,
  `ad` varchar(150) NOT NULL,
  `kategori` varchar(30) DEFAULT 'Standart',
  `birim` varchar(50) DEFAULT NULL,
  `miad_tarihi` date DEFAULT NULL,
  `aciklama` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `malzemeler`
--

INSERT INTO `malzemeler` (`id`, `ad`, `kategori`, `birim`, `miad_tarihi`, `aciklama`) VALUES
(1, 'Gıda', 'Standart', 'kg', NULL, NULL),
(2, 'Su', 'Kritik', 'adet', NULL, NULL),
(3, 'Tıbbi', 'Kritik', 'adet', NULL, NULL),
(4, 'Hijyen', 'Standart', 'adet', NULL, NULL),
(5, 'Barınma', 'Standart', 'adet', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rotalar`
--

CREATE TABLE `rotalar` (
  `id` int(11) NOT NULL,
  `teslimat_id` int(11) NOT NULL,
  `baslangic_lat` decimal(9,6) DEFAULT NULL,
  `baslangic_lng` decimal(9,6) DEFAULT NULL,
  `bitis_lat` decimal(9,6) DEFAULT NULL,
  `bitis_lng` decimal(9,6) DEFAULT NULL,
  `mesafe_km` decimal(10,2) DEFAULT NULL,
  `sure_saat` decimal(10,2) DEFAULT NULL,
  `durum` varchar(20) DEFAULT 'Acik',
  `guncelleme_tarihi` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rotalar`
--

INSERT INTO `rotalar` (`id`, `teslimat_id`, `baslangic_lat`, `baslangic_lng`, `bitis_lat`, `bitis_lng`, `mesafe_km`, `sure_saat`, `durum`, `guncelleme_tarihi`) VALUES
(1, 1, 41.635000, 32.337000, 41.628493, 32.340778, 0.79, 0.02, 'Acik', '2025-12-21 22:08:59'),
(2, 2, 41.208000, 32.627000, 41.628493, 32.340778, 52.50, 0.35, 'Acik', '2025-12-21 22:08:59'),
(3, 3, 41.208000, 32.627000, 41.628493, 32.340778, 52.50, 0.35, 'Acik', '2025-12-21 22:08:59'),
(4, 4, 41.635000, 32.337000, 41.628493, 32.340778, 0.79, 0.02, 'Acik', '2025-12-21 22:08:59'),
(5, 5, 41.635000, 32.337000, 41.628493, 32.340778, 0.79, 0.02, 'Acik', '2025-12-21 22:08:59'),
(6, 6, 41.635000, 32.337000, 0.000000, 0.000000, 5653.18, 113.06, 'Acik', '2025-12-21 22:23:15'),
(7, 7, 41.208000, 32.627000, 0.000000, 0.000000, 5635.74, 37.57, 'Acik', '2025-12-21 22:23:15'),
(8, 8, 41.208000, 32.627000, 0.000000, 0.000000, 5635.74, 37.57, 'Acik', '2025-12-21 22:23:15'),
(9, 9, 41.635000, 32.337000, 0.000000, 0.000000, 5653.18, 113.06, 'Acik', '2025-12-21 22:23:15'),
(10, 10, 41.635000, 32.337000, 0.000000, 0.000000, 5653.18, 113.06, 'Acik', '2025-12-21 22:23:15'),
(11, 11, 39.925000, 32.835000, 36.000000, 26.000000, 740.93, 14.82, 'Acik', '2025-12-22 00:43:01'),
(12, 12, 41.015000, 28.977000, 36.000000, 26.000000, 614.76, 4.10, 'Acik', '2025-12-22 00:43:01'),
(13, 13, 38.423000, 27.142000, 36.000000, 26.000000, 287.77, 1.92, 'Acik', '2025-12-22 00:43:01'),
(14, 14, 41.635000, 32.337000, 36.000000, 26.000000, 832.58, 16.65, 'Acik', '2025-12-22 00:43:01'),
(15, 15, 41.635000, 32.337000, 36.000000, 26.000000, 832.58, 16.65, 'Acik', '2025-12-22 00:43:01'),
(16, 16, 41.635000, 32.337000, 36.000000, 26.000000, 832.58, 16.65, 'Acik', '2025-12-22 00:59:10'),
(17, 17, 41.015000, 28.977000, 36.000000, 26.000000, 614.76, 4.10, 'Acik', '2025-12-22 00:59:10'),
(18, 18, 38.423000, 27.142000, 36.000000, 26.000000, 287.77, 1.92, 'Acik', '2025-12-22 00:59:10'),
(19, 19, 41.635000, 32.337000, 36.000000, 26.000000, 832.58, 16.65, 'Acik', '2025-12-22 00:59:10'),
(20, 20, 41.635000, 32.337000, 36.000000, 26.000000, 832.58, 16.65, 'Acik', '2025-12-22 00:59:10'),
(21, 21, 41.635000, 32.337000, 41.628493, 32.340778, 0.79, 0.02, 'Acik', '2025-12-22 01:03:34'),
(22, 22, 41.208000, 32.627000, 41.628493, 32.340778, 52.50, 0.35, 'Acik', '2025-12-22 01:03:34'),
(23, 23, 41.208000, 32.627000, 41.628493, 32.340778, 52.50, 0.35, 'Acik', '2025-12-22 01:03:34'),
(24, 24, 41.635000, 32.337000, 41.628493, 32.340778, 0.79, 0.02, 'Acik', '2025-12-22 01:03:34'),
(25, 25, 41.635000, 32.337000, 36.000000, 26.000000, 832.58, 16.65, 'Acik', '2025-12-22 01:06:07'),
(26, 26, 41.015000, 28.977000, 36.000000, 26.000000, 614.76, 4.10, 'Acik', '2025-12-22 01:06:07'),
(27, 27, 38.423000, 27.142000, 36.000000, 26.000000, 287.77, 1.92, 'Acik', '2025-12-22 01:06:07'),
(28, 28, 41.635000, 32.337000, 36.000000, 26.000000, 832.58, 16.65, 'Acik', '2025-12-22 01:06:07'),
(29, 29, 41.635000, 32.337000, 36.000000, 26.000000, 832.58, 16.65, 'Acik', '2025-12-22 01:06:07');

-- --------------------------------------------------------

--
-- Table structure for table `stoklar`
--

CREATE TABLE `stoklar` (
  `id` int(11) NOT NULL,
  `tedarikci_id` int(11) NOT NULL,
  `malzeme_id` int(11) NOT NULL,
  `miktar` int(11) NOT NULL,
  `son_guncelleme` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stoklar`
--

INSERT INTO `stoklar` (`id`, `tedarikci_id`, `malzeme_id`, `miktar`, `son_guncelleme`) VALUES
(1, 1, 1, 190, '2025-12-22 01:06:07'),
(2, 1, 4, 138, '2025-12-22 01:06:07'),
(3, 1, 5, 40, '2025-12-22 01:06:07'),
(4, 2, 1, 800, '2025-12-21 20:49:53'),
(5, 2, 2, 294, '2025-12-22 01:03:34'),
(6, 2, 3, 194, '2025-12-22 01:03:34'),
(7, 3, 1, 798, '2025-12-22 00:43:01'),
(8, 4, 2, 594, '2025-12-22 01:06:07'),
(9, 5, 3, 494, '2025-12-22 01:06:07'),
(10, 6, 4, 300, '2025-12-21 23:11:10'),
(11, 7, 5, 300, '2025-12-21 23:11:10');

-- --------------------------------------------------------

--
-- Table structure for table `talepler`
--

CREATE TABLE `talepler` (
  `id` int(11) NOT NULL,
  `kullanici_id` int(11) DEFAULT NULL,
  `sehir` varchar(100) DEFAULT NULL,
  `mahalle` varchar(100) DEFAULT NULL,
  `sokak` varchar(255) DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `kisi_sayisi` int(11) NOT NULL,
  `aciliyet_seviyesi` varchar(20) DEFAULT 'Orta',
  `aciklama` text DEFAULT NULL,
  `ikamet` varchar(200) DEFAULT NULL,
  `telefon` varchar(50) DEFAULT NULL,
  `durum` varchar(30) DEFAULT 'Beklemede',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talepler`
--

INSERT INTO `talepler` (`id`, `kullanici_id`, `sehir`, `mahalle`, `sokak`, `latitude`, `longitude`, `kisi_sayisi`, `aciliyet_seviyesi`, `aciklama`, `ikamet`, `telefon`, `durum`, `created_at`) VALUES
(1, 2, 'Bartin', 'Kemer kopru', 'Taskopru sokak', 0.000000, 0.000000, 5, 'Orta', NULL, NULL, NULL, 'Beklemede', '2025-12-21 15:48:28'),
(2, NULL, 'Bartın', 'Orduyeri', 'Koordinat: 41.628493, 32.340778', 41.628493, 32.340778, 6, 'Acil', 'evimiz yıkıldı', '99763474482', '05057412543', 'Planlandi', '2025-12-21 22:08:58'),
(3, NULL, 'Ankara', 'Merkez', 'muratpasa mahhalesi sokak7', 0.000000, 0.000000, 3, 'Acil', 'sil den dolayi cikamiyoruz evden', '22343654378', '04567654534', 'Hazirlaniyor', '2025-12-21 22:23:15'),
(4, NULL, 'Kastamonu', 'Merkez', 'mimarsinan mahallesi no 34', 0.000000, 0.000000, 9, 'Acil', 'Enkaz altinda', '99874567345', '05067563454', 'Teslim Edildi', '2025-12-22 00:43:01'),
(5, NULL, 'Bartın', 'G&ouml;lbucağı', 'yıldız sokak no 3', 0.000000, 0.000000, 5, 'Normal', '', '9987609876', '05006784532', 'Teslim Edildi', '2025-12-22 00:59:10'),
(6, NULL, 'Ankara', 'Mamak', 'Koordinat: 41.628493, 32.340778', 41.628493, 32.340778, 11, 'CokAcil', '', '6577898765', '08765436576', 'Planlandi', '2025-12-22 01:03:34'),
(7, NULL, 'Bartın', 'Kırtepe', 'necmtenı no 7', 0.000000, 0.000000, 7, 'Acil', '', '876534456', '09876543234', 'Teslim Edildi', '2025-12-22 01:06:07'),
(8, NULL, 'Zonguldak', 'Merkez', 'Koordinat: 41.446924, 31.803703', 41.446924, 31.803703, 4, 'CokAcil', '', '7778886654', '0987654564', 'Tedarik Atandi', '2025-12-22 02:02:45'),
(9, NULL, 'Bartın', 'Orduyeri', 'Koordinat: 41.038643, 28.917760', 41.038643, 28.917760, 5, 'CokAcil', '', '998745679', '0503465873', 'Hazirlaniyor', '2025-12-22 11:08:14');

-- --------------------------------------------------------

--
-- Table structure for table `talep_malzemeler`
--

CREATE TABLE `talep_malzemeler` (
  `id` int(11) NOT NULL,
  `talep_id` int(11) NOT NULL,
  `malzeme_id` int(11) DEFAULT NULL,
  `miktar` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `talep_malzemeler`
--

INSERT INTO `talep_malzemeler` (`id`, `talep_id`, `malzeme_id`, `miktar`) VALUES
(1, 2, 1, '1'),
(2, 2, 2, '1'),
(3, 2, 3, '1'),
(4, 2, 4, '1'),
(5, 2, 5, '1'),
(6, 3, 1, '1'),
(7, 3, 2, '1'),
(8, 3, 3, '1'),
(9, 3, 4, '1'),
(10, 3, 5, '1'),
(11, 3, NULL, 'pilav , tavuk , makarona'),
(12, 4, 1, '1'),
(13, 4, 2, '1'),
(14, 4, 3, '1'),
(15, 4, 4, '1'),
(16, 4, 5, '1'),
(17, 5, 1, '1'),
(18, 5, 2, '1'),
(19, 5, 3, '1'),
(20, 5, 4, '1'),
(21, 5, 5, '1'),
(22, 6, 1, '1'),
(23, 6, 2, '1'),
(24, 6, 3, '1'),
(25, 6, 4, '1'),
(26, 7, 1, '1'),
(27, 7, 2, '1'),
(28, 7, 3, '1'),
(29, 7, 4, '1'),
(30, 7, 5, '1'),
(31, 8, 1, '1'),
(32, 8, 2, '1'),
(33, 8, 3, '1'),
(34, 8, 4, '1'),
(35, 8, 5, '1'),
(36, 9, 1, '1'),
(37, 9, 2, '1'),
(38, 9, 3, '1'),
(39, 9, 4, '1'),
(40, 9, 5, '1'),
(41, 9, NULL, 'yorgan');

-- --------------------------------------------------------

--
-- Table structure for table `tedarikciler`
--

CREATE TABLE `tedarikciler` (
  `id` int(11) NOT NULL,
  `ad` varchar(150) NOT NULL,
  `sehir` varchar(100) DEFAULT NULL,
  `latitude` decimal(9,6) DEFAULT NULL,
  `longitude` decimal(9,6) DEFAULT NULL,
  `kapasite` int(11) DEFAULT NULL,
  `yerel_mi` tinyint(1) DEFAULT 1,
  `kayit_tarihi` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tedarikciler`
--

INSERT INTO `tedarikciler` (`id`, `ad`, `sehir`, `latitude`, `longitude`, `kapasite`, `yerel_mi`, `kayit_tarihi`) VALUES
(1, 'Bartın Yerel Depo', 'Bartın', 41.635000, 32.337000, 500, 1, '2025-12-21 20:49:53'),
(2, 'Karabük Bölgesel Depo', 'Karabük', 41.208000, 32.627000, 1000, 0, '2025-12-21 20:49:53'),
(3, 'Ankara Bölgesel Depo', 'Ankara', 39.925000, 32.835000, 1500, 0, '2025-12-21 23:09:31'),
(4, 'İstanbul Bölgesel Depo', 'İstanbul', 41.015000, 28.977000, 2000, 0, '2025-12-21 23:09:31'),
(5, 'İzmir Bölgesel Depo', 'İzmir', 38.423000, 27.142000, 1200, 0, '2025-12-21 23:09:31'),
(6, 'Trabzon Yerel Depo', 'Trabzon', 41.005000, 39.726000, 600, 1, '2025-12-21 23:09:31'),
(7, 'Diyarbakır Yerel Depo', 'Diyarbakır', 37.915000, 40.218000, 600, 1, '2025-12-21 23:09:31');

-- --------------------------------------------------------

--
-- Table structure for table `teslimatlar`
--

CREATE TABLE `teslimatlar` (
  `id` int(11) NOT NULL,
  `talep_id` int(11) NOT NULL,
  `tedarikci_id` int(11) NOT NULL,
  `tasima_modu` varchar(20) DEFAULT 'Kara',
  `rota_aciklama` text DEFAULT NULL,
  `teslimat_tarihi` datetime DEFAULT NULL,
  `durum` varchar(30) DEFAULT 'Planlandi',
  `malzeme_id` int(11) DEFAULT NULL,
  `surucu_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teslimatlar`
--

INSERT INTO `teslimatlar` (`id`, `talep_id`, `tedarikci_id`, `tasima_modu`, `rota_aciklama`, `teslimat_tarihi`, `durum`, `malzeme_id`, `surucu_id`) VALUES
(1, 2, 1, 'Kara', NULL, NULL, 'Planlandi', NULL, NULL),
(2, 2, 2, 'Hava', NULL, NULL, 'Delivered', NULL, NULL),
(3, 2, 2, 'Hava', NULL, NULL, 'Planlandi', NULL, NULL),
(4, 2, 1, 'Kara', NULL, NULL, 'Planlandi', NULL, NULL),
(5, 2, 1, 'Kara', NULL, NULL, 'Planlandi', NULL, NULL),
(6, 3, 1, 'Kara', NULL, NULL, 'Planlandi', NULL, NULL),
(7, 3, 2, 'Hava', NULL, NULL, 'Planlandi', NULL, NULL),
(8, 3, 2, 'Hava', NULL, NULL, 'Planlandi', NULL, NULL),
(9, 3, 1, 'Kara', NULL, NULL, 'Delivered', NULL, NULL),
(10, 3, 1, 'Kara', NULL, NULL, 'Delivered', NULL, NULL),
(11, 4, 3, 'Kara', NULL, NULL, 'Planlandi', 1, NULL),
(12, 4, 4, 'Hava', NULL, NULL, 'Planlandi', 2, NULL),
(13, 4, 5, 'Hava', NULL, NULL, 'Delivered', 3, NULL),
(14, 4, 1, 'Kara', NULL, NULL, 'Planlandi', 4, NULL),
(15, 4, 1, 'Kara', NULL, NULL, 'Delivered', 5, NULL),
(16, 5, 1, 'Kara', NULL, NULL, 'Delivered', 1, NULL),
(17, 5, 4, 'Hava', NULL, NULL, 'Delivered', 2, NULL),
(18, 5, 5, 'Hava', NULL, NULL, 'Delivered', 3, NULL),
(19, 5, 1, 'Kara', NULL, NULL, 'Delivered', 4, NULL),
(20, 5, 1, 'Kara', NULL, NULL, 'Delivered', 5, NULL),
(21, 6, 1, 'Kara', NULL, NULL, 'Planlandi', 1, NULL),
(22, 6, 2, 'Hava', NULL, NULL, 'Planlandi', 2, NULL),
(23, 6, 2, 'Hava', NULL, NULL, 'Planlandi', 3, NULL),
(24, 6, 1, 'Kara', NULL, NULL, 'Planlandi', 4, NULL),
(25, 7, 1, 'Kara', NULL, NULL, 'Delivered', 1, NULL),
(26, 7, 4, 'Hava', NULL, NULL, 'Delivered', 2, NULL),
(27, 7, 5, 'Hava', NULL, NULL, 'Delivered', 3, NULL),
(28, 7, 1, 'Kara', NULL, NULL, 'Delivered', 4, NULL),
(29, 7, 1, 'Kara', NULL, NULL, 'Delivered', 5, NULL),
(30, 9, 4, 'Kara', NULL, NULL, 'Planlandi', NULL, 10),
(31, 8, 4, 'Hava', NULL, NULL, 'Başlatıldı', NULL, 10),
(32, 7, 4, 'Deniz', NULL, NULL, 'Teslim Edildi', NULL, 11);

-- --------------------------------------------------------

--
-- Table structure for table `yol_durumu`
--

CREATE TABLE `yol_durumu` (
  `id` int(11) NOT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `durum` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `durum_loglari`
--
ALTER TABLE `durum_loglari`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `malzemeler`
--
ALTER TABLE `malzemeler`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rotalar`
--
ALTER TABLE `rotalar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teslimat_id` (`teslimat_id`);

--
-- Indexes for table `stoklar`
--
ALTER TABLE `stoklar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tedarikci_id` (`tedarikci_id`),
  ADD KEY `idx_stoklar_malzeme` (`malzeme_id`,`tedarikci_id`);

--
-- Indexes for table `talepler`
--
ALTER TABLE `talepler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kullanici_id` (`kullanici_id`),
  ADD KEY `idx_talepler_konum` (`sehir`,`mahalle`);

--
-- Indexes for table `talep_malzemeler`
--
ALTER TABLE `talep_malzemeler`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talep_id` (`talep_id`),
  ADD KEY `malzeme_id` (`malzeme_id`);

--
-- Indexes for table `tedarikciler`
--
ALTER TABLE `tedarikciler`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teslimatlar`
--
ALTER TABLE `teslimatlar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_talep_malzeme` (`talep_id`,`malzeme_id`),
  ADD KEY `tedarikci_id` (`tedarikci_id`);

--
-- Indexes for table `yol_durumu`
--
ALTER TABLE `yol_durumu`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `durum_loglari`
--
ALTER TABLE `durum_loglari`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `kullanicilar`
--
ALTER TABLE `kullanicilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `malzemeler`
--
ALTER TABLE `malzemeler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rotalar`
--
ALTER TABLE `rotalar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `stoklar`
--
ALTER TABLE `stoklar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `talepler`
--
ALTER TABLE `talepler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `talep_malzemeler`
--
ALTER TABLE `talep_malzemeler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `tedarikciler`
--
ALTER TABLE `tedarikciler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `teslimatlar`
--
ALTER TABLE `teslimatlar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `yol_durumu`
--
ALTER TABLE `yol_durumu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rotalar`
--
ALTER TABLE `rotalar`
  ADD CONSTRAINT `rotalar_ibfk_1` FOREIGN KEY (`teslimat_id`) REFERENCES `teslimatlar` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stoklar`
--
ALTER TABLE `stoklar`
  ADD CONSTRAINT `stoklar_ibfk_1` FOREIGN KEY (`tedarikci_id`) REFERENCES `tedarikciler` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stoklar_ibfk_2` FOREIGN KEY (`malzeme_id`) REFERENCES `malzemeler` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `talepler`
--
ALTER TABLE `talepler`
  ADD CONSTRAINT `talepler_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `talep_malzemeler`
--
ALTER TABLE `talep_malzemeler`
  ADD CONSTRAINT `talep_malzemeler_ibfk_1` FOREIGN KEY (`talep_id`) REFERENCES `talepler` (`id`),
  ADD CONSTRAINT `talep_malzemeler_ibfk_2` FOREIGN KEY (`malzeme_id`) REFERENCES `malzemeler` (`id`);

--
-- Constraints for table `teslimatlar`
--
ALTER TABLE `teslimatlar`
  ADD CONSTRAINT `teslimatlar_ibfk_1` FOREIGN KEY (`talep_id`) REFERENCES `talepler` (`id`),
  ADD CONSTRAINT `teslimatlar_ibfk_2` FOREIGN KEY (`tedarikci_id`) REFERENCES `tedarikciler` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
