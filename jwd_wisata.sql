-- Membuat database
CREATE DATABASE jwd_wisata;

-- Menggunakan database
USE jwd_wisata;

-- Membuat tabel wisata
CREATE TABLE wisata (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_wisata VARCHAR(255) NOT NULL
);

-- Membuat tabel booking
CREATE TABLE booking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pesanan VARCHAR(255) NOT NULL,
    nomor_hp VARCHAR(20) NOT NULL,
    tanggal_pesan DATE NOT NULL,
    waktu_perjalanan INT NOT NULL,
    pelayanan_paket TEXT NOT NULL,
    jumlah_peserta INT NOT NULL,
    harga_paket DECIMAL(10, 2) NOT NULL,
    jumlah_tagihan DECIMAL(10, 2) NOT NULL
);

-- Menambahkan data ke tabel wisata
INSERT INTO wisata (nama_wisata) VALUES
('Magelang Trip Borobudur'),
('Prambanan - Malioboro Jogja Trip'),
('Paket Pantai Parangtritis dan Hotel'),
('Dieng Trip');
