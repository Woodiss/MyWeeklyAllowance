<?php

namespace App\Entity;

/**
 * Entité Teen
 */
class Teen
{
    private ?int $id;
    private string $firstname;
    private string $lastname;
    private string $username;
    private int $age;
    private string $password;

    public function __construct(string $firstname, string $lastname, string $username, int $age, string $password, ?int $id = null)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->username = $username;
        $this->age = $age;
        $this->password = $password;

        if ($age <= 0) {
            throw new \InvalidArgumentException("L'âge doit être positif");
        }

        if ($age > 18) {
            throw new \InvalidArgumentException("L'âge doit être inférieur à 18 ans");
        }
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->firstname; }
    public function getLastname(): string { return $this->lastname; }
    public function getUsername(): string { return $this->username; }
    public function getAge(): int { return $this->age; }
    public function getPassword(): string { return $this->password; }

    // Setters
    public function setAge(int $age): void
    {
        if ($age <= 0) {
            throw new \InvalidArgumentException("L'âge doit être positif");
        }
        if ($age > 18) {
            throw new \InvalidArgumentException("L'âge doit être inférieur à 18 ans");
        }
        $this->age = $age;
    }
}
