<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Booking.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$bookingModel = new Booking($pdo);

// ID de test pour un passager et un trajet existants
$trip_id = 4; // À ajuster selon les données en base
$passenger_id = 3; // À ajuster selon les données en base

// ➜ Ajouter une réservation
$added = $bookingModel->addBooking($trip_id, $passenger_id, 1, 'pending');
if ($added) {
    echo "✅ Réservation ajoutée avec succès !<br>";
} else {
    echo "❌ Erreur lors de l'ajout de la réservation.<br>";
}

// ➜ Récupérer toutes les réservations
$bookings = $bookingModel->getAllBookings();
echo "<pre>";
print_r($bookings);
echo "</pre>";

// ➜ Récupérer une réservation spécifique
$booking = $bookingModel->getBookingById(6); // Modifier l'ID si nécessaire
if ($booking) {
    echo "✅ Réservation trouvée : ID " . $booking['id'] . ", Statut : " . $booking['booking_status'] . "<br>";
} else {
    echo "❌ Aucune réservation trouvée.<br>";
}

// ➜ Modifier une réservation (statut confirmé)
$updated = $bookingModel->updateBooking(1, 'confirmed'); // Modifier l'ID si nécessaire
if ($updated) {
    echo "✅ Réservation mise à jour avec succès !<br>";
} else {
    echo "❌ Erreur lors de la mise à jour de la réservation.<br>";
}

// ➜ Supprimer une réservation
$deleted = $bookingModel->deleteBooking(1); // Modifier l'ID si nécessaire
if ($deleted) {
    echo "✅ Réservation supprimée avec succès !<br>";
} else {
    echo "❌ Erreur lors de la suppression de la réservation.<br>";
}
?>