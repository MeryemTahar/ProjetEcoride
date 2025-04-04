<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

// Mise à jour des mots de passe pour les utilisateurs existants (bob et admin)
$pdo->query("UPDATE users SET password = '" . password_hash("password123", PASSWORD_BCRYPT) . "' WHERE email = 'bob@example.com'");
$pdo->query("UPDATE users SET password = '" . password_hash("password123", PASSWORD_BCRYPT) . "' WHERE email = 'admin@example.com'");

echo "✅ Mots de passe mis à jour avec succès !<br>";

// Création d'une instance du modèle User
$userModel = new User($pdo);

// Supprimer Alice avant de l'ajouter à nouveau (pour éviter les doublons)
$pdo->query("DELETE FROM users WHERE email = 'alice@example.com'");

// Ajouter un utilisateur
$added = $userModel->addUser('Alice', 'Durand', 'alice@example.com', 'password123', '0612345678', 'passenger');

if (isset($added["success"]) && $added["success"] === true) {
    echo "✅ Utilisateur ajouté avec succès !<br>";
} elseif (isset($added["success"]) && $added["success"] === false && strpos($added["message"], "déjà utilisé") !== false) {
    echo "⚠️ L'utilisateur existe déjà, pas d'insertion.<br>";
} else {
    echo "❌ Erreur lors de l'ajout de l'utilisateur : " . $added["message"] . "<br>";
}

// Récupérer le dernier ID inséré et vérifier si l'utilisateur est en base
$lastInsertId = $pdo->lastInsertId();
$user = $userModel->getUserById($lastInsertId);
if ($user) {
    echo "✅ Utilisateur trouvé : " . $user['firstname'] . " " . $user['lastname'] . "<br>";
} else {
    echo "❌ Aucun utilisateur trouvé.<br>";
}

// Tester la connexion avec un mot de passe correct
$loginResult = $userModel->checkLogin('alice@example.com', 'password123');
if (isset($loginResult["success"]) && $loginResult["success"] === true) {
    echo "✅ Connexion réussie !<br>";
} else {
    echo "❌ Identifiants invalides.<br>";
}

// Debugging : Afficher les informations de l'utilisateur
var_dump($user);

// Tester la connexion avec un mot de passe incorrect
$loginFail = $userModel->checkLogin('alice@example.com', 'wrongpassword');
if (isset($loginFail["success"]) && $loginFail["success"] === true) {
    echo "❌ Problème : connexion réussie avec un mauvais mot de passe !<br>";
} else {
    echo "✅ Le système rejette correctement un mauvais mot de passe.<br>";
}
?>
