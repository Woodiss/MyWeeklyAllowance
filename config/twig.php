<?php

/**
 * Service Twig - Configuration du moteur de templates
 */

require_once __DIR__ . '/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');

$twig = new \Twig\Environment($loader, [
    'cache' => __DIR__ . '/../cache/twig',
    'debug' => true,
    'auto_reload' => true,
]);

// Ajouter l'extension debug pour le développement
$twig->addExtension(new \Twig\Extension\DebugExtension());

return $twig;
