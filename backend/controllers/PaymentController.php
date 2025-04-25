<?php
// backend/controllers/PaymentController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Payment.php';

class PaymentController
{
    private $pdo;
    private $paymentModel;

    public function __construct($pdo)
    {
        $this->pdo           = $pdo;
        $this->paymentModel  = new Payment($pdo);
    }

    /**
     * GET /payments
     * Liste tous les paiements
     */
    public function index(): void
    {
        header('Content-Type: application/json');
        $payments = $this->paymentModel->getAllPayments();
        echo json_encode($payments);
    }

    /**
     * GET /payments/{id}
     * Détaille un paiement
     */
    public function show(int $id): void
    {
        header('Content-Type: application/json');
        $payment = $this->paymentModel->getPaymentById($id);

        if ($payment) {
            echo json_encode($payment);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Paiement non trouvé']);
        }
    }

    /**
     * POST /payments
     * Crée un nouveau paiement
     */
    public function store(): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        // validation de base
        foreach (['booking_id','user_id','amount','payment_method'] as $field) {
            if (empty($data[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Le champ {$field} est requis"]);
                return;
            }
        }

        $status = $data['payment_status'] ?? 'pending';

        $ok = $this->paymentModel->addPayment(
            (int)$data['booking_id'],
            (int)$data['user_id'],
            (float)$data['amount'],
            $data['payment_method'],
            $status
        );

        if ($ok) {
            http_response_code(201);
            echo json_encode(['status'=>'success','message'=>'Paiement créé']);
        } else {
            http_response_code(500);
            echo json_encode(['status'=>'error','message'=>'Échec de la création']);
        }
    }

    /**
     * PUT /payments/{id}
     * Met à jour le statut d’un paiement
     */
    public function update(int $id): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['payment_status'])) {
            http_response_code(400);
            echo json_encode(['error'=>'Le champ payment_status est requis']);
            return;
        }

        $ok = $this->paymentModel->updatePaymentStatus($id, $data['payment_status']);

        if ($ok) {
            echo json_encode(['status'=>'success','message'=>'Statut mis à jour']);
        } else {
            http_response_code(500);
            echo json_encode(['status'=>'error','message'=>'Échec de la mise à jour']);
        }
    }

    /**
     * DELETE /payments/{id}
     * Supprime un paiement
     */
    public function destroy(int $id): void
    {
        header('Content-Type: application/json');
        $ok = $this->paymentModel->deletePayment($id);

        if ($ok) {
            echo json_encode(['status'=>'success','message'=>'Paiement supprimé']);
        } else {
            http_response_code(500);
            echo json_encode(['status'=>'error','message'=>'Échec de la suppression']);
        }
    }
}
