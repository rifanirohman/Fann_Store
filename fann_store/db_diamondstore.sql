-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Des 2025 pada 03.36
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
-- Database: `db_diamondstore`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pegawai','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_admin`
--

INSERT INTO `tb_admin` (`id_admin`, `username`, `nama`, `password`, `role`) VALUES
(1, 'admin', 'admin', '$2y$10$4Y2ZY/6eENUTWyAqfitH6eJmFFMS5Jn3muj8JVCG.mviTRxsFCiWa', 'admin'),
(2, 'pegawai', 'pegawai', '$2y$10$4Y2ZY/6eENUTWyAqfitH6eJmFFMS5Jn3muj8JVCG.mviTRxsFCiWa', 'pegawai');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_keuangan`
--

CREATE TABLE `tb_keuangan` (
  `id_keuangan` int(255) NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` enum('pemasukan','pengeluaran','','') NOT NULL,
  `keterangan` varchar(120) NOT NULL,
  `jumlah` int(255) NOT NULL,
  `nominal` int(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_keuangan`
--

INSERT INTO `tb_keuangan` (`id_keuangan`, `tanggal`, `jenis`, `keterangan`, `jumlah`, `nominal`) VALUES
(3, '2025-11-23', 'pemasukan', 'Penjualan WEEKLY DIAMOND PASS', 0, 27000),
(4, '2025-11-23', 'pengeluaran', 'Restock produk ID 1. ', 0, 25650),
(5, '2025-11-24', 'pemasukan', 'Penjualan WEEKLY DIAMOND PASS', 0, 54000),
(6, '2025-11-24', 'pengeluaran', 'Restock produk ID 2. ', 0, 33250),
(7, '2025-11-24', 'pemasukan', 'Penjualan WEEKLY DIAMOND PASS', 0, 27000),
(8, '2025-11-24', 'pengeluaran', 'Restock produk ID 1. restock wdp tgl 24 nov', 0, 25650),
(9, '2025-11-24', 'pemasukan', 'Penjualan WEEKLY DIAMOND PASS', 0, 27000),
(10, '2025-11-24', 'pemasukan', 'Penjualan WEEKLY DIAMOND PASS', 0, 27000),
(11, '2025-11-24', 'pemasukan', 'Penjualan STARLIGHT', 0, 35000),
(12, '2025-12-07', 'pemasukan', 'Penjualan WEEKLY DIAMOND PASS', 0, 27000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_pelanggan`
--

CREATE TABLE `tb_pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(60) NOT NULL,
  `no_hp` int(20) NOT NULL,
  `password` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_produk`
--

CREATE TABLE `tb_produk` (
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(50) NOT NULL,
  `harga` int(60) NOT NULL,
  `stok` int(120) NOT NULL,
  `img` varchar(255) NOT NULL,
  `kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_produk`
--

INSERT INTO `tb_produk` (`id_produk`, `nama_produk`, `harga`, `stok`, `img`, `kategori`) VALUES
(1, 'WEEKLY DIAMOND PASS', 27000, 62, 'gemini.jpg', 'wdp'),
(2, 'STARLIGHT', 35000, 100, 'starlight.jpg', 'starlight'),
(3, '5 DIAMONDS', 1500, 47, 'diamond.jpg', 'dm_kecil'),
(4, '29 DIAMONDS', 8000, 99, 'diamond.jpg', 'dm_kecil'),
(5, '59 DIAMONDS', 15900, 100, 'diamond.jpg', 'dm_kecil'),
(6, '85 DIAMONDS', 23500, 100, 'diamond.jpg', 'dm_kecil'),
(7, '100 DIAMONDS', 35000, 100, 'diamond.jpg', 'dm_kecil'),
(8, '255 DIAMONDS', 75800, 90, 'diamond.jpg', 'dm_kecil'),
(9, '645 DIAMONDS', 175000, 100, 'diamond2.jpg', 'dm_besar'),
(10, '935 DIAMONDS', 250500, 90, 'diamond2.jpg', 'dm_besar'),
(11, '1130 DIAMONDS', 301000, 80, 'diamond2.jpg', 'dm_besar'),
(12, '2569 DIAMONDS', 656700, 100, 'diamond2.jpg', 'dm_besar'),
(13, '3456 DIAMONDS', 889100, 50, 'diamond2.jpg', 'dm_besar'),
(14, '5370 DIAMONDS', 1356700, 100, 'diamond2.jpg', 'dm_besar');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id_transaksi` int(20) NOT NULL,
  `id_pelanggan` int(25) NOT NULL,
  `id_produk` int(60) NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah` int(50) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','selesai','dibatalkan','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id_transaksi`, `id_pelanggan`, `id_produk`, `tanggal`, `jumlah`, `total`, `status`) VALUES
(10, 1, 1, '2025-11-17', 1, 1500.00, ''),
(11, 1, 10, '2025-11-17', 1, 250500.00, ''),
(12, 1, 1, '2025-11-17', 3, 81000.00, ''),
(13, 1, 1, '2025-11-17', 1, 27000.00, ''),
(14, 1, 1, '2025-11-17', 1, 27000.00, ''),
(15, 1, 1, '2025-11-17', 1, 27000.00, ''),
(16, 1, 1, '2025-11-18', 1, 27000.00, ''),
(17, 1, 1, '2025-11-18', 1, 27000.00, ''),
(18, 1, 1, '2025-11-19', 1, 27000.00, ''),
(19, 1, 3, '2025-11-19', 1, 1500.00, ''),
(20, 1, 1, '2025-11-19', 1, 27000.00, ''),
(21, 1, 1, '2025-11-23', 1, 27000.00, ''),
(22, 1, 1, '2025-11-24', 2, 54000.00, ''),
(23, 1, 1, '2025-11-24', 1, 27000.00, ''),
(24, 1, 1, '2025-11-24', 1, 27000.00, ''),
(25, 1, 1, '2025-11-24', 1, 27000.00, ''),
(26, 1, 2, '2025-11-24', 1, 35000.00, ''),
(27, 1, 1, '2025-12-07', 1, 27000.00, 'pending');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indeks untuk tabel `tb_keuangan`
--
ALTER TABLE `tb_keuangan`
  ADD PRIMARY KEY (`id_keuangan`);

--
-- Indeks untuk tabel `tb_pelanggan`
--
ALTER TABLE `tb_pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indeks untuk tabel `tb_produk`
--
ALTER TABLE `tb_produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indeks untuk tabel `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tb_keuangan`
--
ALTER TABLE `tb_keuangan`
  MODIFY `id_keuangan` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `tb_pelanggan`
--
ALTER TABLE `tb_pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_produk`
--
ALTER TABLE `tb_produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id_transaksi` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
