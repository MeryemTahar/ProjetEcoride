<?php
require_once __DIR__ . '/../config/database.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Récupérer tous les utilisateurs
    public function getAllUsers() {
        $stmt = $this->pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Récupérer un utilisateur par son ID
    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Récupérer les utilisateurs par rôle (passenger, driver, admin)
    public function getUsersByRole($role) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE role = ?");
        $stmt->execute([$role]);
        return $stmt->fetchAll();
    }

    // Ajouter un utilisateur (sans double hachage)
    public function addUser($firstname, $lastname, $email, $password, $phone, $role) {
        // Vérifier si l'email existe déjà
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return "Cet email est déjà utilisé !"; 
        }

        // Insérer l'utilisateur
        $stmt = $this->pdo->prepare("INSERT INTO users (firstname, lastname, email, password, phone, role) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$firstname, $lastname, $email, password_hash($password, PASSWORD_BCRYPT), $phone, $role]);
    }

    // Vérifier la connexion d'un utilisateur
    public function checkLogin($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user) {
            var_dump($user['password']); // Debugging : voir le mot de passe en base
            if (password_verify($password, $user['password'])) {
                return $user; // Connexion réussie
            }
        }
        return false; // Identifiants invalides
    }
}
?>
    