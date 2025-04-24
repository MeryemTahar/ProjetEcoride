<?php
// public/index.php

// 1) active l’affichage des erreurs en dev
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2) inclus la config de la BDD
require_once __DIR__ . '/../config/database.php';

// 3) inclus tes contrôleurs
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/TripController.php';
// … ajouter d’autres controllers si besoin

// 4) importe les classes avec leur namespace (si tu utilises un autoloader PSR-4 via composer)
use App\Controllers\AuthController;
use App\Controllers\TripController;
// sinon, si tu n’as pas de namespace, commente les lignes `use` et instancie directement

// 5) initialise tes controllers en leur passant le $pdo
$authCtrl = new AuthController($pdo);
$tripCtrl = new TripController($pdo);

// 6) récupère la méthode HTTP et le paramètre `path`
$method = $_SERVER['REQUEST_METHOD'];
$path   = isset($_GET['path']) ? trim($_GET['path'], '/') : '';

// 7) routeur basique
header('Content-Type: application/json; charset=utf-8');

if ($path === 'login' && $method === 'POST') {
    // récupère le JSON brut et le décode
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

// ——————————————
// Routes pour les trajets
// ——————————————

if ($path === 'trips' && $method === 'GET') {
    // GET /trips → liste de tous les trajets
    echo $tripCtrl->index();
    exit;
}

if (preg_match('#^trips/(\d+)$#', $path, $m) && $method === 'GET') {
    // GET /trips/{id} → détail d’un trajet
    echo $tripCtrl->show((int)$m[1]);
    exit;
}

if ($path === 'trips' && $method === 'POST') {
    // POST /trips → création d’un trajet
    $data = json_decode(file_get_contents('php://input'), true);
    echo $tripCtrl->store($data);
    exit;
}

if (preg_match('#^trips/(\d+)/join$#', $path, $m) && $method === 'POST') {
    // POST /trips/{id}/join → rejoindre un trajet
    echo $tripCtrl->join((int)$m[1]);
    exit;
}

// … tu peux ajouter d’autres routes (update, delete, filters, etc.)

// Si on arrive ici → route non trouvée
http_response_code(404);
echo json_encode([
  'status'  => 'error',
  'message' => 'Route not found'
]);
