-- Database: DB_UAS_PBO_TRPL1B_Achmal_Maulana
CREATE DATABASE IF NOT EXISTS `DB_UAS_PBO_TRPL1B_Achmal_Maulana`;
USE `DB_UAS_PBO_TRPL1B_Achmal_Maulana`;

-- Table structure for table `karyawan`
CREATE TABLE IF NOT EXISTS `karyawan` (
  `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
  `nip` VARCHAR(20) NOT NULL UNIQUE,
  `nama` VARCHAR(100) NOT NULL,
  `jabatan` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) UNIQUE,
  `no_telp` VARCHAR(15),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table `karyawan`
INSERT INTO `karyawan` (`nip`, `nama`, `jabatan`, `email`, `no_telp`) VALUES
('199208122018011001', 'Achmal Maulana', 'Software Engineer', 'achmal.maulana@email.com', '081234567890'),
('199403152019032002', 'Budi Santoso', 'Project Manager', 'budi.santoso@email.com', '081298765432'),
('199607212020121003', 'Siti Aminah', 'System Analyst', 'siti.aminah@email.com', '081345678901');
