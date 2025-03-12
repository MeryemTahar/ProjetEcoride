<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Vehicle.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$vehicleModel = new Vehicle($pdo);

// Ajouter un véhicule
$added = $vehicleModel->addVehicle('Peugeot', '208', 2023, 'AB-123-CD', 'thermique', 'Rouge', 2);
if ($added) {
    echo "✅ Véhicule ajouté avec succès !<br>";
} else {
    echo "❌ Erreur lors de l'ajout du véhicule.<br>";
}

$user_id = 2;
var_dump($user_id);


// Récupérer tous les véhicules
$vehicles = $vehicleModel->getAllVehicles();
echo "<pre>";
print_r($vehicles);
echo "</pre>";

// Récupérer un véhicule par ID
$vehicle = $vehicleModel->getVehicleById(26);
if ($vehicle) {
    echo "✅ Véhicule trouvé : " . $vehicle['brand'] . " " . $vehicle['model'] . "<br>";
} else {
    echo "❌ Aucun véhicule trouvé.<br>";
}

// Modifier un véhicule
$updated = $vehicleModel->updateVehicle(1, 'Renault', 'Clio', 2022, 'XY-987-ZT', 'électrique', 'Bleu');
if ($updated) {
    echo "✅ Véhicule mis à jour avec succès !<br>";
} else {
    echo "❌ Erreur lors de la mise à jour du véhicule.<br>";
}

// Supprimer un véhicule
$deleted = $vehicleModel->deleteVehicle(1);
if ($deleted) {
    echo "✅ Véhicule supprimé avec succès !<br>";
} else {
    echo "❌ Erreur lors de la suppression du véhicule.<br>";
}



?>
