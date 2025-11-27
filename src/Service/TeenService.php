<?php

namespace App\Service;

use App\Entity\Teen;
use App\Repository\TeenRepository;

/**
 * Service pour la gestion des adolescents
 */
class TeenService
{
    private TeenRepository $teenRepository;

    public function __construct(TeenRepository $teenRepository)
    {
        $this->teenRepository = $teenRepository;
    }

    /**
     * Créer un nouvel adolescent
     */
    public function createTeen(string $firstName, string $lastName, int $age, string $password, int $parentId): int
    {
        // Créer l'entité Teen
        $teen = new Teen($firstName, $lastName, $age, $password);

        // Enregistrer en base de données
        return $this->teenRepository->create($teen, $parentId);
    }

}
