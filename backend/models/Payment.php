<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';

class Payment {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Ajouter un paiement
    public function addPayment($booking_id, $user_id, $amount, $payment_method, $payment_status = 'pending') {
        // Requête préparée pour éviter les injections SQL
        $stmt = $this->pdo->prepare("
            INSERT INTO payments (booking_id, user_id, amount, payment_method, payment_status) 
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$booking_id, $user_id, $amount, $payment_method, $payment_status]);
    }

    // Récupérer tous les paiements
    public function getAllPayments() {
        $stmt = $this->pdo->prepare("SELECT * FROM payments");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Récupérer un paiement par ID
    public function getPaymentById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM payments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Mettre à jour le statut d'un paiement
    public function updatePaymentStatus($id, $status) {
        $stmt = $this->pdo->prepare("UPDATE payments SET payment_status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    // Supprimer un paiement
    public function deletePayment($id) {
        $stmt = $this->pdo->prepare("DELETE FROM payments WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
