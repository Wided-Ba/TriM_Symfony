<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    #[Route('/login', name: 'app_login', methods: ['GET'])]
    public function index2(): Response
    {
        return $this->render('dash/login.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/inscrire', name: 'app_inscrire', methods: ['GET'])]
    public function index3(): Response
    {
        return $this->render('dash/inscrire.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
