<?php

namespace App\Tests\Entity; // Assure-toi que le namespace est correct

use PHPUnit\Framework\TestCase;
use App\Entity\Teen;
use InvalidArgumentException; // Utiliser le FQCN ou importer

class TeenTest extends TestCase
{
    public function testCreateTeen()
    {
        // ... (Ton test existant)
        $teen = new Teen('John', 'Doe', 'johndoe', 15, 'password123');
        
        $this->assertEquals('John', $teen->getName());
        $this->assertEquals('Doe', $teen->getLastname());
        $this->assertEquals('johndoe', $teen->getUsername());
        $this->assertEquals(15, $teen->getAge());
        $this->assertEquals('password123', $teen->getPassword());
        
        // Ajout : Vérification que l'ID est null par défaut
        $this->assertNull($teen->getId()); 
    }

    public function testCreateTeenWithId()
    {
        // Ce test couvre la ligne manquante dans le constructeur ($this->id = $id)
        $expectedId = 99;
        $teen = new Teen('Jane', 'Smith', 'janesmith', 17, 'securepass', $expectedId);
        
        // Ce test couvre le getter getId() qui était manquant
        $this->assertEquals($expectedId, $teen->getId());
    }

    public function testTeenAgeTooYoung()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Teen('Baby', 'Doe', 'baby', 0, 'pass');
    }

    public function testTeenAgeTooOld()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Teen('Adult', 'Doe', 'adult', 19, 'pass');
    }

    public function testSetAgeValid() // Renommage pour être plus clair
    {
        $teen = new Teen('John', 'Doe', 'johndoe', 15, 'password123');
        $teen->setAge(16);
        $this->assertEquals(16, $teen->getAge());
        
        // Ajout : Test d'une valeur limite (18 ans)
        $teen->setAge(18);
        $this->assertEquals(18, $teen->getAge());
    }

    public function testSetAgeTooYoung()
    {
        $teen = new Teen('John', 'Doe', 'johndoe', 15, 'password123');
        $this->expectException(\InvalidArgumentException::class);
        $teen->setAge(0);
    }
    
    public function testSetAgeTooOld() // Renommage pour être plus clair
    {
        $teen = new Teen('John', 'Doe', 'johndoe', 15, 'password123');
        $this->expectException(\InvalidArgumentException::class);
        $teen->setAge(20);
    }
}