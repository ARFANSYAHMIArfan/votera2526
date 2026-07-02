-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql111.infinityfree.com
-- Generation Time: May 10, 2026 at 11:27 PM
-- Server version: 11.4.10-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+08:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_41597545_votera2526`
--
CREATE DATABASE IF NOT EXISTS `if0_41597545_votera2526` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `if0_41597545_votera2526`;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(30) DEFAULT 'superadmin',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `username`, `password`, `role`) VALUES
(1, 'Votera2526 Admin', 'mzadmin', '$2y$10$QGKQehBlw5ygjnO6n.RWKu5r47PHYMLHWgjyVjVypTl0i.PWVYvUq', 'superadmin'),
(2, 'Votera2526_superadmin', 'votera@admin', '$2y$10$Ze4Je8ZE/jBUsevORq.TXOxtknRKHIcefQasTf8HvnTAAYN0osAku', 'superadmin');

-- --------------------------------------------------------

--
-- Table structure for table `calon`
--

DROP TABLE IF EXISTS `calon`;
CREATE TABLE IF NOT EXISTS `calon` (
  `id_calon` int(11) NOT NULL AUTO_INCREMENT,
  `id_kategori` varchar(10) DEFAULT NULL,
  `nama_calon` varchar(150) DEFAULT NULL,
  `kelas_calon` varchar(100) DEFAULT NULL,
  `gambar_calon` varchar(100) DEFAULT 'default.png',
  `undi` int(11) DEFAULT 0,
  PRIMARY KEY (`id_calon`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `calon`
--

INSERT INTO `calon` (`id_calon`, `id_kategori`, `nama_calon`, `kelas_calon`, `gambar_calon`, `undi`) VALUES
(3, 'KB', 'AMAR PUTRA BIN AGUS PUTRA', '5 ALPHA', '1774464118_T5A-AMAR_PUTRA.png', 0),
(4, 'KC', 'AMAR PUTRA BIN AGUS PUTRA', '5 ALPHA', '1774464132_T5A-AMAR_PUTRA.png', 0),
(5, 'KI', 'AMAR PUTRA BIN AGUS PUTRA', '5 ALPHA', 'default.png', 0),
(6, 'KP', 'AMAR PUTRA BIN AGUS PUTRA', '5 ALPHA', '1774464176_T5A-AMAR_PUTRA.png', 0),
(7, 'KS', 'AMAR PUTRA BIN AGUS PUTRA', '5 ALPHA', '1774464191_T5A-AMAR_PUTRA.png', 0),
(9, 'KB', 'MUHAMMAD EMIR RAYQAL BIN MOHD FAIZAL', '5 BETA', '1775447094_091110100747.jpg', 0),
(10, 'KC', 'MUHAMMAD EMIR RAYQAL BIN MOHD FAIZAL', '5 BETA', '1775447104_091110100747.jpg', 0),
(11, 'KI', 'MUHAMMAD EMIR RAYQAL BIN MOHD FAIZAL', '5 BETA', '1775447112_091110100747.jpg', 0),
(13, 'KP', 'MUHAMMAD EMIR RAYQAL BIN MOHD FAIZAL', '5 BETA', '1775447137_091110100747.jpg', 0),
(14, 'KS', 'MUHAMMAD EMIR RAYQAL BIN MOHD FAIZAL', '5 BETA', '1775447144_091110100747.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

DROP TABLE IF EXISTS `kategori`;
CREATE TABLE IF NOT EXISTS `kategori` (
  `id_rekod_kategori` int(11) NOT NULL AUTO_INCREMENT,
  `Id_Kategori` varchar(10) NOT NULL,
  `Kategori` varchar(100) NOT NULL,
  PRIMARY KEY (`id_rekod_kategori`),
  UNIQUE KEY `Id_Kategori` (`Id_Kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_rekod_kategori`, `Id_Kategori`, `Kategori`) VALUES
(3, 'KC', 'KELAS CEMERLANG'),
(5, 'KS', 'KELAS SPORTING'),
(6, 'KI', 'KELAS ISLAMIK'),
(7, 'KB', 'KELAS BERSIH'),
(8, 'KP', 'KELAS PINTAR');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

DROP TABLE IF EXISTS `kelas`;
CREATE TABLE IF NOT EXISTS `kelas` (
  `Id_Kelas` varchar(10) NOT NULL,
  `Kelas` varchar(50) NOT NULL,
  `tot_undi_kelas` int(11) NOT NULL DEFAULT 0,
  `Undi_Kelas` varchar(50) DEFAULT NULL,
  `Gambar_Kelas` varchar(100) DEFAULT 'default.png',
  PRIMARY KEY (`Id_Kelas`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`Id_Kelas`, `Kelas`, `tot_undi_kelas`, `Undi_Kelas`, `Gambar_Kelas`) VALUES
('T5A', '5 ALPHA', 0, NULL, '1778336853_IMG_20260318_204305_085.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `pelajar`
--

DROP TABLE IF EXISTS `pelajar`;
CREATE TABLE IF NOT EXISTS `pelajar` (
  `Id_Pelajar` varchar(20) NOT NULL,
  `Kata_Laluan` varchar(255) NOT NULL,
  `Nama_Pelajar` varchar(150) NOT NULL,
  `Status_Pelajar` varchar(30) DEFAULT 'belum',
  `Gambar_Pelajar` varchar(100) DEFAULT 'default.png',
  PRIMARY KEY (`Id_Pelajar`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelajar`
--

INSERT INTO `pelajar` (`Id_Pelajar`, `Kata_Laluan`, `Nama_Pelajar`, `Status_Pelajar`, `Gambar_Pelajar`) VALUES
('mz001', '$2y$10$SSBTjd3X1QOPDiGNzna0ouNe8s12Cij2jWXh7cy4C2nyCkNGovSLm', 'MUHAMMAD ARFAN SYAHMI BIN MUHAMMMAD AZMI', 'belum', 'T5A-ARFAN_SYAHMI.png'),
('mz002', '$2y$10$flvmGKkWxi0mei1fmMAe8el2/p78GhibdPGQuJAp.DcoSGjcYJcKe', 'MUHAMMAD IMAN NUR HAFIZ BIN MUSTAPA', 'belum', 'default.png'),
('mz003', '$2y$10$qNWGWvuAWA27X9ZXyZeOH.Iai4CIfQaYEh11pmtiDtzJcrlgpVL3S', 'MUHAMMAD AZRUL HELMI BIN AYOB', 'belum', 'default.png'),
('mz004', '$2y$10$q0kzNrHwCsTTn4GzrBQK8uz0AKUynrKGsD/EveNNmeTpzIHbbDr9q', 'ADZIN DZARIEF BIN AZHAIRIL', 'belum', '1775447512_090711040247.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `undi_kategori`
--

DROP TABLE IF EXISTS `undi_kategori`;
CREATE TABLE IF NOT EXISTS `undi_kategori` (
  `id_undi` int(11) NOT NULL AUTO_INCREMENT,
  `Id_Pelajar` varchar(20) DEFAULT NULL,
  `Id_Kategori` varchar(10) DEFAULT NULL,
  `id_calon` int(11) DEFAULT NULL,
  `Tarikh_Undi` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_undi`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
