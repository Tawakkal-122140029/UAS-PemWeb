CREATE DATABASE chillbarbershop;
USE chillbarbershop;

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_pelanggan VARCHAR(255) NOT NULL,
    capster VARCHAR(255) NOT NULL,
    lokasi VARCHAR(255) NOT NULL,
    waktu_reservasi DATETIME NOT NULL
);

CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_service VARCHAR(255) NOT NULL,
    icon_path VARCHAR(255) NOT NULL
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fname VARCHAR(50) NOT NULL,
    lname VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO services (nama_service, icon_path)
VALUES 
('Haircut', '../img/haircut.png'),
('Hairstyle', '../img/hairstyle.png'),
('Hairwash', '../img/hairwash.png');
