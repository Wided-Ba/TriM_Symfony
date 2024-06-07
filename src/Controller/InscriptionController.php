<?php

namespace App\Controller;

use App\Entity\chefLab;
use App\Entity\infirmier;
use App\Entity\medecin;
use App\Entity\patient;
use App\Entity\pharmacien;
use App\Form\ChefLabType;
use App\Form\InfirmierType;
use App\Form\MedecinType;
use App\Form\PatientType;
use App\Form\PharmacienType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    #[Route('/avantinscrire', name: 'app_avant_inscrire', methods: ['GET'])]
    public function index4(): Response
    {
        return $this->render('inscription/preinscrire.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/forgotPass', name: 'app_forgotPass', methods: ['GET'])]
    public function index3(): Response
    {
        return $this->render('inscription/forgot-password.html.twig', [
            'controller_name' => 'MedController',
        ]);
    }


    //*********************************chef-lab**************************************************************************

    #[Route('/cheflab/add', name: 'chef_add')]
    public function createChef(Request $request):Response
    {
        $chef = new chefLab();
        $form = $this->createForm(ChefLabType::class, $chef);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Les données du formulaire sont valides, vous pouvez les sauvegarder dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($chef);
            $chef->setRole('Chef laboratoire');
            $chef->setIsBlocked(0);
            $entityManager->flush();

            $this->addFlash('success', 'le compte a été enregistré avec succès.');

            // Redirigez l'utilisateur vers une autre page (par exemple, une liste d'auteurs)
            return $this->redirectToRoute('app_home');
        }
        return $this->render('inscription/chef_inscri.html.twig', [
            'form' => $form->createView(),
        ]);
    }
//******************************medecin**************************************************************************
    #[Route('/med/add', name: 'add')]
    public function createMed(Request $request):Response
    {
        $med = new medecin();
        $form = $this->createForm(MedecinType::class, $med);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Les données du formulaire sont valides, vous pouvez les sauvegarder dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($med);
            $med->setRole('Medecin');
            $med->setIsBlocked(0);
            $entityManager->flush();

            $this->addFlash('success', 'le compte a été enregistré avec succès.');

            // Redirigez l'utilisateur vers une autre page (par exemple, une liste d'auteurs)
            return $this->redirectToRoute('app_home');
        }
        return $this->render('inscription/med_inscri.html.twig', [
            'form' => $form->createView(),
        ]);
    }
//*********************************patient*****************************************************************************
    #[Route('/patient/add', name: 'patient_add')]
    public function createPat(Request $request):Response
    {
        $pat = new patient();
        $form = $this->createForm(PatientType::class, $pat);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Les données du formulaire sont valides, vous pouvez les sauvegarder dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pat);
            $pat->setRole('Patient');
            $pat->setIsBlocked(0);
            $entityManager->flush();

            $this->addFlash('success', 'le compte a été enregistré avec succès.');

            // Redirigez l'utilisateur vers une autre page (par exemple, une liste d'auteurs)
            return $this->redirectToRoute('app_home');
        }

        return $this->render('inscription/patient_inscri.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    //*********************************Pharmacien***********************************************************************
    #[Route('/pharmacien/add', name: 'pharmacien_add')]
    public function createPha(Request $request):Response
    {
        $phar = new pharmacien();
        $form = $this->createForm(PharmacienType::class, $phar);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Les données du formulaire sont valides, vous pouvez les sauvegarder dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($phar);
            $phar->setRole('Pharmacien');
            $phar->setIsBlocked(0);
            $entityManager->flush();

            $this->addFlash('success', 'le compte a été enregistré avec succès.');

            // Redirigez l'utilisateur vers une autre page (par exemple, une liste d'auteurs)
            return $this->redirectToRoute('app_home');
        }

        return $this->render('inscription/pharmacien_inscri.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //**************************************infermiere****************************************************************

    #[Route('/inf/add', name: 'inf_add')]
    public function createInf(Request $request):Response
    {
        $inf = new infirmier();
        $form = $this->createForm(InfirmierType::class, $inf);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Les données du formulaire sont valides, vous pouvez les sauvegarder dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($inf);
            $inf->setRole('Infermier');
            $inf->setIsBlocked(0);
            $entityManager->flush();

            $this->addFlash('success', 'le compte a été enregistré avec succès.');

            // Redirigez l'utilisateur vers une autre page (par exemple, une liste d'auteurs)
            return $this->redirectToRoute('app_home');
        }

        return $this->render('inscription/infirmier_inscri.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
