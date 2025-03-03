<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

$pdo->query("UPDATE users SET password = '" . password_hash("password123", PASSWORD_BCRYPT) . "' WHERE email = 'bob@example.com'");
$pdo->query("UPDATE users SET password = '" . password_hash("password123", PASSWORD_BCRYPT) . "' WHERE email = 'admin@example.com'");

echo "✅ Mots de passe mis à jour avec succès !<br>";

// Création d'une instance de User
$userModel = new User($pdo);

// Supprimer Alice avant de l'ajouter à nouveau
$pdo->query("DELETE FROM users WHERE email = 'alice@example.com'");

// Ajouter un utilisateur (sans double hachage)
$added = $userModel->addUser('Alice', 'Durand', 'alice@example.com', 'password123', '0612345678', 'passenger');
if ($added === true) {
    echo "✅ Utilisateur ajouté avec succès !<br>";
} elseif ($added === "Cet email est déjà utilisé !") {
    echo "⚠️ L'utilisateur existe déjà, pas d'insertion.<br>";
} else {
    echo "❌ Erreur lors de l'ajout de l'utilisateur.<br>";
}

// Vérifier si l'utilisateur est en base
$user = $userModel->getUserById($pdo->lastInsertId()); // Récupère le dernier ID inséré
if ($user) {
    echo "✅ Utilisateur trouvé : " . $user['firstname'] . " " . $user['lastname'] . "<br>";
} else {
    echo "❌ Aucun utilisateur trouvé.<br>";
}

// Tester la connexion avec un mot de passe correct
$loginSuccess = $userModel->checkLogin('alice@example.com', 'password123');
if ($loginSuccess) {
    echo "✅ Connexion réussie !<br>";
} else {
    echo "❌ Identifiants invalides.<br>";
}

// Debugging : Vérifier les infos stockées
var_dump($user);

// Tester la connexion avec un mot de passe incorrect
$loginFail = $userModel->checkLogin('alice@example.com', 'wrongpassword');
if ($loginFail) {
    echo "❌ Problème : connexion réussie avec un mauvais mot de passe !<br>";
} else {
    echo "✅ Le système rejette correctement un mauvais mot de passe.<br>";
}
?>
