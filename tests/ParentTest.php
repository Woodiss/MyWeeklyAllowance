<?php

namespace App\Tests\Entity; // Assure-toi que le namespace est correct selon ton dossier

use PHPUnit\Framework\TestCase;
use App\Entity\ParentUser;

class ParentTest extends TestCase
{
    public function testCreateParent()
    {
        $parent = new ParentUser('Jane', 'Doe', 'jane@example.com', 'password123');
        
        $this->assertEquals('Jane', $parent->getFirstName());
        $this->assertEquals('Doe', $parent->getLastName());
        $this->assertEquals('jane@example.com', $parent->getEmail());
        $this->assertEquals('password123', $parent->getPassword());
        $this->assertInstanceOf(\DateTime::class, $parent->getCreatedAt());
        $this->assertEmpty($parent->getManagedTeens());
        
        // Ajout : Vérifie que l'ID est bien null quand on ne le fournit pas
        $this->assertNull($parent->getId());
    }

    // Ajout : Ce test va couvrir les lignes manquantes du constructeur
    public function testCreateParentWithId()
    {
        $id = 42;
        $parent = new ParentUser('Mark', 'Twain', 'mark@example.com', 'password123', $id);

        $this->assertEquals($id, $parent->getId());
    }
}