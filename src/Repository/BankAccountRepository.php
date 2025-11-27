<?php

namespace App\Repository;

use PDO;
use Config\Database;
use App\Entity\BankAccount;

/**
 * Repository pour les comptes bancaires
 */
class BankAccountRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Trouver un compte par ID de l'adolescent
     */
    public function findByTeenId(int $teenId): ?array
    {
        $sql = "SELECT * FROM bank_account WHERE teen_id = :teen_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['teen_id' => $teenId]);
        
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Mettre à jour le solde
     */
    public function updateBalance(int $teenId, float $newBalance): bool
    {
        $sql = "UPDATE bank_account 
                SET balance = :balance 
                WHERE teen_id = :teen_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'balance' => $newBalance,
            'teen_id' => $teenId
        ]);
    }

    /**
     * Mettre à jour l'argent de poche hebdomadaire
     */
    public function updateWeeklyAllowance(int $teenId, float $newWeeklyAllowance): bool
    {
        $sql = "UPDATE bank_account 
                SET weekly_allowance = :weekly_allowance 
                WHERE teen_id = :teen_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'weekly_allowance' => $newWeeklyAllowance,
            'teen_id' => $teenId
        ]);
    }

    /**
     * Enregistrer une dépense
     */
    public function recordExpense(int $teenId, float $amount): bool
    {
        // Vérifier d'abord si le solde est suffisant
        $sqlCheck = "SELECT balance FROM bank_account WHERE teen_id = :teen_id";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->execute(['teen_id' => $teenId]);
        $currentBalance = $stmtCheck->fetchColumn();

        if ($currentBalance < $amount) {
            throw new \RuntimeException("Solde insuffisant");
        }

        $sql = "UPDATE bank_account 
                SET balance = balance - :amount 
                WHERE teen_id = :teen_id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'amount' => $amount,
            'teen_id' => $teenId
        ]);
    }
}
