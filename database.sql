
DROP DATABASE IF EXISTS votera2526;
CREATE DATABASE votera2526;
USE votera2526;

CREATE TABLE kategori (
    id_rekod_kategori INT AUTO_INCREMENT PRIMARY KEY,
    Id_Kategori VARCHAR(10) NOT NULL UNIQUE,
    Kategori VARCHAR(100) NOT NULL
);

CREATE TABLE pelajar (
    Id_Pelajar VARCHAR(20) PRIMARY KEY,
    Kata_Laluan VARCHAR(255) NOT NULL,
    Nama_Pelajar VARCHAR(150) NOT NULL,
    Status_Pelajar VARCHAR(30) DEFAULT 'belum',
    Gambar_Pelajar VARCHAR(100) DEFAULT 'default.png'
);

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(30) DEFAULT 'superadmin'
);

CREATE TABLE calon (
    id_calon INT AUTO_INCREMENT PRIMARY KEY,
    id_kategori VARCHAR(10),
    nama_calon VARCHAR(150),
    kelas_calon VARCHAR(100),
    gambar_calon VARCHAR(100) DEFAULT 'default.png',
    undi INT DEFAULT 0
);

CREATE TABLE undi_kategori (
    id_undi INT AUTO_INCREMENT PRIMARY KEY,
    Id_Pelajar VARCHAR(20),
    Id_Kategori VARCHAR(10),
    id_calon INT,
    Tarikh_Undi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO kategori (Id_Kategori, Kategori) VALUES
('KAT01','Ketua Pelajar'),
('KAT02','Penolong Ketua Pelajar');

-- Admin login
-- username: admin
-- password: admin123
INSERT INTO admin (nama, username, password, role) VALUES
('Administrator','admin','$2y$10$wH8Yw9Yd6K2X2xYDs2fzQeF6Y8WcN6iYG3PaY5E9OQj1YxDCEV4QK','superadmin');
