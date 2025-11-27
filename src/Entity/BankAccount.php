<?php

namespace App\Entity;

/**
 * Entité Compte Bancaire
 */
class BankAccount
{
    private ?int $id;
    private int $parentId;
    private int $teenId;
    private float $balance;
    private float $weeklyAllowance;
    private string $createdAt;

    public function __construct(int $parentId, int $teenId, float $balance = 0.0, float $weeklyAllowance = 0.0, ?int $id = null, ?string $createdAt = null)
    {
        $this->id = $id;
        $this->parentId = $parentId;
        $this->teenId = $teenId;
        $this->balance = $balance;
        $this->weeklyAllowance = $weeklyAllowance;
        $this->createdAt = $createdAt ?? date('Y-m-d H:i:s');
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getParentId(): int { return $this->parentId; }
    public function getTeenId(): int { return $this->teenId; }
    public function getBalance(): float { return $this->balance; }
    public function getWeeklyAllowance(): float { return $this->weeklyAllowance; }
    public function getCreatedAt(): string { return $this->createdAt; }

    // Setters
    public function setBalance(float $balance): void { $this->balance = $balance; }
    public function setWeeklyAllowance(float $weeklyAllowance): void { $this->weeklyAllowance = $weeklyAllowance; }
}
