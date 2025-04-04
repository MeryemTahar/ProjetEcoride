<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController {
    private $pdo;
    private $secret_key = "YOUR_SECRET_KEY"; 
    private $accessTokenExpiry = 3600; 
    private $refreshTokenExpiry = 604800; 

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login($email, $password) {
        // Récupération de l'utilisateur
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Création du token
            $payload = [
                'iss' => 'localhost',
                'iat' => time(),
                'exp' => time() + $this->accessTokenExpiry,
                'user_id' => $user['id'],
                'role' => $user['role']
            ];
            $token = JWT::encode($payload, $this->secret_key, 'HS256');
            return json_encode(["status" => "success", "accessToken" => $token]);
        } else {
            return json_encode(["status" => "error", "message" => "Invalid credentials"]);
        }
    }

    public function register($firstname, $lastname, $email, $password, $phone, $role) {
        // Hash du mot de passe pour plus de sécurité
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        // Préparation de la requête d'insertion
        $stmt = $this->pdo->prepare("INSERT INTO users (firstname, lastname, email, password, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
        $result = $stmt->execute([$firstname, $lastname, $email, $hashedPassword, $phone, $role]);
        
        if ($result) {
            return json_encode(["status" => "success", "message" => "Utilisateur créé avec succès"]);
        } else {
            return json_encode(["status" => "error", "message" => "Erreur lors de la création de l'utilisateur"]);
        }
    }

    public function refreshToken($refreshToken) {
        // Pour l'instant, non implémenté
        return json_encode(["status" => "error", "message" => "Refresh token non implémenté"]);
    }

    public function logout($refreshToken) {
        // Pour l'instant, on se contente de renvoyer un message de succès sans action réelle
        return json_encode(["status" => "success", "message" => "Déconnexion réussie"]);
    }

    // Vérification du token
    public function verifyToken($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->secret_key, 'HS256'));
            return (array)$decoded;
        } catch (\Exception $e) {
            return ["error" => "Token invalide ou expiré"];
        }
    }
}
?>
