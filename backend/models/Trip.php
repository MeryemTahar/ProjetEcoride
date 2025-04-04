<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__. '/../vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

class Trip {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupérer tous les trajets
    public function getAllTrips() {
        $stmt = $this->pdo->prepare("SELECT * FROM trips");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Récupérer un trajet par son ID
    public function getTripById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM trips WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Récupérer les trajets par ville de départ
    public function getTripsByDeparture($departure) {
        $stmt = $this->pdo->prepare("SELECT * FROM trips WHERE departure = ?");
        $stmt->execute([$departure]);
        return $stmt->fetchAll();
    }

    // Récupérer les trajets par ville d'arrivée
    public function getTripsByArrival($arrival) {
        $stmt = $this->pdo->prepare("SELECT * FROM trips WHERE arrival = ?");
        $stmt->execute([$arrival]);
        return $stmt->fetchAll();
    }

    // Récupérer les trajets par ville de départ et ville d'arrivée
    public function getTripsByDepartureAndArrival($departure, $arrival) {
        $stmt = $this->pdo->prepare("SELECT * FROM trips WHERE departure = ? AND arrival = ?");
        $stmt->execute([$departure, $arrival]);
        return $stmt->fetchAll();
    }

    // Ajouter un trajet (avec véhicule, préférences et économie de CO2)
    public function addTrip($departure, $arrival, $departure_time, $available_seats, $price, $vehicle_id, $preferences, $co2_savings, $driver_id) {
        $stmt = $this->pdo->prepare("
            INSERT INTO trips (departure, arrival, departure_time, available_seats, price, vehicle_id, preferences, co2_savings, driver_id) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$departure, $arrival, $departure_time, $available_seats, $price, $vehicle_id, $preferences, $co2_savings, $driver_id]);
    }

    // Modifier un trajet existant
    public function updateTrip($id, $departure, $arrival, $departure_time, $available_seats, $price, $vehicle_id, $preferences, $co2_savings) {
        $stmt = $this->pdo->prepare("
            UPDATE trips 
            SET departure = ?, arrival = ?, departure_time = ?, available_seats = ?, price = ?, vehicle_id = ?, preferences = ?, co2_savings = ?
            WHERE id = ?
        ");
        return $stmt->execute([$departure, $arrival, $departure_time, $available_seats, $price, $vehicle_id, $preferences, $co2_savings, $id]);
    }

    // Supprimer un trajet
    public function deleteTrip($id) {
        $stmt = $this->pdo->prepare("DELETE FROM trips WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Réserver un trajet
    public function bookTrip($trip_id, $passenger_id, $seats_booked) {
        $stmt = $this->pdo->prepare("INSERT INTO bookings (trip_id, passenger_id, seats_booked) VALUES (?, ?, ?)");
        return $stmt->execute([$trip_id, $passenger_id, $seats_booked]);
    }
}
?>