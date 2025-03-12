<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Trip.php';

// Création d'une instance de Trip
$tripModel = new Trip($pdo);

// 🔹 **TEST 1 : Insérer un trajet**
$added = $tripModel->addTrip(
    'Paris',            // Ville de départ
    'Lyon',            // Ville d'arrivée
    '2025-03-01 08:00:00', // Date et heure de départ
    3,                 // Nombre de places disponibles
    25.50,             // Prix du trajet
    1,                 // ID du véhicule
    'Non-fumeur, Animaux acceptés', // Préférences
    5.2,               // Économie de CO2
    2                  // ID du conducteur
);

if ($added) {
    echo "✅ Trajet ajouté avec succès !<br>";
} else {
    echo "❌ Erreur lors de l'ajout du trajet.<br>";
}

// 🔹 **TEST 2 : Modifier un trajet**
$updated = $tripModel->updateTrip(
    1,                 // ID du trajet à modifier
    'Paris',            // Nouvelle ville de départ
    'Marseille',        // Nouvelle ville d'arrivée
    '2025-03-05 10:30:00', // Nouvelle date et heure de départ
    2,                 // Nombre de places restantes
    30.00,             // Nouveau prix
    1,                 // ID du véhicule
    'Fumeur, Pas d’animaux', // Préférences mises à jour
    4.8                // Nouvelle économie de CO2
);

if ($updated) {
    echo "✅ Trajet mis à jour avec succès !<br>";
} else {
    echo "❌ Erreur lors de la mise à jour du trajet.<br>";
}
?>