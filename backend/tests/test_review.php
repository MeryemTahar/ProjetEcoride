<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Review.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$reviewModel = new Review($pdo);

// ID de test pour un avis laissé par un passager sur un conducteur
$trip_id_passenger = 4;
$reviewer_id_passenger = 5;    // Passager qui laisse un avis
$reviewed_user_id_driver = 2;  // Conducteur qui reçoit l'avis

// ID de test pour un avis laissé par un conducteur sur un passager
$trip_id_driver = 4;
$reviewer_id_driver = 2;       // Conducteur qui laisse un avis
$reviewed_user_id_passenger = 5; // Passager qui reçoit l'avis

function checkUserExists($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

if (!checkUserExists($pdo, $reviewer_id_passenger) || !checkUserExists($pdo, $reviewed_user_id_driver)) {
    die("❌ Erreur : l'un des utilisateurs n'existe pas en base.<br>");
}

// ➜ Ajouter un avis passager
$added_passenger_review = $reviewModel->addReview(
    $trip_id_passenger, 
    $reviewer_id_passenger, 
    $reviewed_user_id_driver, 
    5, 
    'Super trajet !'
);
if ($added_passenger_review) {
    echo "✅ Avis passager ajouté avec succès !<br>";
} else {
    echo "❌ Erreur lors de l'ajout de l'avis passager.<br>";
}

// ➜ Ajouter un avis conducteur
$added_driver_review = $reviewModel->addReview(
    $trip_id_driver, 
    $reviewer_id_driver, 
    $reviewed_user_id_passenger, 
    4, 
    'Conducteur très sympa !'
);
if ($added_driver_review) {
    echo "✅ Avis conducteur ajouté avec succès !<br>";
} else {
    echo "❌ Erreur lors de l'ajout de l'avis conducteur.<br>";
}

$last_review = $reviewModel->getReviewById($pdo->lastInsertId(18));
if ($last_review) {
    echo "✅ Avis ajouté : " . $last_review['comment'] . "<br>";
} else {
    echo "❌ Erreur : l'avis ne semble pas exister en base.<br>";
}

// ➜ Récupérer tous les avis
$reviews = $reviewModel->getAllReviews();
echo "<pre>";
print_r($reviews);
echo "</pre>";


// ➜ Mise à jour d'un avis
$updated = $reviewModel->updateReview(5, 4, 'Trajet agréable, je recommande !');
if ($updated) {
    echo "✅ Avis mis à jour avec succès !<br>";
} else {
    echo "❌ Erreur lors de la mise à jour de l'avis.<br>";
}

// ➜ Supprimer un avis
$deleted = $reviewModel->deleteReview(5);
if ($deleted) {
    echo "✅ Avis supprimé avec succès !<br>";
} else {
    echo "❌ Erreur lors de la suppression de l'avis.<br>";
}
?>