<?php

namespace App\Repository;

use PDO;
use Config\Database;
use App\Entity\Teen;

/**
 * Repository pour les adolescents
 */
class TeenRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Créer un nouvel adolescent
     */
    public function create(Teen $teen, int $parentId): int
    {
        $hashedPassword = password_hash($teen->getPassword(), PASSWORD_BCRYPT);

        // Insérer l'adolescent
        $sql = "INSERT INTO teen (firstname, lastname, username, age, password_hash, created_at) 
                VALUES (:firstname, :lastname, :username, :age, :password_hash, NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'firstname' => $teen->getName(),
            'lastname' => $teen->getLastname(),
            'username' => $teen->getUsername(),
            'age' => $teen->getAge(),
            'password_hash' => $hashedPassword
        ]);

        $teenId = (int) $this->db->lastInsertId();

        // Créer le compte bancaire lié
        $sqlBank = "INSERT INTO bank_account (parent_id, teen_id, balance, created_at, weekly_allowance) 
                    VALUES (:parent_id, :teen_id, 0.00, NOW(), 0.00)";
        
        $stmtBank = $this->db->prepare($sqlBank);
        $stmtBank->execute([
            'parent_id' => $parentId,
            'teen_id' => $teenId
        ]);

        return $teenId;
    }

    /**
     * Trouver un adolescent par ID
     */
    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM teen WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Trouver un adolescent par nom d'utilisateur
     */
    public function findByUsername(string $username): ?array
    {
        $sql = "SELECT * FROM teen WHERE username = :username LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Trouver tous les adolescents d'un parent
     */
    public function findByParentId(int $parentId): array
    {
        $sql = "SELECT t.*, ba.balance, ba.id as bank_account_id, ba.weekly_allowance
                FROM teen t
                INNER JOIN bank_account ba ON t.id = ba.teen_id
                WHERE ba.parent_id = :parent_id
                ORDER BY t.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['parent_id' => $parentId]);
        
        return $stmt->fetchAll();
    }

    public function verifyCredentials(string $username, string $hash_password): ?array
    {
        $user = $this->findByUsername($username);

        if ($user && password_verify($hash_password, $user['password_hash'])) {
            return $user;
        }

        return null;
    }

    /**
     * Récupérer un adolescent avec son solde
     */
    public function getTeenWithBalance(int $teenId): ?array
    {
        $sql = "SELECT t.*, ba.balance, ba.weekly_allowance
                FROM teen t
                INNER JOIN bank_account ba ON t.id = ba.teen_id
                WHERE t.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $teenId]);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }

}
