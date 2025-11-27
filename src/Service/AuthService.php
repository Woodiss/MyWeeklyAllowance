<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\ParentUser;
use App\Entity\Teen;
use App\Repository\ParentUserRepository;
use App\Repository\TeenRepository;

/**
 * Service d'authentification
 */
class AuthService
{
    private ParentUserRepository $ParentUserRepository;
    private TeenRepository $TeenRepository;

    public function __construct(ParentUserRepository $ParentUserRepository, TeenRepository $TeenRepository)
    {
        $this->ParentUserRepository = $ParentUserRepository;
        $this->TeenRepository = $TeenRepository;
    }

    /**
     * Enregistrer un nouvel utilisateur
     */
    public function registerParent(string $firstName,string $lastName, string $email, string $password): int
    {
        // Vérifier si l'email existe déjà
        if ($this->ParentUserRepository->emailExists($email)) {
            throw new \RuntimeException("Cet email est déjà utilisé");
        }

        // Créer l'entité appropriée selon le rôle
        $user = new ParentUser($firstName, $lastName, $email, $password);

        // Enregistrer en base de données
        return $this->ParentUserRepository->create($user);
    }

    /**
     * Connecter un utilisateur
     */
    public function login(string $emailOrUsername, string $password, string $role): ?array
    {
        if ($role === "parent") {
            return [$this->ParentUserRepository->verifyCredentials($emailOrUsername, $password), "parent"];
        } else {
            return [$this->TeenRepository->verifyCredentials($emailOrUsername, $password), "teen"];
        }
    }

    /**
     * Démarrer une session utilisateur
     */
    public function startSession(array $user, string $role): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($role === "parent") {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['name'];
            $_SESSION['last_name'] = $user['lastname'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = "parent";
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['first_name'] = $user['firstname'];
            $_SESSION['last_name'] = $user['lastname'];
            $_SESSION['user_role'] = "teen";
        }
    }

    /**
     * Déconnecter un utilisateur
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();
    }

    /**
     * Vérifier si un utilisateur est connecté
     */
    public function isLoggedIn(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user_id']);
    }
}
