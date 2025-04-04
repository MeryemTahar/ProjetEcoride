<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';

class Vehicle {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupérer tous les véhicules
    public function getAllVehicles() {
        $stmt = $this->pdo->prepare("SELECT * FROM vehicles");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Récupérer un véhicule par son ID
    public function getVehicleById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM vehicles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Ajouter un véhicule avec type (thermique ou électrique)
    public function addVehicle($brand, $model, $year, $license_plate, $type, $color, $user_id) {
        $stmt = $this->pdo->prepare("
            INSERT INTO vehicles (brand, model, year, license_plate, type, color, user_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$brand, $model, $year, $license_plate, $type, $color, $user_id]);
    }

    // Modifier un véhicule existant
    public function updateVehicle($id, $brand, $model, $year, $license_plate, $type) {
        $stmt = $this->pdo->prepare("
            UPDATE vehicles 
            SET brand = ?, model = ?, year = ?, license_plate = ?, type = ?
            WHERE id = ?
        ");
        return $stmt->execute([$brand, $model, $year, $license_plate, $type, $id]);
    }

    // Supprimer un véhicule
    public function deleteVehicle($id) {
        $stmt = $this->pdo->prepare("DELETE FROM vehicles WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
