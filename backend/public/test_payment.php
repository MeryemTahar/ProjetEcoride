<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Payment.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);

$paymentModel = new Payment($pdo);

// Ajouter un paiement
echo "<h3>Test d'ajout de paiement</h3>";
$added = $paymentModel->addPayment(6, 2, 50.00, 'card');
if ($added) {
    echo "✅ Paiement ajouté avec succès !<br>";
} else {
    echo "❌ Erreur lors de l'ajout du paiement.<br>";
}

// Récupérer tous les paiements
echo "<h3>Test récupération de tous les paiements</h3>";
$payments = $paymentModel->getAllPayments();
echo "<pre>";
print_r($payments);
echo "</pre>";

// Récupérer un paiement par ID
echo "<h3>Test récupération d'un paiement</h3>";
$payment = $paymentModel->getPaymentById(5);
if ($payment) {
    echo "✅ Paiement trouvé : " . $payment['amount'] . "€ via " . $payment['payment_method'] . "<br>";
} else {
    echo "❌ Aucun paiement trouvé.<br>";
}

// Modifier un paiement
echo "<h3>Test de mise à jour du statut de paiement</h3>";
$updated = $paymentModel->updatePaymentStatus(1, 'completed');
if ($updated) {
    echo "✅ Paiement mis à jour avec succès !<br>";
} else {
    echo "❌ Erreur lors de la mise à jour du paiement.<br>";
}

// Supprimer un paiement
echo "<h3>Test de suppression d'un paiement</h3>";
$deleted = $paymentModel->deletePayment(1);
if ($deleted) {
    echo "✅ Paiement supprimé avec succès !<br>";
} else {
    echo "❌ Erreur lors de la suppression du paiement.<br>";
}
?>
