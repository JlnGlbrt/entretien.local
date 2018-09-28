<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ExceptionController
 * @package App\Controller
 */
class ExceptionController extends AbstractController
{
    /**
     * Méthode qui redirige toutes les exceptions vers la page d'accueil
     * Le paramétrage s'est fait dans config/packages/twig.yaml (exception_controller)
     * 
     * @return Response
     */
    public function redirectException(): Response
    {
        return $this->redirectToRoute('articles');
    }
}

