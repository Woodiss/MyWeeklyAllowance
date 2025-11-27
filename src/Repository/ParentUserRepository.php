<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\ParentUser;
use App\Entity\Teen;
use Config\Database;
use PDO;

/**
 * Repository pour les utilisateurs
 */
class ParentUserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function create(ParentUser $parentUser): int
    {
        $hashedPassword = password_hash($parentUser->getPassword(), PASSWORD_BCRYPT);

        $sql = "INSERT INTO parent (name, lastname, email, password_hash, created_at) 
                VALUES (:name, :lastname, :email, :password_hash, NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name' => $parentUser->getFirstName(),
            'lastname'=> $parentUser->getLastName(),
            'email' => $parentUser->getEmail(),
            'password_hash' => $hashedPassword
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Trouver un utilisateur par email
     */
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM parent WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);

        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Trouver un utilisateur par ID
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM parent WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Vérifier si un email existe déjà
     */
    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    /**
     * Vérifier les identifiants de connexion
     */
    public function verifyCredentials(string $email, string $hash_password): ?array
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($hash_password, $user['password_hash'])) {
            return $user;
        }

        return null;
    }
}
