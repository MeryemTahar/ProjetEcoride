<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';

header('Content-Type: application/json');

$pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $DB_OPTIONS);
$authController = new AuthController($pdo);

// Simuler une connexion avec email et mot de passe
$email = "bob@example.com"; // Remplace par un email existant en BDD
$password = "bu7ftmud"; // Remplace par le mot de passe correct en clair

// Tester la connexion
$response = $authController->login($email, $password);
echo $response;

// Décoder la réponse JSON pour récupérer les données
$responseData = json_decode($response, true);
var_dump($responseData);

// Vérification du mot de passe si l'authentification réussit
if (isset($responseData['status']) && $responseData['status'] === "success") {
    $user = $responseData['user'];

    var_dump($password, $user['password']); exit;
    if 
    (password_verify($password, $user['password'])) {
        echo "Mot de passe valide ✅";
    } else {
        echo "Mot de passe invalide ❌";
    }
} else {
    echo "Erreur d'authentification : " . $responseData['message'];
}

exit;
?>

