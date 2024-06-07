<?php

namespace App\Controller;

use App\Entity\maladie;
use App\Form\MaladieType;
use App\Repository\MaladieRepository;
use App\Repository\MedecinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MaladieController extends AbstractController
{
    #[Route('/maladies/{id}', name: 'maladies_list')]
    public function listMaladies (
        MaladieRepository $maladieRepository, $id
    ): Response
    {
        $mal=$maladieRepository->findAll();
        return $this->render('maladie/listmaladies.html.twig', [
            'maladies' => $mal,
            'id' => $id,
        ]);
    }
    #[Route('/addMaladie/{id}', name: 'add_maladie')]
    public function createMaladie(Request $request, $id, MaladieRepository $maladieRepo,MedecinRepository $medecinRepository)
    {
        $medecinId= $medecinRepository->find($id);
        $medId = $medecinId->getId();
        $maladie = new maladie();
        $form = $this->createForm(MaladieType::class, $maladie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $maladie->setIdMedecin($medecinId);
            $entityManager->persist($maladie);
            $entityManager->flush();
            // Redirect to a success page or any other desired action
            return $this->redirectToRoute('maladies_list', ['id' => $medId]);
        }

        return $this->render('maladie/add.html.twig', [
            'form' => $form->createView(),
            'medecinId' => $medecinId,
            'id' => $medecinId,

        ]);
    }

    #[Route('/editMaladie/{id}', name: 'edit_maladie')]
    public function editMaladie(
        Request $request,
        EntityManagerInterface $entityManager,
        maladie $maladie, MaladieRepository $repo,$id
    ): Response {
        $form = $this->createForm(MaladieType::class, $maladie);
        $form->handleRequest($request);
        $maladd = $repo->findOneBy(['id' => $maladie]);
        $maladie = $repo->find($id);
        // Récupérer l'ID du médecin associé à la maladie
        $idMed2 = $maladie->getIdMedecin()->getId();
        $idMed = $maladd->getIdMedecin();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('maladies_list', ['id' => $idMed2]);
        }

        return $this->render('maladie/editMal.html.twig', [
            'form' => $form->createView(),
            'mal' => $maladie,
            'medecinId' => $idMed       ]);
    }

    #[Route('/maladie/delete/{id}', name: 'delete_maladie')]
    public function deleteMaladie($id, MaladieRepository $maladieRepository, EntityManagerInterface $entityManager): Response
    {
        // Trouver l'entité de maladie par son identifiant
        $maladie = $maladieRepository->find($id);
        // Récupérer l'ID du médecin associé à la maladie
        $idMed = $maladie->getIdMedecin()->getId();

        // Supprimer la maladie de la base de données
        $entityManager->remove($maladie);
        $entityManager->flush();

        // Rediriger vers la liste des maladies avec l'ID du médecin
        return $this->redirectToRoute('maladies_list', ['id' => $idMed]);
    }
}