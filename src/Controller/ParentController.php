<?php

namespace Controller;

use App\Service\TeenService;
use App\Repository\TeenRepository;
use App\Repository\ParentUserRepository;

/**
 * Contrôleur pour les fonctionnalités parent
 */
class ParentController extends AbstractController
{
    private TeenService $teenService;
    private TeenRepository $teenRepository;

    public function __construct($twig)
    {
        parent::__construct($twig);
        $this->teenRepository = new TeenRepository();
        $this->teenService = new TeenService($this->teenRepository);
    }

    /**
     * Dashboard parent
     */
    public function dashboard(): void
    {
        $parentId = $_SESSION['user_id'];
        
        // Récupérer tous les adolescents gérés par ce parent
        $teens = $this->teenRepository->findByParentId($parentId);

        $this->render('parent/dashboard.html.twig', [
            'title' => 'Tableau de bord Parent',
            'teens' => $teens
        ]);
    }

    /**
     * Page pour créer/inscrire un adolescent
     */
    public function createTeen(): void
    {
        $this->render('parent/create-teen.html.twig', [
            'title' => 'Inscrire un adolescent'
        ]);
    }

    /**
     * Traitement de l'inscription d'un adolescent
     */
    public function createTeenSubmit(): void
    {
        try {
            $parentId = $_SESSION['user_id'];
            
            // Récupérer les données du formulaire
            $firstName = $_POST['firstName'] ?? '';
            $lastName = $_POST['lastName'] ?? '';
            $age = isset($_POST['age']) ? (int)$_POST['age'] : 0;
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';

            // Validation
            if (empty($firstName) || empty($lastName) || empty($age) || empty($password)) {
                throw new \InvalidArgumentException("Tous les champs sont requis");
            }

            if ($password !== $passwordConfirm) {
                throw new \InvalidArgumentException("Les mots de passe ne correspondent pas");
            }

            if (strlen($password) < 6) {
                throw new \InvalidArgumentException("Le mot de passe doit contenir au moins 6 caractères");
            }

            if ($age < 10 || $age > 18) {
                throw new \InvalidArgumentException("L'âge doit être entre 10 et 18 ans");
            }

            // Créer l'adolescent
            $teenId = $this->teenService->createTeen($firstName, $lastName, $age, $password, $parentId);

            // Rediriger vers le dashboard avec message de succès
            $_SESSION['success_message'] = "L'adolescent {$firstName} {$lastName} a été créé avec succès !";
            $this->redirect('/parent/dashboard');

        } catch (\InvalidArgumentException $e) {
            $this->render('parent/create-teen.html.twig', [
                'title' => 'Inscrire un adolescent',
                'error' => $e->getMessage(),
                'firstName' => $_POST['firstName'] ?? '',
                'lastName' => $_POST['lastName'] ?? '',
                'age' => $_POST['age'] ?? '',
            ]);
        } catch (\RuntimeException $e) {
            $this->render('parent/create-teen.html.twig', [
                'title' => 'Inscrire un adolescent',
                'error' => $e->getMessage(),
            ]);
        }
    }


}
