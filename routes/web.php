<?php

/**
 * Définition des routes web
 */

use FastRoute\RouteCollector;

return function(RouteCollector $r) {
    // ========================================
    // ROUTES PUBLIQUES (pas d'authentification requise)
    // ========================================
    
    // Page d'accueil
    $r->addRoute('GET', '/', ['Controller\HomeController', 'index', null]);
    
    // Page À propos
    $r->addRoute('GET', '/about', ['Controller\HomeController', 'about', null]);

    // Page d'inscription
    $r->addRoute('GET', '/register', ['Controller\AuthController', 'register', null]);
    $r->addRoute('POST', '/register', ['Controller\AuthController', 'registerSubmit', null]);
    
    // Page de connexion
    $r->addRoute('GET', '/login', ['Controller\AuthController', 'login', null]);
    $r->addRoute('POST', '/login', ['Controller\AuthController', 'loginSubmit', null]);
    
    // ========================================
    // ROUTES AUTHENTIFIÉES (n'importe quel rôle)
    // ========================================
    
    // Dashboard général (accessible à tous les utilisateurs connectés)
    // $r->addRoute('GET', '/dashboard', ['Controller\DashboardController', 'index', 'auth']);
    
    // ========================================
    // ROUTES PARENT (rôle 'parent' requis)
    // ========================================
    
    // Dashboard parent
    $r->addRoute('GET', '/parent/dashboard', ['Controller\ParentController', 'dashboard', 'parent']);
    
    // Créer un adolescent
    $r->addRoute('GET', '/parent/teen/create', ['Controller\ParentController', 'createTeen', 'parent']);
    $r->addRoute('POST', '/parent/teen/create', ['Controller\ParentController', 'createTeenSubmit', 'parent']);
    
    // Modifier le solde d'un adolescent
    $r->addRoute('POST', '/parent/teen/{id:\d+}/update-balance', ['Controller\ParentController', 'updateBalanceSubmit', 'parent']);
    
    // Modifier l'argent de poche hebdomadaire
    $r->addRoute('POST', '/parent/teen/{id:\d+}/update-weekly-allowance', ['Controller\ParentController', 'updateWeeklyAllowanceSubmit', 'parent']);
};
