<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';

header('Content-Type: application/json');

$authController = new AuthController($pdo);

$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : null;

// Si la méthode est POST, on récupère éventuellement le JSON du body
$data = null;
if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
}

// Gestion des endpoints
if ($method === 'POST' && $action === 'protected-endpoint') {
    // Vérification de l'existence du header Authorization
    $headers = apache_request_headers();
    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Token manquant"]);
        exit;
    }
    // Extraction du token (en retirant "Bearer ")
    $authHeader = $headers['Authorization'];
    $rawToken = str_replace('Bearer ', '', $authHeader);
    $decoded = $authController->verifyToken($rawToken);
    if (isset($decoded['error'])) {
        http_response_code(401);
        echo json_encode(["status" => "error", "message" => "Token invalide ou expiré"]);
        exit;
    }
    echo json_encode(["status" => "success", "message" => "Accès autorisé (token valide)"]);
    exit;

} elseif ($method === 'POST') {
    // Gestion des endpoints d'authentification classiques
    if ($action === 'register') {
        if (isset($data['firstname'], $data['lastname'], $data['email'], $data['password'], $data['phone'], $data['role'])) {
            echo $authController->register(
                $data['firstname'],
                $data['lastname'],
                $data['email'],
                $data['password'],
                $data['phone'],
                $data['role']
            );
        } else {
            echo json_encode(["status" => "error", "message" => "Champs manquants pour l'inscription"]);
        }
    } elseif ($action === 'login') {
        if (isset($data['email'], $data['password'])) {
            echo $authController->login($data['email'], $data['password']);
        } else {
            echo json_encode(["status" => "error", "message" => "Champs manquants pour la connexion"]);
        }
    } elseif ($action === 'refresh') {
        if (isset($data['refreshToken'])) {
            echo $authController->refreshToken($data['refreshToken']);
        } else {
            echo json_encode(["status" => "error", "message" => "Refresh token manquant"]);
        }
    } elseif ($action === 'logout') {
        if (isset($data['refreshToken'])) {
            echo $authController->logout($data['refreshToken']);
        } else {
            echo json_encode(["status" => "error", "message" => "Refresh token manquant pour le logout"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Action non reconnue"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Méthode HTTP non supportée"]);
}
?>
