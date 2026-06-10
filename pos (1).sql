-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Jun 2026 pada 18.38
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `Oid` int(11) NOT NULL,
  `Category` varchar(36) DEFAULT NULL,
  `Fitur` varchar(36) DEFAULT NULL,
  `IsPos` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`Oid`, `Category`, `Fitur`, `IsPos`) VALUES
(1, 'Produk & Menu', 'Menu & Item', 1),
(2, 'Produk & Menu', 'Produk & Kategori', 1),
(3, 'Transaksi & Order', 'Table Management', 2),
(4, 'Transaksi & Order', 'Kitchen Display System', 2),
(5, 'Transaksi & Order', 'Self-Order QR Code', 2),
(6, 'Transaksi & Order', 'Multi-Payment', 1),
(7, 'Transaksi & Order', 'Barcode Scanning', 1),
(8, 'Transaksi & Order', 'Refund / Return / Exchange', 1),
(9, 'Loyalty & Pelanggan', 'Loyalty Program', 2),
(10, 'Loyalty & Pelanggan', 'Customer Data & Loyalty', 2),
(11, 'Inventori & Stok', 'Bahan Baku & HPP', 2),
(12, 'Inventori & Stok', 'Update Stok Real-Time', 1),
(13, 'Inventori & Stok', 'Supplier & Purchase Order', 1),
(14, 'Laporan & Analitik', 'Penjualan & Menu Laris', 1),
(15, 'Laporan & Analitik', 'Laporan Penjualan', 1),
(16, 'Integrasi & Multi-Outlet', 'Integrasi Aplikasi', 1),
(17, 'Integrasi & Multi-Outlet', 'Sinkronisasi Outlet', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `product`
--

CREATE TABLE `product` (
  `Oid` int(36) NOT NULL,
  `Code` varchar(36) NOT NULL,
  `Name` varchar(36) NOT NULL,
  `Type` varchar(36) NOT NULL,
  `Price` decimal(10,0) NOT NULL,
  `IsStock` smallint(1) NOT NULL,
  `IsActive` smallint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `product`
--

INSERT INTO `product` (`Oid`, `Code`, `Name`, `Type`, `Price`, `IsStock`, `IsActive`) VALUES
(10, 'BRG001', 'Indomie Goreng', 'Food', 3000, 1, 1),
(11, 'BRG002', 'Indomie Soto', 'Food', 3000, 1, 1),
(12, 'BRG003', 'Aqua 600ml', 'Beverage', 3000, 1, 1),
(13, 'BRG004', 'Teh Botol Sosro', 'Beverage', 4000, 1, 1),
(14, 'BRG005', 'Ultra Milk 1L', 'Beverage', 19000, 1, 1),
(15, 'BRG006', 'Gula Pasir 1kg', 'Grocery', 15000, 1, 1),
(16, 'BRG007', 'Beras Ramos 5kg', 'Grocery', 70000, 1, 1),
(17, 'BRG008', 'Minyak Goreng 1L', 'Grocery', 17000, 1, 1),
(18, 'BRG009', 'Telur Ayam 1kg', 'Fresh', 28000, 1, 1),
(19, 'BRG010', 'Roti Tawar', 'Food', 12000, 1, 1),
(20, 'BRG011', 'Sabun Lifebuoy', 'Household', 5000, 1, 1),
(21, 'BRG012', 'Shampoo Clear', 'Household', 22000, 1, 1),
(22, 'BRG013', 'Pasta Gigi Pepsodent', 'Household', 8000, 1, 1),
(23, 'BRG014', 'Detergen Rinso', 'Household', 15000, 1, 1),
(24, 'BRG015', 'Sikat Gigi Oral-B', 'Household', 10000, 1, 1),
(25, 'BRG016', 'Kopi Kapal Api', 'Beverage', 12000, 1, 1),
(26, 'BRG017', 'Good Day Coffee', 'Beverage', 15000, 1, 1),
(27, 'BRG018', 'Sprite 1.5L', 'Beverage', 10000, 1, 1),
(28, 'BRG019', 'Coca Cola 1.5L', 'Beverage', 10000, 1, 1),
(29, 'BRG020', 'Fanta 1.5L', 'Beverage', 10000, 1, 1),
(30, 'BRG021', 'Biskuit Roma', 'Food', 9000, 1, 1),
(31, 'BRG022', 'Oreo', 'Food', 8000, 1, 1),
(32, 'BRG023', 'Chitato', 'Food', 11000, 1, 1),
(33, 'BRG024', 'Qtela Singkong', 'Food', 9000, 1, 1),
(34, 'BRG025', 'SilverQueen', 'Food', 15000, 1, 1),
(35, 'BRG026', 'Tissue Paseo', 'Household', 12000, 1, 1),
(36, 'BRG027', 'Tissue Nice', 'Household', 10000, 1, 1),
(37, 'BRG028', 'Air Galon Aqua', 'Beverage', 20000, 1, 1),
(38, 'BRG029', 'Gas LPG 3kg', 'Utility', 22000, 1, 1),
(39, 'BRG030', 'Air Mineral Le Minerale', 'Beverage', 3000, 1, 1),
(40, 'BRG031', 'Susu Dancow', 'Beverage', 25000, 1, 1),
(41, 'BRG032', 'Susu Milo', 'Beverage', 20000, 1, 1),
(42, 'BRG033', 'Sarden ABC', 'Food', 12000, 1, 1),
(43, 'BRG034', 'Kecap Bango', 'Grocery', 15000, 1, 1),
(44, 'BRG035', 'Saus Sambal ABC', 'Grocery', 12000, 1, 1),
(45, 'BRG036', 'Mie Sedaap Goreng', 'Food', 3000, 1, 1),
(46, 'BRG037', 'Mie Sedaap Kari', 'Food', 3000, 1, 1),
(47, 'BRG038', 'Air Cleo 600ml', 'Beverage', 2500, 1, 1),
(48, 'BRG039', 'Teh Pucuk', 'Beverage', 4000, 1, 1),
(49, 'BRG040', 'Floridina', 'Beverage', 5000, 1, 1),
(50, 'BRG041', 'Sabun Dettol', 'Household', 7000, 1, 1),
(51, 'BRG042', 'Shampoo Sunsilk', 'Household', 20000, 1, 1),
(52, 'BRG043', 'Conditioner Pantene', 'Household', 22000, 1, 1),
(53, 'BRG044', 'Minyak Kayu Putih', 'Health', 15000, 1, 1),
(54, 'BRG045', 'Masker Medis', 'Health', 10000, 1, 1),
(55, 'BRG046', 'Lampu LED', 'Utility', 25000, 1, 1),
(56, 'BRG047', 'Baterai ABC', 'Utility', 8000, 1, 1),
(57, 'BRG048', 'Air Zamzam Botol', 'Beverage', 30000, 1, 1),
(58, 'BRG049', 'Kurma 500gr', 'Food', 35000, 1, 1),
(59, 'BRG050', 'Madu Asli', 'Health', 45000, 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `role`
--

CREATE TABLE `role` (
  `Oid` int(11) NOT NULL,
  `Code` varchar(36) NOT NULL,
  `Name` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `role`
--

INSERT INTO `role` (`Oid`, `Code`, `Name`) VALUES
(1, 'SA', 'SuperAdmin'),
(2, 'AD', 'Admin');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `Oid` int(36) NOT NULL,
  `Code` varchar(36) NOT NULL,
  `Name` varchar(36) NOT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `IsRole` smallint(1) NOT NULL,
  `IsActive` smallint(1) NOT NULL,
  `IsPos` smallint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`Oid`, `Code`, `Name`, `Password`, `IsRole`, `IsActive`, `IsPos`) VALUES
(1, 'OSC', 'Oscar', '', 1, 1, 1),
(2, 'BS', 'Brema Sitepu', '', 2, 1, 1),
(3, 'VAL', 'Valen', '', 1, 1, 1),
(5, 'ADM01', 'admin', '$2y$12$LrM9oXCvgFfM8ScI76tgleX4ice07bqPv7GPaHVuDUS7hHYtMvTeK', 1, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`Oid`);

--
-- Indeks untuk tabel `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`Oid`);

--
-- Indeks untuk tabel `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`Oid`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`Oid`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `Oid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `product`
--
ALTER TABLE `product`
  MODIFY `Oid` int(36) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT untuk tabel `role`
--
ALTER TABLE `role`
  MODIFY `Oid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `Oid` int(36) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
