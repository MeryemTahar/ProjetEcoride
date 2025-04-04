<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__. '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

error_reporting(E_ALL);
ini_set('display_errors', 1);

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer tous les utilisateurs
    public function getAllUsers() {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            // En cas d'erreur SQL, on peut retourner un tableau vide ou un message d’erreur
            return [];
        }
    }

    // Récupérer un utilisateur par son ID
    public function getUserById($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (\PDOException $e) {
            return null;
        }
    }

    // Récupérer les utilisateurs par rôle (passenger, driver, admin)
    public function getUsersByRole($role) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE role = ?");
            $stmt->execute([$role]);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            return [];
        }
    }

    // Ajouter un utilisateur (avec vérification d'email et hachage bcrypt)
    public function addUser($firstname, $lastname, $email, $password, $phone, $role) {
        try {
            // Vérifier si l'email existe déjà
            $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                return [
                    "success" => false,
                    "message" => "Cet email est déjà utilisé !"
                ];
            }

            // Préparer l'insertion
            $stmt = $this->pdo->prepare("
                INSERT INTO users (firstname, lastname, email, password, phone, role) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            // Hachage du mot de passe (bcrypt avec un coût de 12)
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ["cost" => 12]);

            // Exécuter l'insertion
            $success = $stmt->execute([
                $firstname, 
                $lastname, 
                $email, 
                $hashedPassword, 
                $phone, 
                $role
            ]);

            if ($success) {
                return [
                    "success" => true,
                    "message" => "Utilisateur ajouté avec succès !"
                ];
            } else {
                return [
                    "success" => false,
                    "message" => "Erreur lors de l'insertion de l'utilisateur."
                ];
            }
        } catch (\PDOException $e) {
            return [
                "success" => false,
                "message" => "Erreur SQL : " . $e->getMessage()
            ];
        }
    }

    // Vérifier la connexion d'un utilisateur (login)
    public function checkLogin($email, $password) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Connexion réussie
                return [
                    "success" => true,
                    "user"    => $user
                ];
            } else {
                // Identifiants invalides
                return [
                    "success" => false,
                    "message" => "Email ou mot de passe incorrect."
                ];
            }
        } catch (\PDOException $e) {
            return [
                "success" => false,
                "message" => "Erreur SQL : " . $e->getMessage()
            ];
        }
    }
}
    