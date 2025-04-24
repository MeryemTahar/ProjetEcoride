<?php
// Active les en-têtes CORS
header("Access-Control-Allow-Origin: http://localhost:4200"); // Remplace par l'origine de ton front-end
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Gérer les requêtes OPTIONS (prévol)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
?>
