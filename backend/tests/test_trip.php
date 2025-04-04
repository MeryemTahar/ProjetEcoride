<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Trip.php';

// Création d'une instance de Trip
$tripModel = new Trip($pdo);

// 🔹 **TEST 1 : Insérer un trajet**
$added = $tripModel->addTrip(
    'Paris',              // Ville de départ
    'Lyon',               // Ville d'arrivée
    '2025-03-01 08:00:00',  // Date et heure de départ
    4,                    // Nombre de places disponibles
    25.50,                // Prix du trajet
    26,                   // ID du véhicule
    'Non-fumeur, Animaux acceptés', // Préférences
    5.2,                  // Économie de CO2
    2                     // ID du conducteur
);

if ($added) {
    // Récupérer l'ID auto-incrémenté du trajet ajouté
    $lastTripId = $pdo->lastInsertId();
    echo "✅ Trajet ajouté avec succès ! (ID = $lastTripId)<br>";
} else {
    echo "❌ Erreur lors de l'ajout du trajet.<br>";
    exit;
}

// 🔹 **TEST 2 : Modifier le trajet ajouté**
$updated = $tripModel->updateTrip(
    $lastTripId, // ID du trajet ajouté
    'Paris', 
    'Marseille', 
    '2025-03-05 10:30:00', 
    2, 
    30.00, 
    28, 
    'Fumeur, Pas d’animaux', 
    4.8
);

if ($updated) {
    echo "✅ Trajet mis à jour avec succès !<br>";
} else {
    echo "❌ Erreur lors de la mise à jour du trajet.<br>";
}

// 🔹 **TEST 3 : Supprimer le trajet ajouté**
$deleted = $tripModel->deleteTrip($lastTripId);
if ($deleted) {
    echo "✅ Trajet supprimé avec succès !<br>";
} else {
    echo "❌ Erreur lors de la suppression du trajet.<br>";
}
?>
