-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 23, 2026 at 01:26 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_uas_pbo_trpl1b_achmal_maulana`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_karyawan`
--

CREATE TABLE `tabel_karyawan` (
  `id_karyawan` int NOT NULL,
  `nama_karyawan` varchar(100) NOT NULL,
  `departemen` varchar(100) NOT NULL,
  `hari_kerja_masuk` int NOT NULL,
  `gaji_dasar_per_hari` decimal(12,2) NOT NULL,
  `jenis_karyawan` enum('Kontrak','Tetap','Magang') NOT NULL,
  `durasi_kontrak_bulan` int DEFAULT NULL,
  `agensi_penyalur` varchar(100) DEFAULT NULL,
  `tunjangan_kesehatan` decimal(12,2) DEFAULT NULL,
  `opsi_saham_id` varchar(50) DEFAULT NULL,
  `uang_saku_bulanan` decimal(12,2) DEFAULT NULL,
  `sertifikat_kampus_merdeka` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_karyawan`
--

INSERT INTO `tabel_karyawan` (`id_karyawan`, `nama_karyawan`, `departemen`, `hari_kerja_masuk`, `gaji_dasar_per_hari`, `jenis_karyawan`, `durasi_kontrak_bulan`, `agensi_penyalur`, `tunjangan_kesehatan`, `opsi_saham_id`, `uang_saku_bulanan`, `sertifikat_kampus_merdeka`) VALUES
(1, 'Achmal Maulana', 'IT Support', 22, 250000.00, 'Kontrak', 12, 'PT Outsource Nusantara', NULL, NULL, NULL, NULL),
(2, 'Rizqi Ramadhan', 'Programmer', 22, 300000.00, 'Kontrak', 24, 'PT Digital Talent', NULL, NULL, NULL, NULL),
(3, 'Fadhel Aqila', 'UI UX Designer', 20, 275000.00, 'Kontrak', 18, 'PT Kreatif Media', NULL, NULL, NULL, NULL),
(4, 'Adit Kurniadi', 'System Analyst', 22, 320000.00, 'Kontrak', 12, 'PT Solusi Teknologi', NULL, NULL, NULL, NULL),
(5, 'Bagas Pratama', 'Database Admin', 21, 290000.00, 'Kontrak', 24, 'PT Data Indonesia', NULL, NULL, NULL, NULL),
(6, 'Naufal Hidayat', 'QA Engineer', 20, 260000.00, 'Kontrak', 12, 'PT Quality Tech', NULL, NULL, NULL, NULL),
(7, 'Dimas Saputra', 'Network Engineer', 22, 310000.00, 'Kontrak', 18, 'PT Infrastruktur Digital', NULL, NULL, NULL, NULL),
(8, 'Budi Santoso', 'Manager IT', 22, 500000.00, 'Tetap', NULL, NULL, 1500000.00, 'OPS001', NULL, NULL),
(9, 'Andi Wijaya', 'Senior Programmer', 22, 450000.00, 'Tetap', NULL, NULL, 1200000.00, 'OPS002', NULL, NULL),
(10, 'Rina Maharani', 'HRD', 22, 350000.00, 'Tetap', NULL, NULL, 1000000.00, 'OPS003', NULL, NULL),
(11, 'Dewi Lestari', 'Finance', 22, 380000.00, 'Tetap', NULL, NULL, 900000.00, 'OPS004', NULL, NULL),
(12, 'Satria Nugraha', 'Project Manager', 22, 550000.00, 'Tetap', NULL, NULL, 2000000.00, 'OPS005', NULL, NULL),
(13, 'Yusuf Kurniawan', 'DevOps Engineer', 22, 470000.00, 'Tetap', NULL, NULL, 1300000.00, 'OPS006', NULL, NULL),
(14, 'Rahma Putri', 'Business Analyst', 22, 400000.00, 'Tetap', NULL, NULL, 1100000.00, 'OPS007', NULL, NULL),
(15, 'Alfi Syahputra', 'Frontend Developer', 20, 100000.00, 'Magang', NULL, NULL, NULL, NULL, 1500000.00, 'Ada'),
(16, 'Nanda Putri', 'Backend Developer', 20, 100000.00, 'Magang', NULL, NULL, NULL, NULL, 1500000.00, 'Ada'),
(17, 'Fikri Akbar', 'UI UX Intern', 20, 100000.00, 'Magang', NULL, NULL, NULL, NULL, 1200000.00, 'Ada'),
(18, 'Salsa Nur Aini', 'Data Analyst Intern', 20, 100000.00, 'Magang', NULL, NULL, NULL, NULL, 1300000.00, 'Ada'),
(19, 'Kevin Pratama', 'IT Support Intern', 20, 100000.00, 'Magang', NULL, NULL, NULL, NULL, 1000000.00, 'Ada'),
(20, 'Nabila Zahra', 'QA Intern', 20, 100000.00, 'Magang', NULL, NULL, NULL, NULL, 1250000.00, 'Ada');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_karyawan`
--
ALTER TABLE `tabel_karyawan`
  ADD PRIMARY KEY (`id_karyawan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tabel_karyawan`
--
ALTER TABLE `tabel_karyawan`
  MODIFY `id_karyawan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
