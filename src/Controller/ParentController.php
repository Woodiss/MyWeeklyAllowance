<?php

namespace Controller;

use App\Repository\ParentUserRepository;
use App\Repository\TeenRepository;
use App\Repository\BankAccountRepository;
use App\Entity\Teen;

/**
 * Contrôleur pour l'espace parent
 */
class ParentController extends AbstractController
{
    private ParentUserRepository $parentRepository;
    private TeenRepository $teenRepository;
    private BankAccountRepository $bankAccountRepository;

    public function __construct($twig, ?ParentUserRepository $parentRepo = null, ?TeenRepository $teenRepo = null, ?BankAccountRepository $bankRepo = null)
    {
        parent::__construct($twig);
        $this->parentRepository = $parentRepo ?? new ParentUserRepository();
        $this->teenRepository = $teenRepo ?? new TeenRepository();
        $this->bankAccountRepository = $bankRepo ?? new BankAccountRepository();
    }

    /**
     * Tableau de bord du parent
     */
    public function dashboard(): void
    {
        // Récupérer l'utilisateur connecté
        $parentId = $_SESSION['user_id'];
        $teens = $this->teenRepository->findByParentId($parentId);

        $this->render('parent/dashboard.html.twig', [
            'title' => 'Tableau de bord Parent',
            'user' => $this->user,
            'teens' => $teens
        ]);
    }

    /**
     * Page de création d'un adolescent
     */
    public function createTeen(): void
    {
        $this->render('parent/create-teen.html.twig', [
            'title' => 'Ajouter un adolescent',
            'user' => $this->user
        ]);
    }

    /**
     * Traitement de la création d'un adolescent
     */
    public function createTeenSubmit(): void
    {
        try {
            $firstname = $_POST['firstname'] ?? '';
            $lastname = $_POST['lastname'] ?? '';
            $username = $_POST['username'] ?? '';
            $age = (int) ($_POST['age'] ?? 0);
            $password = $_POST['password'] ?? '';
            
            // Validation basique
            if (empty($firstname) || empty($lastname) || empty($username) || empty($password)) {
                throw new \InvalidArgumentException("Tous les champs sont requis");
            }

            // Vérifier si le nom d'utilisateur existe déjà
            if ($this->teenRepository->findByUsername($username)) {
                throw new \RuntimeException("Ce nom d'utilisateur est déjà pris");
            }

            $teen = new Teen($firstname, $lastname, $username, $age, $password);
            
            $parentId = $_SESSION['user_id'];
            $this->teenRepository->create($teen, $parentId);

            $this->redirect('/parent/dashboard');

        } catch (\Exception $e) {
            $this->render('parent/create-teen.html.twig', [
                'title' => 'Ajouter un adolescent',
                'error' => $e->getMessage(),
                'user' => $this->user
            ]);
        }
    }

    /**
     * Mettre à jour le solde d'un adolescent
     */
    public function updateBalanceSubmit(int $id): void
    {
        try {
            $newBalance = (float) ($_POST['balance'] ?? 0);
            
            // Vérifier que l'ado appartient bien au parent connecté (sécurité)
            // TODO: Ajouter cette vérification
            
            $this->bankAccountRepository->updateBalance($id, $newBalance);
            
            $this->redirect('/parent/dashboard');
            
        } catch (\Exception $e) {
            // Gérer l'erreur
            $this->redirect('/parent/dashboard');
        }
    }

    /**
     * Mettre à jour l'argent de poche hebdomadaire
     */
    public function updateWeeklyAllowanceSubmit(int $id): void
    {
        try {
            $newAllowance = (float) ($_POST['weekly_allowance'] ?? 0);
            
            if ($newAllowance < 0) {
                throw new \InvalidArgumentException("Le montant ne peut pas être négatif");
            }
            
            $this->bankAccountRepository->updateWeeklyAllowance($id, $newAllowance);
            
            $this->redirect('/parent/dashboard');
            
        } catch (\Exception $e) {
            // Gérer l'erreur
            $this->redirect('/parent/dashboard');
        }
    }
}
