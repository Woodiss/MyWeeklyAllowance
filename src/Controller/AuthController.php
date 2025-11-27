<?php

namespace Controller;

use App\Service\AuthService;
use App\Repository\ParentUserRepository;

/**
 * Contrôleur d'authentification
 */
class AuthController extends AbstractController
{
    private AuthService $authService;

    public function __construct($twig)
    {
        parent::__construct($twig);
        $this->authService = new AuthService(new ParentUserRepository());
    }

    /**
     * Page d'inscription
     */
    public function register(): void
    {
        $this->render('auth/register.html.twig', [
            'title' => 'Inscription',
        ]);
    }

    /**
     * Traitement de l'inscription
     */
    public function registerSubmit(): void
    {
        try {
            // Récupérer les données du formulaire
            $name = $_POST['name'] ?? '';
            $lastName = $_POST['lastName'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            
            // Validation
            if (empty($name) || empty($lastName) || empty($email) || empty($password) || empty($passwordConfirm)) {
                throw new \InvalidArgumentException("Tous les champs sont requis");
            }
            
            if ($password !== $passwordConfirm) {
                throw new \InvalidArgumentException("Les mots de passe ne correspondent pas");
            }
            
            if (strlen($password) < 6) {
                throw new \InvalidArgumentException("Le mot de passe doit contenir au moins 6 caractères");
            }
            
            // Enregistrer l'utilisateur
            $userId = $this->authService->registerParent($name, $lastName, $email, $password);

            // Connecter automatiquement l'utilisateur
            $user = (new ParentUserRepository())->findById($userId);
            $this->authService->startSession($user);

            // Rediriger vers le tableau de bord
            $this->redirect('/dashboard');

        } catch (\InvalidArgumentException $e) {
            $this->render('auth/register.html.twig', [
                'title' => 'Inscription',
                'error' => $e->getMessage(),
            ]);
        } catch (\RuntimeException $e) {
            $this->render('auth/register.html.twig', [
                'title' => 'Inscription',
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Page de connexion
     */
    public function login(): void
    {
        $this->render('auth/login.html.twig', [
            'title' => 'Connexion',
        ]);
    }

    /**
     * Traitement de la connexion
     */
    public function loginSubmit(): void
    {
        try {
            // Récupérer les données du formulaire
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validation
            if (empty($email) || empty($password)) {
                throw new \InvalidArgumentException("Email et mot de passe requis");
            }

            // Vérifier les identifiants
            $user = $this->authService->login($email, $password);

            if ($user === null) {
                throw new \RuntimeException("Email ou mot de passe incorrect");
            }

            // Démarrer la session
            $this->authService->startSession($user);

            // Rediriger vers le tableau de bord
            $this->redirect('/dashboard');

        } catch (\InvalidArgumentException $e) {
            $this->render('auth/login.html.twig', [
                'title' => 'Connexion',
                'error' => $e->getMessage(),
            ]);
        } catch (\RuntimeException $e) {
            $this->render('auth/login.html.twig', [
                'title' => 'Connexion',
                'error' => $e->getMessage(),
            ]);
        }
    }
}

