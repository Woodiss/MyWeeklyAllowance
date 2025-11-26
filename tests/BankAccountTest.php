<?php

use PHPUnit\Framework\TestCase;

class BankAccountTest extends TestCase
{
    /**
     * Test : Créer un compte bancaire pour un adolescent
     */
    public function testCreateBankAccountForTeen()
    {
        // ARRANGE
        $teen = new Teen("Alice", "alice@example.com", 15);
        $parent = new ParentUser("Jean", "jean@example.com");
        
        // ACT
        $account = new BankAccount($teen, $parent);
        
        // ASSERT
        $this->assertEquals($teen, $account->getOwner());
        $this->assertEquals($parent, $account->getManager());
    }

    /**
     * Test : Le solde initial d'un compte est 0€
     */
    public function testBankAccountStartsWithZeroBalance()
    {
        // ARRANGE
        $teen = new Teen("Bob", "bob@example.com", 14);
        $parent = new ParentUser("Marie", "marie@example.com");
        
        // ACT
        $account = new BankAccount($teen, $parent);
        
        // ASSERT
        $this->assertEquals(0.0, $account->getBalance());
    }

    // ========== 2. DÉPOSER DE L'ARGENT ==========

    /**
     * Test : Déposer de l'argent augmente le solde
     */
    public function testDepositIncreasesBalance()
    {
        // ARRANGE
        $teen = new Teen("Charlie", "charlie@example.com", 16);
        $parent = new ParentUser("Pierre", "pierre@example.com");
        $account = new BankAccount($teen, $parent);
        
        // ACT
        $account->deposit(50.0);
        
        // ASSERT
        $this->assertEquals(50.0, $account->getBalance());
    }

    /**
     * Test : Ne pas déposer un montant négatif
     */
    public function testCannotDepositNegativeAmount()
    {
        // ARRANGE
        $teen = new Teen("David", "david@example.com", 13);
        $parent = new ParentUser("Sophie", "sophie@example.com");
        $account = new BankAccount($teen, $parent);
        
        // ASSERT
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Le montant doit être positif");
        
        // ACT
        $account->deposit(-20.0);
    }

    /**
     * Test : Plusieurs dépôts s'accumulent
     */
    public function testMultipleDepositsAccumulate()
    {
        // ARRANGE
        $teen = new Teen("Eve", "eve@example.com", 17);
        $parent = new ParentUser("Luc", "luc@example.com");
        $account = new BankAccount($teen, $parent);
        
        // ACT
        $account->deposit(30.0);
        $account->deposit(20.0);
        
        // ASSERT
        $this->assertEquals(50.0, $account->getBalance());
    }

    // ========== 3. ENREGISTRER DES DÉPENSES ==========

    /**
     * Test : Retirer de l'argent diminue le solde
     */
    public function testWithdrawDecreasesBalance()
    {
        // ARRANGE
        $teen = new Teen("Frank", "frank@example.com", 15);
        $parent = new ParentUser("Claire", "claire@example.com");
        $account = new BankAccount($teen, $parent);
        $account->deposit(100.0);
        
        // ACT
        $account->withdraw(30.0, "Achat de jeu vidéo");
        
        // ASSERT
        $this->assertEquals(70.0, $account->getBalance());
    }

    /**
     * Test : Ne pas retirer plus que le solde disponible
     */
    public function testCannotWithdrawMoreThanBalance()
    {
        // ARRANGE
        $teen = new Teen("Grace", "grace@example.com", 14);
        $parent = new ParentUser("Thomas", "thomas@example.com");
        $account = new BankAccount($teen, $parent);
        $account->deposit(50.0);
        
        // ASSERT
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Solde insuffisant");
        
        // ACT
        $account->withdraw(100.0, "Tentative de retrait");
    }

    /**
     * Test : Un retrait nécessite une description
     */
    public function testWithdrawRequiresDescription()
    {
        // ARRANGE
        $teen = new Teen("Henry", "henry@example.com", 16);
        $parent = new ParentUser("Julie", "julie@example.com");
        $account = new BankAccount($teen, $parent);
        $account->deposit(50.0);
        
        // ASSERT
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("La description ne peut pas être vide");
        
        // ACT
        $account->withdraw(20.0, "");
    }

    // ========== 4. ALLOCATION HEBDOMADAIRE ==========

    /**
     * Test : Définir une allocation hebdomadaire
     */
    public function testCanSetWeeklyAllowance()
    {
        // ARRANGE
        $teen = new Teen("Iris", "iris@example.com", 15);
        $parent = new ParentUser("Marc", "marc@example.com");
        $account = new BankAccount($teen, $parent);
        
        // ACT
        $account->setWeeklyAllowance(10.0);
        
        // ASSERT
        $this->assertEquals(10.0, $account->getWeeklyAllowance());
    }

    /**
     * Test : Appliquer l'allocation hebdomadaire augmente le solde
     */
    public function testApplyWeeklyAllowanceIncreasesBalance()
    {
        // ARRANGE
        $teen = new Teen("Jack", "jack@example.com", 14);
        $parent = new ParentUser("Nathalie", "nathalie@example.com");
        $account = new BankAccount($teen, $parent);
        $account->setWeeklyAllowance(10.0);
        
        // ACT
        $account->applyWeeklyAllowance();
        
        // ASSERT
        $this->assertEquals(10.0, $account->getBalance());
    }

    /**
     * Test : Ne pas définir une allocation négative
     */
    public function testCannotSetNegativeWeeklyAllowance()
    {
        // ARRANGE
        $teen = new Teen("Kate", "kate@example.com", 13);
        $parent = new ParentUser("Olivier", "olivier@example.com");
        $account = new BankAccount($teen, $parent);
        
        // ASSERT
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("L'allocation doit être positive");
        
        // ACT
        $account->setWeeklyAllowance(-5.0);
    }

    /**
     * Test : Ne pas appliquer l'allocation si elle n'est pas définie
     */
    public function testCannotApplyAllowanceIfNotSet()
    {
        // ARRANGE
        $teen = new Teen("Leo", "leo@example.com", 17);
        $parent = new ParentUser("Sylvie", "sylvie@example.com");
        $account = new BankAccount($teen, $parent);
        
        // ASSERT
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Aucune allocation hebdomadaire n'est définie");
        
        // ACT
        $account->applyWeeklyAllowance();
    }
}
