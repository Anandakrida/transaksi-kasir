-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table transaksi.detail_kasir
CREATE TABLE IF NOT EXISTS `detail_kasir` (
  `id_detail` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_transaksi` bigint unsigned NOT NULL,
  `id_produk` bigint unsigned NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(15,2) NOT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `detail_transaksi_fk` (`id_transaksi`),
  KEY `detail_produk_fk` (`id_produk`),
  CONSTRAINT `detail_produk_fk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `detail_transaksi_fk` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table transaksi.detail_kasir: ~49 rows (approximately)
INSERT INTO `detail_kasir` (`id_detail`, `id_transaksi`, `id_produk`, `jumlah`, `harga_satuan`, `diskon`, `subtotal`) VALUES
	(1, 1, 1, 1, 24999000.00, 0.00, 24999000.00),
	(2, 2, 1, 3, 24999000.00, 0.00, 74997000.00),
	(3, 3, 5, 1, 14999000.00, 0.00, 14999000.00),
	(4, 4, 2, 3, 21999000.00, 0.00, 65997000.00),
	(5, 5, 2, 1, 21999000.00, 0.00, 21999000.00),
	(6, 6, 5, 2, 14999000.00, 0.00, 29998000.00),
	(7, 7, 5, 1, 14999000.00, 0.00, 14999000.00),
	(8, 8, 5, 1, 14999000.00, 0.00, 14999000.00),
	(9, 8, 2, 1, 21999000.00, 0.00, 21999000.00),
	(10, 9, 2, 1, 21999000.00, 0.00, 21999000.00),
	(11, 10, 1, 2, 24999000.00, 0.00, 49998000.00),
	(12, 10, 5, 1, 14999000.00, 0.00, 14999000.00),
	(13, 11, 2, 1, 21999000.00, 0.00, 21999000.00),
	(14, 12, 2, 1, 21999000.00, 0.00, 21999000.00),
	(15, 13, 5, 1, 14999000.00, 0.00, 14999000.00),
	(16, 14, 5, 1, 14999000.00, 0.00, 14999000.00),
	(17, 15, 5, 1, 14999000.00, 0.00, 14999000.00),
	(18, 16, 5, 1, 14999000.00, 0.00, 14999000.00),
	(19, 17, 5, 1, 14999000.00, 0.00, 14999000.00),
	(20, 18, 2, 1, 21999000.00, 0.00, 21999000.00),
	(21, 19, 1, 1, 24999000.00, 0.00, 24999000.00),
	(22, 20, 5, 1, 14999000.00, 0.00, 14999000.00),
	(23, 21, 1, 1, 24999000.00, 0.00, 24999000.00),
	(24, 22, 1, 1, 24999000.00, 0.00, 24999000.00),
	(25, 23, 2, 1, 21999000.00, 0.00, 21999000.00),
	(26, 24, 5, 1, 14999000.00, 0.00, 14999000.00),
	(27, 24, 2, 1, 21999000.00, 0.00, 21999000.00),
	(28, 24, 1, 1, 24999000.00, 0.00, 24999000.00),
	(29, 24, 9, 1, 3400000.00, 0.00, 3400000.00),
	(30, 24, 4, 1, 18999000.00, 0.00, 18999000.00),
	(31, 24, 3, 1, 16999000.00, 0.00, 16999000.00),
	(32, 25, 2, 1, 21999000.00, 0.00, 21999000.00),
	(33, 26, 5, 1, 14999000.00, 0.00, 14999000.00),
	(34, 27, 5, 1, 14999000.00, 0.00, 14999000.00),
	(35, 28, 9, 1, 3400000.00, 0.00, 3400000.00),
	(36, 29, 5, 1, 14999000.00, 0.00, 14999000.00),
	(37, 30, 5, 1, 14999000.00, 0.00, 14999000.00),
	(38, 31, 2, 1, 21999000.00, 0.00, 21999000.00),
	(39, 31, 5, 1, 14999000.00, 0.00, 14999000.00),
	(40, 32, 9, 1, 3400000.00, 0.00, 3400000.00),
	(41, 33, 4, 1, 18999000.00, 0.00, 18999000.00),
	(42, 34, 2, 1, 21999000.00, 0.00, 21999000.00),
	(43, 35, 2, 4, 21999000.00, 0.00, 87996000.00),
	(44, 36, 2, 1, 21999000.00, 0.00, 21999000.00),
	(45, 37, 2, 1, 21999000.00, 0.00, 21999000.00),
	(46, 38, 2, 1, 21999000.00, 0.00, 21999000.00),
	(47, 39, 5, 1, 14999000.00, 0.00, 14999000.00),
	(48, 39, 1, 1, 24999000.00, 0.00, 24999000.00),
	(49, 40, 5, 1, 14999000.00, 0.00, 14999000.00),
	(50, 41, 5, 1, 14999000.00, 0.00, 14999000.00),
	(51, 42, 9, 1, 3400000.00, 0.00, 3400000.00),
	(52, 43, 1, 1, 24999000.00, 0.00, 24999000.00);

-- Dumping structure for table transaksi.kasir
CREATE TABLE IF NOT EXISTS `kasir` (
  `id_kasir` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kasir` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_kasir`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table transaksi.kasir: ~2 rows (approximately)
INSERT INTO `kasir` (`id_kasir`, `nama_kasir`, `username`, `password`, `created_at`, `updated_at`) VALUES
	(1, 'Admin Kasir', 'admin', 'admin123', '2026-09-02 04:52:36', '2026-09-02 04:52:36'),
	(2, 'Budi', 'budi', 'budi123', '2026-09-02 04:52:36', '2026-09-02 04:52:36');

-- Dumping structure for table transaksi.produk
CREATE TABLE IF NOT EXISTS `produk` (
  `id_produk` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode_produk` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_produk` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `merk` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'HP Flagship',
  `harga` decimal(15,2) NOT NULL,
  `stok` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'default.png',
  PRIMARY KEY (`id_produk`),
  UNIQUE KEY `kode_produk` (`kode_produk`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table transaksi.produk: ~5 rows (approximately)
INSERT INTO `produk` (`id_produk`, `kode_produk`, `nama_produk`, `merk`, `kategori`, `harga`, `stok`, `created_at`, `updated_at`, `gambar`) VALUES
	(1, 'HP001', 'iPhone 17 Pro Max', 'Apple', 'HP Flagship', 24999000.00, 6, '2026-09-02 04:52:36', '2026-09-04 01:36:36', '1788485796_577.jpg'),
	(2, 'HP002', 'Galaxy S26 Ultra', 'Samsung', 'HP Flagship', 21999000.00, 10, '2026-09-02 04:52:36', '2026-10-01 03:30:27', '1790825427_914.jpg'),
	(3, 'HP003', 'Xiaomi 15 Ultra', 'Xiaomi', 'HP Flagship', 16999000.00, 9, '2026-09-02 04:52:36', '2026-09-04 01:54:54', '1788486894_705.jpg'),
	(4, 'HP004', 'Pixel 10 Pro XL', 'Google', 'HP Flagship', 18999000.00, 8, '2026-09-02 04:52:36', '2026-09-04 01:56:17', '1788486977_470.jpg'),
	(5, 'HP005', 'Find X9 Pro', 'OPPO', 'HP Flagship', 14999000.00, 5, '2026-09-02 04:52:36', '2026-09-04 05:33:11', '1788499991_709.jpg'),
	(9, 'HP006', 'note 15 pro', 'redmi', 'HP Flagship', 3400000.00, 6, '2026-09-02 07:55:42', '2026-09-04 01:57:32', '1788487052_696.jpg');

-- Dumping structure for table transaksi.transaksi
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id_transaksi` bigint unsigned NOT NULL AUTO_INCREMENT,
  `no_transaksi` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kasir` bigint unsigned NOT NULL,
  `nama_pelanggan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Umum',
  `tanggal_transaksi` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_bayar` decimal(15,2) NOT NULL DEFAULT '0.00',
  `uang_bayar` decimal(15,2) NOT NULL DEFAULT '0.00',
  `kembalian` decimal(15,2) NOT NULL DEFAULT '0.00',
  `metode_pembayaran` enum('Cash','QRIS','Transfer','Debit') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Cash',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_transaksi`),
  UNIQUE KEY `no_transaksi` (`no_transaksi`),
  KEY `transaksi_kasir_fk` (`id_kasir`),
  CONSTRAINT `transaksi_kasir_fk` FOREIGN KEY (`id_kasir`) REFERENCES `kasir` (`id_kasir`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table transaksi.transaksi: ~39 rows (approximately)
INSERT INTO `transaksi` (`id_transaksi`, `no_transaksi`, `id_kasir`, `nama_pelanggan`, `tanggal_transaksi`, `subtotal`, `diskon`, `total_bayar`, `uang_bayar`, `kembalian`, `metode_pembayaran`, `created_at`, `updated_at`) VALUES
	(1, 'TRX-20260901-001', 1, 'Apan', '2026-09-01 21:52:36', 24999000.00, 0.00, 24999000.00, 25000000.00, 1000.00, 'Cash', '2026-09-02 04:52:36', '2026-09-02 04:52:36'),
	(2, 'TRX-20260902062112', 1, 'ananda', '2026-09-01 23:21:12', 74997000.00, 0.00, 74997000.00, 75000000.00, 3000.00, 'Cash', '2026-09-02 06:21:12', '2026-09-02 06:21:12'),
	(3, 'TRX-20260902062455', 1, 'ananda', '2026-09-01 23:24:55', 14999000.00, 0.00, 14999000.00, 14999000.00, 0.00, 'Cash', '2026-09-02 06:24:55', '2026-09-02 06:24:55'),
	(4, 'TRX-20260902064845', 1, 'ananda', '2026-09-01 23:48:45', 65997000.00, 0.00, 65997000.00, 65997000.00, 0.00, 'Cash', '2026-09-02 06:48:45', '2026-09-02 06:48:45'),
	(5, 'TRX-20260902064903', 1, 'Umum', '2026-09-01 23:49:03', 21999000.00, 0.00, 21999000.00, 21999000.00, 0.00, 'QRIS', '2026-09-02 06:49:03', '2026-09-02 06:49:03'),
	(6, 'TRX-20260902071306', 1, 'cihuyyyyyyyy', '2026-09-02 00:13:06', 29998000.00, 0.00, 29998000.00, 29998000.00, 0.00, 'Cash', '2026-09-02 07:13:06', '2026-09-02 07:13:06'),
	(7, 'TRX-20260902073602', 1, 'ananda', '2026-09-02 00:36:02', 14999000.00, 0.00, 14999000.00, 14999000.00, 0.00, 'Cash', '2026-09-02 07:36:02', '2026-09-02 07:36:02'),
	(8, 'TRX-20260902074147', 1, 'Umum', '2026-09-02 00:41:47', 36998000.00, 0.00, 36998000.00, 46998000.00, 10000000.00, 'Cash', '2026-09-02 07:41:47', '2026-09-02 07:41:47'),
	(9, 'TRX-20260902074606', 1, 'Umum', '2026-09-02 00:46:06', 21999000.00, 0.00, 21999000.00, 21999000.00, 0.00, 'Cash', '2026-09-02 07:46:06', '2026-09-02 07:46:06'),
	(10, 'TRX-20260902075807', 1, 'ananda', '2026-09-02 00:58:07', 64997000.00, 0.00, 64997000.00, 64997000.00, 0.00, 'Cash', '2026-09-02 07:58:07', '2026-09-02 07:58:07'),
	(11, 'TRX-20260902080002', 1, 'Umum', '2026-09-02 01:00:02', 21999000.00, 0.00, 21999000.00, 21999000.00, 0.00, 'Cash', '2026-09-02 08:00:02', '2026-09-02 08:00:02'),
	(12, 'TRX-20260904015515', 1, 'Umum', '2026-09-03 18:55:15', 21999000.00, 0.00, 21999000.00, 21999000.00, 0.00, 'Cash', '2026-09-04 01:55:15', '2026-09-04 01:55:15'),
	(13, 'TRX-20260904015929', 1, 'Umum', '2026-09-03 18:59:29', 14999000.00, 0.00, 14999000.00, 14999000.00, 0.00, 'Cash', '2026-09-04 01:59:29', '2026-09-04 01:59:29'),
	(14, 'TRX-20260904020437', 1, 'Umum', '2026-09-03 19:04:37', 14999000.00, 0.00, 14999000.00, 14999000.00, 0.00, 'Cash', '2026-09-04 02:04:37', '2026-09-04 02:04:37'),
	(15, 'TRX-20260904020950', 1, 'Umum', '2026-09-03 19:09:50', 14999000.00, 0.00, 14999000.00, 14999000.00, 0.00, 'Cash', '2026-09-04 02:09:50', '2026-09-04 02:09:50'),
	(16, 'TRX-20260904032954', 1, 'Umum', '2026-09-03 20:29:54', 14999000.00, 0.00, 14999000.00, 14999000.00, 0.00, 'Cash', '2026-09-04 03:29:54', '2026-09-04 03:29:54'),
	(17, 'TRX-20260904033032', 1, 'Umum', '2026-09-03 20:30:32', 14999000.00, 0.00, 14999000.00, 14999000.00, 0.00, 'Cash', '2026-09-04 03:30:32', '2026-09-04 03:30:32'),
	(18, 'TRX-20260904035207', 1, 'Umum', '2026-09-03 20:52:07', 21999000.00, 0.00, 21999000.00, 21999000.00, 0.00, 'Cash', '2026-09-04 03:52:07', '2026-09-04 03:52:07'),
	(19, 'TRX-20260904035558', 1, 'Umum', '2026-09-03 20:55:58', 24999000.00, 0.00, 24999000.00, 24999000.00, 0.00, 'Cash', '2026-09-04 03:55:58', '2026-09-04 03:55:58'),
	(20, 'TRX-20260904035714', 1, 'Umum', '2026-09-03 20:57:14', 14999000.00, 0.00, 14999000.00, 14999000.00, 0.00, 'Cash', '2026-09-04 03:57:14', '2026-09-04 03:57:14'),
	(21, 'TRX-20260904035920', 1, 'Umum', '2026-09-03 20:59:20', 24999000.00, 0.00, 24999000.00, 24999000.00, 0.00, 'Cash', '2026-09-04 03:59:20', '2026-09-04 03:59:20'),
	(22, 'TRX-20260904042113', 1, 'Umum', '2026-09-03 21:21:13', 24999000.00, 0.00, 24999000.00, 24999000.00, 0.00, 'Cash', '2026-09-04 04:21:13', '2026-09-04 04:21:13'),
	(23, 'TRX-20260904042141', 1, 'Umum', '2026-09-03 21:21:41', 21999000.00, 0.00, 21999000.00, 25999000.00, 4000000.00, 'Cash', '2026-09-04 04:21:41', '2026-09-04 04:21:41'),
	(24, 'TRX-20260904043707', 1, 'ananda', '2026-09-03 21:37:07', 101395000.00, 0.00, 101395000.00, 101395000.00, 0.00, 'Cash', '2026-09-04 04:37:07', '2026-09-04 04:37:07'),
	(25, 'TRX-20260904054140', 1, 'Umum', '2026-09-03 22:41:40', 21999000.00, 0.00, 21999000.00, 21999000.00, 0.00, 'Cash', '2026-09-04 05:41:40', '2026-09-04 05:41:40'),
	(26, 'TRX-20260904054238', 1, 'ananda', '2026-09-03 22:42:38', 14999000.00, 0.00, 14999000.00, 14999000.00, 0.00, 'Cash', '2026-09-04 05:42:38', '2026-09-04 05:42:38'),
	(27, 'TRX-20260904054344', 1, 'ananda', '2026-09-03 22:43:44', 14999000.00, 0.00, 14999000.00, 14999000.00, 0.00, 'Cash', '2026-09-04 05:43:44', '2026-09-04 05:43:44'),
	(28, 'TRX-20260904054549', 1, 'Umum', '2026-09-03 22:45:49', 3400000.00, 0.00, 3400000.00, 3400000.00, 0.00, 'Cash', '2026-09-04 05:45:49', '2026-09-04 05:45:49'),
	(29, 'TRX-20260904054835', 1, 'ananda', '2026-09-03 22:48:35', 14999000.00, 0.00, 14999000.00, 14999000.00, 0.00, 'Cash', '2026-09-04 05:48:35', '2026-09-04 05:48:35'),
	(30, 'TRX-20260904054953', 1, 'Umum', '2026-09-03 22:49:53', 14999000.00, 0.00, 14999000.00, 14999000.00, 0.00, 'Cash', '2026-09-04 05:49:53', '2026-09-04 05:49:53'),
	(31, 'TRX-20260904055246', 1, 'customer', '2026-09-03 22:52:46', 36998000.00, 0.00, 36998000.00, 36998000.00, 0.00, 'Cash', '2026-09-04 05:52:46', '2026-09-04 05:52:46'),
	(32, 'TRX-20260904055349', 1, 'customer', '2026-09-03 22:53:49', 3400000.00, 0.00, 3400000.00, 3400000.00, 0.00, 'Cash', '2026-09-04 05:53:49', '2026-09-04 05:53:49'),
	(33, 'TRX-20260904055511', 1, 'customer', '2026-09-03 22:55:11', 18999000.00, 0.00, 18999000.00, 18999000.00, 0.00, 'Cash', '2026-09-04 05:55:11', '2026-09-04 05:55:11'),
	(34, 'TRX-20260904060253', 1, 'customer', '2026-09-03 23:02:53', 21999000.00, 0.00, 21999000.00, 21999000.00, 0.00, 'Cash', '2026-09-04 06:02:53', '2026-09-04 06:02:53'),
	(35, 'TRX-20260904060430', 1, 'customer', '2026-09-03 23:04:30', 87996000.00, 0.00, 87996000.00, 87996000.00, 0.00, 'Cash', '2026-09-04 06:04:30', '2026-09-04 06:04:30'),
	(36, 'TRX-20260904060624', 1, 'customer', '2026-09-03 23:06:24', 21999000.00, 0.00, 21999000.00, 21999000.00, 0.00, 'Cash', '2026-09-04 06:06:24', '2026-09-04 06:06:24'),
	(37, 'TRX-20260904060708', 1, 'customer', '2026-09-03 23:07:08', 21999000.00, 0.00, 21999000.00, 21999000.00, 0.00, 'QRIS', '2026-09-04 06:07:08', '2026-09-04 06:07:08'),
	(38, 'TRX-20260904060838', 1, 'customer', '2026-09-03 23:08:38', 21999000.00, 0.00, 21999000.00, 21999000.00, 0.00, 'QRIS', '2026-09-04 06:08:38', '2026-09-04 06:08:38'),
	(39, 'TRX-20260904061148', 1, 'customer', '2026-09-03 23:11:48', 39998000.00, 0.00, 39998000.00, 39998000.00, 0.00, 'Cash', '2026-09-04 06:11:48', '2026-09-04 06:11:48'),
	(40, 'TRX-20260904061457', 1, 'customer', '2026-09-03 23:14:57', 14999000.00, 0.00, 14999000.00, 15000000.00, 1000.00, 'Cash', '2026-09-04 06:14:57', '2026-09-04 06:14:57'),
	(41, 'TRX-20260904063336', 1, 'customer', '2026-09-03 23:33:36', 14999000.00, 0.00, 14999000.00, 14999001.00, 1.00, 'Cash', '2026-09-04 06:33:36', '2026-09-04 06:33:36'),
	(42, 'TRX-20260904063626', 1, 'customer', '2026-09-03 23:36:26', 3400000.00, 0.00, 3400000.00, 4400000.00, 1000000.00, 'Cash', '2026-09-04 06:36:26', '2026-09-04 06:36:26'),
	(43, 'TRX-20261001032401', 1, 'kevin', '2026-09-30 20:24:01', 24999000.00, 0.00, 24999000.00, 24999000.00, 0.00, 'Cash', '2026-10-01 03:24:01', '2026-10-01 03:24:01');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
