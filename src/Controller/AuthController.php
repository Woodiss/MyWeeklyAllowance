<?php

namespace Controller;

use App\Service\AuthService;
use App\Repository\ParentUserRepository;
use App\Repository\TeenRepository;

/**
 * Contrôleur d'authentification
 */
class AuthController extends AbstractController
{
    private AuthService $authService;

    public function __construct($twig)
    {
        parent::__construct($twig);
        $this->authService = new AuthService(new ParentUserRepository() , new TeenRepository());
    }

    /**
     * Page d'inscription
     */
    public function register(): void
    {
        $this->render('auth/register.html.twig', [
            'title' => 'Inscription d\'un parent',
            'user' => $this->user,
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
            $this->authService->startSession($user, "parent");

            // Rediriger vers le tableau de bord
            if ($_SESSION["user_role"] === "parent") {
                $this->redirect('/parent/dashboard');
            } elseif ($_SESSION["user_role"] === "teen") {
                $this->redirect('teen/wallet');
            }

        } catch (\InvalidArgumentException $e) {
            $this->render('auth/register.html.twig', [
                'title' => 'Inscription',
                'error' => $e->getMessage(),
                'user' => $this->user,
            ]);
        } catch (\RuntimeException $e) {
            $this->render('auth/register.html.twig', [
                'title' => 'Inscription',
                'error' => $e->getMessage(),
                'user' => $this->user,
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
            'user' => $this->user,
        ]);
    }

    /**
     * Traitement de la connexion
     */
    public function loginSubmit(): void
    {
        try {
            // Récupérer les données du formulaire
            $emailOrUsername = $_POST['email_or_username'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? '';

            // Validation
            if (empty($emailOrUsername) || empty($password)) {
                throw new \InvalidArgumentException("Email et mot de passe requis");
            }

            // Vérifier les identifiants
            $user = $this->authService->login($emailOrUsername, $password, $role);

            if ($user[0] === null) {
                throw new \RuntimeException("Email ou mot de passe incorrect");
            }
            // die(var_dump($user));
            // Démarrer la session
            $this->authService->startSession($user[0], $user[1]);

            // Rediriger vers le tableau de bord
            if ($_SESSION["user_role"] === "parent") {
                $this->redirect('/parent/dashboard');
            } elseif ($_SESSION["user_role"] === "teen") {
                $this->redirect('/teen/wallet');
            }

        } catch (\InvalidArgumentException $e) {
            $this->render('auth/login.html.twig', [
                'title' => 'Connexion',
                'error' => $e->getMessage(),
                'user' => $this->user,
            ]);
        } catch (\RuntimeException $e) {
            $this->render('auth/login.html.twig', [
                'title' => 'Connexion',
                'error' => $e->getMessage(),
                'user' => $this->user,
            ]);
        }
    }

    public function logout(): void
    {
        $this->authService->logout();
        $this->redirect('/login');
    }
}

