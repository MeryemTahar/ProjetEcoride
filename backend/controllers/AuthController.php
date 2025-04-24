<?php
namespace App\Controllers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDO;

class AuthController {
    private PDO $pdo;
    private string $secret;
    private int $accessExpiry = 3600;

    public function __construct(PDO $pdo, string $secret) {
        $this->pdo    = $pdo;
        $this->secret = $secret;
    }

    public function register(): void {
        $d = json_decode(file_get_contents('php://input'), true);
        $firstname = trim($d['firstname'] ?? '');
        $lastname  = trim($d['lastname'] ?? '');
        $email     = filter_var($d['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password  = $d['password'] ?? '';
        $phone     = trim($d['phone'] ?? '');
        $role      = $d['role'] ?? 'user';

        if (! $email || strlen($password) < 6) {
            http_response_code(422);
            echo json_encode(['error'=>'Données invalides']);
            exit;
        }

        // Vérifie unicité
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = :e");
        $stmt->execute([':e'=>$email]);
        if ($stmt->fetch()) {
            http_response_code(409);
            echo json_encode(['error'=>'Email déjà utilisé']);
            exit;
        }

        $hash = password_hash($password, PASSWORD_BCRYPT);
        try {
            $stmt = $this->pdo->prepare("
              INSERT INTO users 
                (firstname, lastname, email, password, phone, role)
              VALUES 
                (:fn, :ln, :em, :pw, :ph, :rl)
            ");
            $stmt->execute([
              ':fn'=>$firstname, ':ln'=>$lastname,
              ':em'=>$email,     ':pw'=>$hash,
              ':ph'=>$phone,     ':rl'=>$role
            ]);
            http_response_code(201);
            echo json_encode(['status'=>'success']);
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['error'=>'Erreur base de données']);
        }
        exit;
    }

    public function login(): void {
        $d = json_decode(file_get_contents('php://input'), true);
        $email    = $d['email']    ?? '';
        $password = $d['password'] ?? '';

        $stmt = $this->pdo->prepare("SELECT id, password, role FROM users WHERE email=:e");
        $stmt->execute([':e'=>$email]);
        $u = $stmt->fetch(PDO::FETCH_ASSOC);

        if (! $u || ! password_verify($password, $u['password'])) {
            http_response_code(401);
            echo json_encode(['error'=>'Identifiants invalides']);
            exit;
        }

        $now = time();
        $payload = [
          'iat'  => $now,
          'exp'  => $now + $this->accessExpiry,
          'sub'  => $u['id'],
          'role' => $u['role']
        ];
        $jwt = JWT::encode($payload, $this->secret, 'HS256');

        echo json_encode(['accessToken'=>$jwt]);
        exit;
    }

    public function verifyToken(string $header): array {
        if (! preg_match('/Bearer\s(\S+)/', $header, $m)) {
            return ['error'=>'Token manquant'];
        }
        try {
            $t = JWT::decode($m[1], new Key($this->secret, 'HS256'));
            return (array)$t;
        } catch (\Exception $e) {
            return ['error'=>'Token invalide'];
        }
    }
}
