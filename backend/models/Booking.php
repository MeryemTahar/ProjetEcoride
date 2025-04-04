<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';

class Booking {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ajouter une réservation
    public function addBooking($trip_id, $passenger_id, $seats_booked, $status = 'pending') {
        // On pourrait envelopper la requête dans un try/catch pour une meilleure gestion des erreurs si besoin
        $stmt = $this->pdo->prepare("INSERT INTO bookings (trip_id, passenger_id, seats_booked, booking_status) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$trip_id, $passenger_id, $seats_booked, $status]);
    }

    // Récupérer une réservation par son ID
    public function getBookingById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Récupérer toutes les réservations
    public function getAllBookings() {
        $stmt = $this->pdo->prepare("SELECT * FROM bookings");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Modifier une réservation (changement de statut)
    public function updateBooking($id, $status) {
        $stmt = $this->pdo->prepare("UPDATE bookings SET booking_status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    // Supprimer une réservation
    public function deleteBooking($id) {
        $stmt = $this->pdo->prepare("DELETE FROM bookings WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
