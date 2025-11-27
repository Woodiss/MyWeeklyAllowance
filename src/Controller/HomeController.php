<?php

namespace Controller;

/**
 * Contrôleur de la page d'accueil
 */
class HomeController extends AbstractController
{
    /**
     * Page d'accueil
     */
    public function index(): void
    {
        $this->render('home/index.html.twig', [
            'title' => 'MyWeeklyAllowance',
            'description' => 'Gestion d\'argent de poche pour adolescents',
            'user' => $this->user,
        ]);
    }

    /**
     * Page À propos
     */
    public function about(): void
    {
        $this->render('home/about.html.twig', [
            'title' => 'À propos',
            'user' => $this->user,
        ]);
    }
}
