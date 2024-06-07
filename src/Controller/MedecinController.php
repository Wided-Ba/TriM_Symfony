<?php

namespace App\Controller;

use App\Entity\medecin;
use App\Form\MedecinType;
use App\Repository\MedecinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedecinController extends AbstractController
{

    #[Route('/medecin/profil/{id}', name: 'app_profil_med')]
    public function index1($id): Response
    {
        return $this->render('medecin/profil.html.twig',['id' => $id]);
    }

    //*********************************Recuperer email****************************************************************************

    // Exemple de contrôleur Symfony pour récupérer un utilisateur par e-mail
    #[Route('/get-user-by-email/{email}', name: 'email')]
    public function getUserByEmail(Request $request, MedecinRepository $userRepository): Response
    {
        $email = $request->get('email');
        $user = $userRepository->findOneBy(['email' => $email]);

        // Retourner les informations de l'utilisateur au format JSON
        return $this->json($user);
    }
    //*********************************edit****************************************************************************

    #[Route('/medecin/edit/{id}', name: 'editmed')]
    public function edit($id,Request $request, ManagerRegistry $manager,EntityManagerInterface $entityManager):Response
    {
        $chef = $entityManager->getRepository(medecin::class)->find($id);

        $form = $this->createForm(MedecinType::class, $chef);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('editmed', ['id' => $id]);
        }

        // Vous pouvez également renvoyer les données de l'entité chefLab au format JSON si nécessaire
        $data = [
            'id' => $chef->getId(),
            'nom' => $chef->getNom(),
            'prenom' => $chef->getPrenom(),
            'email' => $chef->getEmail(),
            'password' => $chef->getPassword(),
            'genre' => $chef->getGenre(),
            'ntel' => $chef->getNtel(),
            'addresse' => $chef->getAddresse(),
            'hdebut' => $chef->getHdebut(),
            'hfin' => $chef->getHfin(),
        ];

        // Renvoyer une réponse JSON si la requête est AJAX
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse($data);
        }

        return $this->render('medecin/update.html.twig', ['form' => $form->createView(), 'chef' => $chef,$this->json($data)]);

    }

}