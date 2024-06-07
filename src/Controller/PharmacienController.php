<?php

namespace App\Controller;

use App\Entity\pharmacie;
use App\Form\PharmacieType;
use App\Repository\PharmacienRepository;
use App\Repository\PharmacieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class PharmacienController extends AbstractController
{
    #[Route('/pharmacien/{id}', name: 'app_pharmacie')]
    public function list(PharmacieRepository $repo,PharmacienRepository $repopha,$id): Response
    {
        // Récupérer les données des pharmacies depuis la base de données
        $pharmacies = $repo->findAll();
        $pharmaciens = $repopha->findOneBy(['idPharmacie' => $pharmacies]);
        return $this->render('pharmacien/pharmacie.html.twig', [
            'pharmacies' => $pharmacies,
            'id' => $id,
        ]);
    }

    #[Route('/pharmacien/pha/ajouter', name: 'app_pharmacie_ajouter')]
    public function Add(Request $request): Response
    {
        $pharmacie = new Pharmacie();
        $form = $this->createForm(PharmacieType::class, $pharmacie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pharmacie);
            $entityManager->flush();
            return $this->redirectToRoute('app_pharmacie', ['id' => 2]);
        }
        return $this->render("/pharmacien/ajoutPharmacie.html.twig", ['form' => $form->createView(), 'id' => 2]);
    }

    #[Route('/pharmacien/modifier/{id}', name: 'app_pharmacie_modifier')]
    public function edit(PharmacieRepository $repository, PharmacienRepository $repo, $id, Request $request)
    {
        $pharmacie = $repository->find($id);
        $pharmac = $repo->findOneBy(['idPharmacie' => $pharmacie->getId()]);
        $idpha = $pharmac->getId();
        $form = $this->createForm(PharmacieType::class, $pharmacie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('app_pharmacie', ['id' => $idpha]); // Redirigez vers la route 'app_Affiche'
        }

        return $this->render("pharmacien/modifierPharmacie.html.twig", ['form' => $form->createView(), 'pharmacies' => $pharmacie, 'id' => $idpha]);
    }


    #[Route('/pharmdelete/{id}', name:'delete_pharm')]
    public function deleteLab($id, PharmacieRepository $repo, ManagerRegistry $manager)
    {
        $pharmacie = $repo->find($id);
        $pharmac = $repo->findOneBy(['id' => $pharmacie->getId()]);
        $idpha = $pharmac->getId();
        $en = $manager->getManager();
        $en->remove($pharmacie);
        $en->flush();

        // Rediriger l'utilisateur vers une autre page (par exemple, la liste des auteurs)
        return $this->redirectToRoute('app_pharmacie', ['id' => $idpha]);
    }


    #[Route('/pharmacien/info/{id}', name: 'app_pharmacie_info')]
    public function info(PharmacieRepository $repository, $id, Request $request,EntityManagerInterface $entityManager)
    {
        $pharmacie = $entityManager->getRepository(pharmacie::class)->find($id);
        $form = $this->createForm(PharmacieType::class, $pharmacie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('app_pharmacie'); // Redirigez vers la route 'app_Affiche'
        }

        return $this->render("pharmacien/infoPharmacie.html.twig", [ 'form' => $form->createView(),'pharmacies' => $pharmacie]);
    }
    #[Route('/pharmacien1', name: 'app_pharmacie1')]
    public function list1(PharmacieRepository $repo): Response
    {
        // Récupérer les données des pharmacies depuis la base de données
        $pharmacies = $repo->findAll();
        return $this->render('pharmacien/pharmacie1.html.twig', [
            'pharmacies' => $pharmacies,
        ]);
    }

    #[Route('/pharmacien1/maps', name: 'app_pharmacie1_maps')]
    public function maps(PharmacieRepository $repo): Response
    {
        // Récupérer les données des pharmacies depuis la base de données

        return $this->render('pharmacien/maps.html.twig', [

        ]);
    }

    #[Route('/pharmacienn/{id}', name: 'app_profil_pha')]
    public function index1($id): Response
    {
        return $this->render('pharmacien/profil.html.twig', [
            'id' => $id,
        ]);
    }


}