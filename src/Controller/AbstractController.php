<?php

namespace Controller;

use Twig\Environment;

/**
 * Contrôleur abstrait de base
 * Fournit les fonctionnalités communes à tous les contrôleurs
 */
abstract class AbstractController
{
    protected Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Rendre une vue Twig
     */
    protected function render(string $template, array $data = []): void
    {
        echo $this->twig->render($template, $data);
    }

    /**
     * Retourner une réponse JSON
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * Rediriger vers une URL
     */
    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}
