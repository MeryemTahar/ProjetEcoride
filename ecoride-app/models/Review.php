<?php
require_once __DIR__ . '/../config/database.php';

class Review {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

  // Ajouter un avis
public function addReview($trip_id, $reviewer_id, $reviewed_user_id, $rating, $comment) {
    $stmt = $this->pdo->prepare("INSERT INTO reviews (trip_id, reviewer_id, reviewed_user_id, rating, comment) 
                                 VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$trip_id, $reviewer_id, $reviewed_user_id, $rating, $comment]);
}

    // Récupérer tous les avis
    public function getAllReviews() {
        $stmt = $this->pdo->prepare("SELECT * FROM reviews");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Récupérer un avis par son ID
    public function getReviewById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM reviews WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Modifier un avis
    public function updateReview($id, $rating, $comment) {
        $stmt = $this->pdo->prepare("UPDATE reviews SET rating = ?, comment = ? WHERE id = ?");
        return $stmt->execute([$rating, $comment, $id]);
    }

    // Supprimer un avis
    public function deleteReview($id) {
        $stmt = $this->pdo->prepare("DELETE FROM reviews WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
