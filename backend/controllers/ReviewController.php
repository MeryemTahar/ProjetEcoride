<?php
// backend/controllers/ReviewController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Review.php';

class ReviewController
{
    private $pdo;
    private $reviewModel;

    public function __construct($pdo)
    {
        $this->pdo         = $pdo;
        $this->reviewModel = new Review($pdo);
    }

    /**
     * GET /reviews
     * Liste tous les avis
     */
    public function index(): void
    {
        header('Content-Type: application/json');
        $reviews = $this->reviewModel->getAllReviews();
        echo json_encode($reviews);
    }

    /**
     * GET /reviews/{id}
     * Détaille un avis
     */
    public function show(int $id): void
    {
        header('Content-Type: application/json');
        $review = $this->reviewModel->getReviewById($id);

        if ($review) {
            echo json_encode($review);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Avis non trouvé']);
        }
    }

    /**
     * POST /reviews
     * Crée un nouvel avis
     */
    public function store(): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        // validation rapide
        foreach (['trip_id','reviewer_id','reviewed_user_id','rating','comment'] as $field) {
            if (empty($data[$field]) && $data[$field] !== '0') {
                http_response_code(400);
                echo json_encode(['error' => "Le champ {$field} est requis"]);
                return;
            }
        }

        $ok = $this->reviewModel->addReview(
            (int)$data['trip_id'],
            (int)$data['reviewer_id'],
            (int)$data['reviewed_user_id'],
            (int)$data['rating'],
            $data['comment']
        );

        if ($ok) {
            http_response_code(201);
            echo json_encode(['status'=>'success','message'=>'Avis créé']);
        } else {
            http_response_code(500);
            echo json_encode(['status'=>'error','message'=>'Échec de la création']);
        }
    }

    /**
     * PUT /reviews/{id}
     * Met à jour un avis existant
     */
    public function update(int $id): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['rating']) || !isset($data['comment'])) {
            http_response_code(400);
            echo json_encode(['error'=>'Les champs rating et comment sont requis']);
            return;
        }

        $ok = $this->reviewModel->updateReview(
            $id,
            (int)$data['rating'],
            $data['comment']
        );

        if ($ok) {
            echo json_encode(['status'=>'success','message'=>'Avis mis à jour']);
        } else {
            http_response_code(500);
            echo json_encode(['status'=>'error','message'=>'Échec de la mise à jour']);
        }
    }

    /**
     * DELETE /reviews/{id}
     * Supprime un avis
     */
    public function destroy(int $id): void
    {
        header('Content-Type: application/json');
        $ok = $this->reviewModel->deleteReview($id);

        if ($ok) {
            echo json_encode(['status'=>'success','message'=>'Avis supprimé']);
        } else {
            http_response_code(500);
            echo json_encode(['status'=>'error','message'=>'Échec de la suppression']);
        }
    }
}
