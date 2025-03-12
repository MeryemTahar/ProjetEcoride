-- Script de création de la base de données pour le projet Ecoride

CREATE DATABASE IF NOT EXISTS ecoride;
USE ecoride;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    role ENUM('passenger', 'driver', 'admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des véhicules
CREATE TABLE IF NOT EXISTS vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    brand VARCHAR(255) NOT NULL,
    model VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    type ENUM('thermique', 'électrique') NOT NULL,
    seats INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des trajets
CREATE TABLE IF NOT EXISTS trips (
    id INT AUTO_INCREMENT PRIMARY KEY,
    driver_id INT NOT NULL,
    departure VARCHAR(255) NOT NULL,
    arrival VARCHAR(255) NOT NULL,
    departure_time DATETIME NOT NULL,
    available_seats INT NOT NULL CHECK (available_seats > 0),
    price DECIMAL(10,2) NOT NULL,
    vehicle_id INT DEFAULT NULL,  -- Peut être NULL si pas encore enregistré
    preferences VARCHAR(255),
    co2_savings DECIMAL(10,2) NOT NULL DEFAULT 0.0,  -- Obligatoire avec valeur par défaut
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (driver_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE SET NULL
);

-- Table des réservations
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trip_id INT NOT NULL,
    passenger_id INT NOT NULL,
    seats_booked INT NOT NULL CHECK (seats_booked > 0),  -- Vérification du nombre de places réservées
    booking_status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (trip_id) REFERENCES trips(id) ON DELETE CASCADE,
    FOREIGN KEY (passenger_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des paiements
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    user_id INT NOT NULL,  -- Ajout de l'utilisateur qui a payé
    amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des avis
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trip_id INT NOT NULL,
    reviewer_id INT NOT NULL,
    reviewed_user_id INT NOT NULL,  -- Permet de noter le conducteur ou le passager
    rating INT CHECK (rating BETWEEN 1 AND 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (trip_id) REFERENCES trips(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insertion de données de test
INSERT INTO users (firstname, lastname, email, password, phone, role) VALUES
('Alice', 'Durand', 'alice@example.com', '$2y$10$hashedpassword', '0612345678', 'passenger'),
('Bob', 'Martin', 'bob@example.com', '$2y$10$hashedpassword', '0698765432', 'driver');

INSERT INTO vehicles (user_id, brand, model, year, type, seats) VALUES
(2, 'Tesla', 'Model 3', 2022, 'électrique', 5),
(2, 'Renault', 'Clio', 2020, 'thermique', 4);

INSERT INTO trips (driver_id, departure, arrival, departure_time, available_seats, price, vehicle_id, preferences, co2_savings) VALUES
(2, 'Paris', 'Lyon', '2025-03-01 08:00:00', 3, 25.50, 1, 'Non-fumeur', 5.2);

INSERT INTO bookings (trip_id, passenger_id, seats_booked) VALUES
(1, 1, 1);

INSERT INTO payments (booking_id, user_id, amount, payment_status) VALUES
(1, 1, 25.50, 'completed');

INSERT INTO reviews (trip_id, reviewer_id, reviewed_user_id, rating, comment) VALUES
(1, 1, 2, 5, 'Super trajet, conducteur très sympathique !');