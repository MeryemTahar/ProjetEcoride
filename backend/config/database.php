<?php
// Paramètres de connexion à la base de données
$DB_DSN = "mysql:host=localhost;dbname=ecoride;charset=utf8";
$DB_USER = "ecoride_user"; // Nom d'utilisateur MySQL
$DB_PASSWORD = "MotDePasseFort123!"; // Mot de passe de l'utilisateur

$DB_OPTIONS = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    // Création de la connexion PDO
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD, $DB_OPTIONS);
} catch (PDOException $e) {
    die("❌ Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
