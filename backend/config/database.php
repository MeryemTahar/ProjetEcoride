<?php

// Paramètres de connexion à la base de données
$host = 'localhost'; // Adresse du serveur MySQL
$dbname = 'ecoride'; // Nom de la base de données
$username = 'ecoride_user'; // Nom d'utilisateur MySQL
$password = 'MotDePasseFort123!'; // Mot de passe de l'utilisateur

try {
    // Création de la connexion PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Configuration des options PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // En cas d'erreur, on affiche un message et on arrête le script
    die("\u{274C} Erreur de connexion à la base de données : " . $e->getMessage());
}

?>