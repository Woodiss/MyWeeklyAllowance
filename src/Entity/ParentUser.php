<?php

namespace App\Entity;

use DateTime;

/**
 * Entité ParentUser
 */
class ParentUser
{
    protected ?int $id;
    protected string $firstName;
    protected ?string $lastName;
    protected string $email;
    protected string $password;
    protected DateTime $createdAt;
    private array $managedTeens = [];

    public function __construct(string $firstName, string $lastName, string $email, string $password, ?int $id = null)
    {
        if ($id !== null) {
            $this->id = $id;
        }
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = new DateTime();
    }

    // Getters
    public function getManagedTeens(): array { return $this->managedTeens; }
    public function getId(): ?int { return $this->id; }
    public function getFirstName(): string { return $this->firstName; }
    public function getLastName(): ?string { return $this->lastName; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }
    public function getCreatedAt(): DateTime { return $this->createdAt; }

}
