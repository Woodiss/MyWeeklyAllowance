<?php

namespace App\Tests\Entity; // N'oublie pas le namespace si tes tests sont dans tests/Entity

use PHPUnit\Framework\TestCase;
use App\Entity\BankAccount;

class BankAccountTest extends TestCase
{
    public function testCreateBankAccount()
    {
        // Teste les valeurs par défaut (id null, date automatique)
        $account = new BankAccount(1, 2, 100.0, 20.0);
        
        $this->assertEquals(1, $account->getParentId());
        $this->assertEquals(2, $account->getTeenId());
        $this->assertEquals(100.0, $account->getBalance());
        $this->assertEquals(20.0, $account->getWeeklyAllowance());
        $this->assertNotNull($account->getCreatedAt());
        
        // On vérifie aussi que l'ID est null par défaut (bon pour la rigueur)
        $this->assertNull($account->getId());
    }

    // NOUVEAU TEST AJOUTÉ
    public function testCreateBankAccountWithAllParameters()
    {
        $id = 42;
        $customDate = '2024-01-01 12:00:00';
        
        // On remplit tous les arguments du constructeur
        $account = new BankAccount(1, 2, 50.0, 10.0, $id, $customDate);

        // Cela force le code à passer dans le return $this->id;
        $this->assertEquals($id, $account->getId());
        
        // Cela vérifie que la logique "$createdAt ?? date(...)" prend bien la valeur fournie
        $this->assertEquals($customDate, $account->getCreatedAt());
    }

    public function testSetBalance()
    {
        $account = new BankAccount(1, 2);
        $account->setBalance(50.0);
        $this->assertEquals(50.0, $account->getBalance());
    }

    public function testSetWeeklyAllowance()
    {
        $account = new BankAccount(1, 2);
        $account->setWeeklyAllowance(15.0);
        $this->assertEquals(15.0, $account->getWeeklyAllowance());
    }
}