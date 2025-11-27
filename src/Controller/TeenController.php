<?php

namespace Controller;

use App\Repository\TeenRepository;
use App\Repository\BankAccountRepository;

class TeenController extends AbstractController
{
    private TeenRepository $teenRepository;
    private BankAccountRepository $bankAccountRepository;

    public function __construct($twig)
    {
        parent::__construct($twig);
        $this->teenRepository = new TeenRepository();
        $this->bankAccountRepository = new BankAccountRepository();
    }

    /**
     * Afficher le portefeuille de l'adolescent
     */
    public function wallet(): void
    {
        // Vérifier si l'utilisateur est connecté et est un ado
        if (!$this->isTeen()) {
            $this->redirect('/login');
            return;
        }

        $teenId = $_SESSION['user_id'];
        
        // On utilise toujours getTeenWithBalance de TeenRepository car c'est une méthode de lecture pratique
        // qui fait une jointure. On pourrait la déplacer, mais elle concerne l'affichage du Teen.
        $teenData = $this->teenRepository->getTeenWithBalance($teenId);

        $this->render('teen/wallet.html.twig', [
            'title' => 'Mon Portefeuille',
            'user' => $this->user, // Contient les infos de session
            'balance' => $teenData['balance'],
            'weekly_allowance' => $teenData['weekly_allowance']
        ]);
    }

    /**
     * Enregistrer une dépense
     */
    public function recordExpense(): void
    {
        if (!$this->isTeen()) {
            $this->redirect('/login');
            return;
        }

        try {
            $amount = (float) ($_POST['amount'] ?? 0);
            $description = $_POST['description'] ?? '';

            if ($amount <= 0) {
                throw new \InvalidArgumentException("Le montant doit être positif");
            }

            $teenId = $_SESSION['user_id'];
            
            $this->bankAccountRepository->recordExpense($teenId, $amount);
            
            // On pourrait ajouter l'enregistrement de la transaction dans une table 'transactions' si elle existait.
            
            $this->redirect('/teen/wallet');

        } catch (\Exception $e) {
            // Récupérer les infos pour réafficher la page
            $teenId = $_SESSION['user_id'];
            $teenData = $this->teenRepository->getTeenWithBalance($teenId);
            
            $this->render('teen/wallet.html.twig', [
                'title' => 'Mon Portefeuille',
                'user' => $this->user,
                'balance' => $teenData['balance'],
                'weekly_allowance' => $teenData['weekly_allowance'],
                'error' => $e->getMessage()
            ]);
        }
    }

    private function isTeen(): bool
    {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'teen';
    }
}
