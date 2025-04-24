<?php
// backend/controllers/TripController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Trip.php';

class TripController {
    private $tripModel;

    public function __construct(PDO $pdo) {
        $this->tripModel = new Trip($pdo);
    }

    /** GET /api/trips */
    public function index() {
        $trips = $this->tripModel->getAllTrips();
        header('Content-Type: application/json');
        echo json_encode($trips);
    }

    /** GET /api/trips/{id} */
    public function show(int $id) {
        $trip = $this->tripModel->getTripById($id);
        if ($trip) {
            header('Content-Type: application/json');
            echo json_encode($trip);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Trip not found']);
        }
    }

    /** POST /api/trips */
    public function store() {
        // on suppose du JSON dans le body
        $input = json_decode(file_get_contents('php://input'), true);

        $ok = $this->tripModel->addTrip(
            $input['departure'] ?? null,
            $input['arrival'] ?? null,
            $input['departure_time'] ?? null,
            $input['available_seats'] ?? null,
            $input['price'] ?? null,
            $input['vehicle_id'] ?? null,
            $input['preferences'] ?? null,
            $input['co2_savings'] ?? null,
            $input['driver_id'] ?? null
        );

        header('Content-Type: application/json');
        if ($ok) {
            http_response_code(201);
            echo json_encode(['status' => 'success', 'message' => 'Trip created']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Failed to create trip']);
        }
    }

    /** PUT /api/trips/{id} */
    public function update(int $id) {
        $input = json_decode(file_get_contents('php://input'), true);

        $ok = $this->tripModel->updateTrip(
            $id,
            $input['departure'] ?? null,
            $input['arrival'] ?? null,
            $input['departure_time'] ?? null,
            $input['available_seats'] ?? null,
            $input['price'] ?? null,
            $input['vehicle_id'] ?? null,
            $input['preferences'] ?? null,
            $input['co2_savings'] ?? null
        );

        header('Content-Type: application/json');
        if ($ok) {
            echo json_encode(['status' => 'success', 'message' => 'Trip updated']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Failed to update trip']);
        }
    }

    /** DELETE /api/trips/{id} */
    public function destroy(int $id) {
        $ok = $this->tripModel->deleteTrip($id);

        header('Content-Type: application/json');
        if ($ok) {
            echo json_encode(['status' => 'success', 'message' => 'Trip deleted']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete trip']);
        }
    }

    /** POST /api/trips/{id}/join */
    public function join(int $id) {
        $input = json_decode(file_get_contents('php://input'), true);
        $seats = $input['seats_booked'] ?? 1;
        $userId = $input['passenger_id'] ?? null;

        $ok = $this->tripModel->bookTrip($id, $userId, $seats);

        header('Content-Type: application/json');
        if ($ok) {
            echo json_encode(['status' => 'success', 'message' => 'Trip booked']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Failed to book trip']);
        }
    }
}
