<?php

/**
 * Point d'entrée de l'application
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Démarrer la session
session_start();

// Charger le service Twig
$twig = require __DIR__ . '/../config/twig.php';

// Créer le dispatcher FastRoute
$dispatcher = FastRoute\simpleDispatcher(require __DIR__ . '/../routes/web.php');

// Récupérer la méthode HTTP et l'URI
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Supprimer les query strings de l'URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

// Router la requête
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo $twig->render('errors/404.html.twig');
        break;
        
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo $twig->render('errors/405.html.twig');
        break;
        
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        
        // Extraire le contrôleur, la méthode et le rôle requis
        $controllerClass = $handler[0];
        $method = $handler[1];
        $requiredRole = $handler[2] ?? null; // null = route publique
        
        // Vérifier les permissions
        if ($requiredRole !== null) {
            // Si 'auth' : juste vérifier que l'utilisateur est connecté
            if ($requiredRole === 'auth') {
                if (!isset($_SESSION['user_id'])) {
                    header('Location: /login');
                    exit;
                }
            }
            // Sinon : vérifier le rôle spécifique
            else {
                // Vérifier que l'utilisateur est connecté
                if (!isset($_SESSION['user_id'])) {
                    header('Location: /login');
                    exit;
                }
                
                // Vérifier que l'utilisateur a le bon rôle
                if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $requiredRole) {
                    http_response_code(403);
                    echo $twig->render('errors/403.html.twig');
                    exit;
                }
            }
        }
        
        // Instancier le contrôleur et appeler la méthode
        $controller = new $controllerClass($twig);
        $controller->$method(...array_values($vars));
        break;
}
