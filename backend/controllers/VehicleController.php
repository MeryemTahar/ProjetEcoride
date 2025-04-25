<?php
// backend/controllers/VehicleController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Vehicle.php';

class VehicleController {
    private $vehicleModel;

    public function __construct(PDO $pdo) {
        $this->vehicleModel = new Vehicle($pdo);
    }

    /** GET /api/vehicles */
    public function index() {
        $vehicles = $this->vehicleModel->getAllVehicles();
        header('Content-Type: application/json');
        echo json_encode($vehicles);
    }

    /** GET /api/vehicles/{id} */
    public function show(int $id) {
        $vehicle = $this->vehicleModel->getVehicleById($id);
        header('Content-Type: application/json');
        if ($vehicle) {
            echo json_encode($vehicle);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Vehicle not found']);
        }
    }

    /** POST /api/vehicles */
    public function store() {
        $input = json_decode(file_get_contents('php://input'), true);

        $ok = $this->vehicleModel->addVehicle(
            $input['brand']         ?? null,
            $input['model']         ?? null,
            $input['year']          ?? null,
            $input['license_plate'] ?? null,
            $input['type']          ?? null,
            $input['color']         ?? null,
            $input['user_id']       ?? null
        );

        header('Content-Type: application/json');
        if ($ok) {
            http_response_code(201);
            echo json_encode(['status' => 'success', 'message' => 'Vehicle created']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Failed to create vehicle']);
        }
    }

    /** PUT /api/vehicles/{id} */
    public function update(int $id) {
        $input = json_decode(file_get_contents('php://input'), true);

        $ok = $this->vehicleModel->updateVehicle(
            $id,
            $input['brand']         ?? null,
            $input['model']         ?? null,
            $input['year']          ?? null,
            $input['license_plate'] ?? null,
            $input['type']          ?? null
        );

        header('Content-Type: application/json');
        if ($ok) {
            echo json_encode(['status' => 'success', 'message' => 'Vehicle updated']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Failed to update vehicle']);
        }
    }

    /** DELETE /api/vehicles/{id} */
    public function destroy(int $id) {
        $ok = $this->vehicleModel->deleteVehicle($id);

        header('Content-Type: application/json');
        if ($ok) {
            echo json_encode(['status' => 'success', 'message' => 'Vehicle deleted']);
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete vehicle']);
        }
    }
}
