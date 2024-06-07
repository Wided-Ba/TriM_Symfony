<?php

namespace App\Controller;

use App\Entity\medicament;
use App\Form\MedicamentType;
use App\Repository\MedicamentRepository;
use App\Repository\PharmacienRepository;
use App\Repository\PharmacieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicamentController extends AbstractController
{
    #[Route('/pharmacien/medicament/{id}', name: 'app_medicament')]
    public function list($id, MedicamentRepository $repo, PharmacienRepository $repophar,PharmacieRepository $repoph): Response
    {
        // Récupérer les données des pharmacies depuis la base de données
        $pharmacien = $repophar->find($id);
        $pharmaci = $pharmacien->getIdPharmacie();
        $medicament = $repo->findAll();
        return $this->render('medicament/afficherMedicament.html.twig', [
            'medicaments' => $medicament,
            'pharmaciens' => $pharmacien,
            'pharmacie' => $pharmaci,
            'id'=> $id,
        ]);
    }

    #[Route('/medicament/ajouter/{id}', name: 'app_medicament_ajouter')]
    public function Add(Request $request, PharmacienRepository $repo,PharmacieRepository $repoph, $id): Response
    {
        $pharmci = $repoph->find($id);
        $pharmaciens = $repo->findOneBy(['idPharmacie' => $id]);
        $medicaments = new Medicament();
        $form = $this->createForm(MedicamentType::class, $medicaments);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($medicaments);
            $medicaments->setIdPharmacien($pharmaciens);
            $entityManager->flush();
            return $this->redirectToRoute('app_medicament', ['id' => $id]);
        }

        return $this->render("/medicament/ajouterMedicament.html.twig", ['form' => $form->createView()]);
    }

    #[Route('/medicament/modifier/{id}', name: 'app_medicament_modifier')]
    public function edit(MedicamentRepository $repository, $id, Request $request)
    {
        $medicament = $repository->find($id);
        $pharmacien = $medicament->getIdPharmacien();
        $pharmaci = $pharmacien->getIdPharmacie();
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('app_medicament', ['id' => $pharmaci->getId()]); // Redirigez vers la route 'app_Affiche'
        }
        return $this->render("medicament/modifierMedicament.html.twig", [ 'form' => $form->createView(),'medicaments' => $medicament]);
    }

    #[Route('/medicament/supprimer/{id}', name:'app_medicament_supprimer')]
    public function deletemedic(MedicamentRepository $repo,$id,ManagerRegistry $manager)
    {
        $medicament = $repo->find($id);
        $pharmacien = $medicament->getIdPharmacien();
        $pharmaci = $pharmacien->getIdPharmacie();
        $res=$manager->getManager();
        $res->remove($medicament);
        $res->flush();
        return $this->redirectToRoute('app_medicament',  ['id' => $pharmaci->getId()]);
    }

}
