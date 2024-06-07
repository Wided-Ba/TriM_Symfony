<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PatientController extends AbstractController
{
    #[Route('/patient/profil', name: 'app_profil_pat')]
    public function index1(): Response
    {
        return $this->render('patient/profil.html.twig', [
            'controller_name' => 'PatientController',
        ]);
    }

}
