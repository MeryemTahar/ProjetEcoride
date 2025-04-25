<?php
// backend/controllers/UserController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

class UserController
{
    private $pdo;
    private $userModel;

    public function __construct($pdo)
    {
        $this->pdo       = $pdo;
        $this->userModel = new User($pdo);
    }

    /**
     * GET /users
     */
    public function index(): void
    {
        header('Content-Type: application/json');
        $users = $this->userModel->getAllUsers();
        echo json_encode($users);
    }

    /**
     * GET /users/{id}
     */
    public function show(int $id): void
    {
        header('Content-Type: application/json');
        $user = $this->userModel->getUserById($id);

        if ($user) {
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Utilisateur non trouvé']);
        }
    }

    /**
     * POST /users
     */
    public function store(): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        // Récupère et valide les champs nécessaires
        $required = ['firstname', 'lastname', 'email', 'password', 'phone'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                http_response_code(400);
                echo json_encode(['error' => "Le champ {$field} est requis."]);
                return;
            }
        }

        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $ok = $this->userModel->addUser(
            $data['firstname'],
            $data['lastname'],
            $data['email'],
            $hashedPassword,
            $data['phone']
        );

        if ($ok) {
            http_response_code(201);
            echo json_encode(['status' => 'success', 'message' => 'Utilisateur créé']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la création']);
        }
    }

    /**
     * PUT /users/{id}
     */
    public function update(int $id): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        // Vous pouvez ici vérifier quels champs sont présents et les valider
        $ok = $this->userModel->updateUser(
            $id,
            $data['firstname']  ?? null,
            $data['lastname']   ?? null,
            $data['email']      ?? null,
            isset($data['password']) 
                ? password_hash($data['password'], PASSWORD_BCRYPT) 
                : null,
            $data['phone']      ?? null
        );

        if ($ok) {
            echo json_encode(['status' => 'success', 'message' => 'Utilisateur mis à jour']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la mise à jour']);
        }
    }

    /**
     * DELETE /users/{id}
     */
    public function destroy(int $id): void
    {
        header('Content-Type: application/json');
        $ok = $this->userModel->deleteUser($id);

        if ($ok) {
            echo json_encode(['status' => 'success', 'message' => 'Utilisateur supprimé']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Erreur lors de la suppression']);
        }
    }
}
