<?php

namespace App\Entity;

/**
 * Entité Teen
 */
class Teen
{
    private int $age;
    private string $lastname;
    private string $firstname;
    private string $password;

    public function __construct(string $firstname, string $lastname, int $age, string $password = '')
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->age = $age;
        $this->password = $password;

        if ($age <= 0) {
            throw new \InvalidArgumentException("L'âge doit être positif");
        }

        if ($age > 18) {
            throw new \InvalidArgumentException("L'âge doit être inférieur à 18 ans");
        }

        $this->age = $age;
    }

    // Getters
    public function getAge(): int { return $this->age; }
    public function getName(): string { return $this->firstname; }
    public function getLastname(): ?string { return $this->lastname; }
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
