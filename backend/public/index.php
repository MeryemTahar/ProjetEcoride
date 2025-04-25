<?php
// public/index.php

// 1) Affichage des erreurs en dev
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2) Inclusion de la config BDD
require_once __DIR__ . '/../config/database.php';

// 3) Inclusion de tous les controllers
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/TripController.php';
require_once __DIR__ . '/../controllers/BookingController.php';
require_once __DIR__ . '/../controllers/PaymentController.php';
require_once __DIR__ . '/../controllers/ReviewController.php';
require_once __DIR__ . '/../controllers/VehicleController.php';

// 4) Instanciation des controllers
$authCtrl     = new AuthController($pdo);
$userCtrl     = new UserController($pdo);
$tripCtrl     = new TripController($pdo);
$bookingCtrl  = new BookingController($pdo);
$paymentCtrl  = new PaymentController($pdo);
$reviewCtrl   = new ReviewController($pdo);
$vehicleCtrl  = new VehicleController($pdo);

// 5) Récupération de la méthode HTTP et du path
$method = $_SERVER['REQUEST_METHOD'];
$path   = isset($_GET['path']) ? trim($_GET['path'], '/') : '';

// 6) On renvoie toujours du JSON
header('Content-Type: application/json; charset=utf-8');

// 7) ROUTES AUTH
if ($path === 'login' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $authCtrl->login($data['email'], $data['password']);
    exit;
}
if ($path === 'register' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $authCtrl->register(
        $data['firstname'],
        $data['lastname'],
        $data['email'],
        $data['password'],
        $data['phone'] ?? null,
        $data['role']  ?? 'Utilisateur'
    );
    exit;
}

// —————————————————————————
// ROUTES USERS
// —————————————————————————
if ($path === 'users' && $method === 'GET') {
    echo json_encode($userCtrl->index());
    exit;
}
if (preg_match('#^users/(\d+)$#', $path, $m) && $method === 'GET') {
    echo json_encode($userCtrl->show((int)$m[1]));
    exit;
}
if ($path === 'users' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $userCtrl->store($data);
    exit;
}
if (preg_match('#^users/(\d+)$#', $path, $m) && $method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $userCtrl->update((int)$m[1], $data);
    exit;
}
if (preg_match('#^users/(\d+)$#', $path, $m) && $method === 'DELETE') {
    echo $userCtrl->destroy((int)$m[1]);
    exit;
}

// —————————————————————————
// ROUTES TRIPS
// —————————————————————————
if ($path === 'trips' && $method === 'GET') {
    echo json_encode($tripCtrl->index());
    exit;
}
if (preg_match('#^trips/(\d+)$#', $path, $m) && $method === 'GET') {
    echo json_encode($tripCtrl->show((int)$m[1]));
    exit;
}
if ($path === 'trips' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $tripCtrl->store($data);
    exit;
}
if (preg_match('#^trips/(\d+)$#', $path, $m) && $method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $tripCtrl->update((int)$m[1], $data);
    exit;
}
if (preg_match('#^trips/(\d+)$#', $path, $m) && $method === 'DELETE') {
    echo $tripCtrl->destroy((int)$m[1]);
    exit;
}
if (preg_match('#^trips/(\d+)/join$#', $path, $m) && $method === 'POST') {
    echo $tripCtrl->join((int)$m[1]);
    exit;
}

// —————————————————————————
// ROUTES BOOKINGS
// —————————————————————————
if ($path === 'bookings' && $method === 'GET') {
    echo json_encode($bookingCtrl->index());
    exit;
}
if (preg_match('#^bookings/(\d+)$#', $path, $m) && $method === 'GET') {
    echo json_encode($bookingCtrl->show((int)$m[1]));
    exit;
}
if ($path === 'bookings' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $bookingCtrl->store($data);
    exit;
}
if (preg_match('#^bookings/(\d+)$#', $path, $m) && $method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $bookingCtrl->update((int)$m[1], $data);
    exit;
}
if (preg_match('#^bookings/(\d+)$#', $path, $m) && $method === 'DELETE') {
    echo $bookingCtrl->destroy((int)$m[1]);
    exit;
}

// —————————————————————————
// ROUTES PAYMENTS
// —————————————————————————
if ($path === 'payments' && $method === 'GET') {
    echo json_encode($paymentCtrl->index());
    exit;
}
if (preg_match('#^payments/(\d+)$#', $path, $m) && $method === 'GET') {
    echo json_encode($paymentCtrl->show((int)$m[1]));
    exit;
}
if ($path === 'payments' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $paymentCtrl->store($data);
    exit;
}
if (preg_match('#^payments/(\d+)$#', $path, $m) && $method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $paymentCtrl->update((int)$m[1], $data);
    exit;
}
if (preg_match('#^payments/(\d+)$#', $path, $m) && $method === 'DELETE') {
    echo $paymentCtrl->destroy((int)$m[1]);
    exit;
}

// —————————————————————————
// ROUTES REVIEWS
// —————————————————————————
if ($path === 'reviews' && $method === 'GET') {
    echo json_encode($reviewCtrl->index());
    exit;
}
if (preg_match('#^reviews/(\d+)$#', $path, $m) && $method === 'GET') {
    echo json_encode($reviewCtrl->show((int)$m[1]));
    exit;
}
if ($path === 'reviews' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $reviewCtrl->store($data);
    exit;
}
if (preg_match('#^reviews/(\d+)$#', $path, $m) && $method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $reviewCtrl->update((int)$m[1], $data);
    exit;
}
if (preg_match('#^reviews/(\d+)$#', $path, $m) && $method === 'DELETE') {
    echo $reviewCtrl->destroy((int)$m[1]);
    exit;
}

// —————————————————————————
// ROUTES VEHICLES
// —————————————————————————
if ($path === 'vehicles' && $method === 'GET') {
    echo json_encode($vehicleCtrl->index());
    exit;
}
if (preg_match('#^vehicles/(\d+)$#', $path, $m) && $method === 'GET') {
    echo json_encode($vehicleCtrl->show((int)$m[1]));
    exit;
}
if ($path === 'vehicles' && $method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $vehicleCtrl->store($data);
    exit;
}
if (preg_match('#^vehicles/(\d+)$#', $path, $m) && $method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    echo $vehicleCtrl->update((int)$m[1], $data);
    exit;
}
if (preg_match('#^vehicles/(\d+)$#', $path, $m) && $method === 'DELETE') {
    echo $vehicleCtrl->destroy((int)$m[1]);
    exit;
}

// —————————————————————————
// Si on arrive ici → route non trouvée
// —————————————————————————
http_response_code(404);
echo json_encode([
  'status'  => 'error',
  'message' => 'Route not found'
]);
