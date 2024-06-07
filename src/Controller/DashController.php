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
use App\Repository\ChefLabRepository;
use App\Repository\InfirmierRepository;
use App\Repository\MedecinRepository;
use App\Repository\PatientRepository;
use App\Repository\PharmacienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class DashController extends AbstractController
{
    #[Route('/admin', name: 'dash', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('dash/dashAdmin.html.twig', [
            'controller_name' => 'DashController',
        ]);
    }

    #[Route('/admin/charts', name: 'app_chart', methods: ['GET'])]
    public function index2(): Response
    {
        return $this->render('dash/charts.html.twig', [
            'controller_name' => 'DashController',
        ]);
    }
//*********************************chef-lab**************************************************************************

    #[Route('/admin/cheflab/edit/{id}', name: 'editChef')]
    public function edit($id,Request $request, ManagerRegistry $manager,EntityManagerInterface $entityManager): Response
    {
        $chef = $entityManager->getRepository(chefLab::class)->find($id);
        $form=$this->createForm(ChefLabType::class,$chef);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $res=$manager->getManager();
            $res->flush();
            return $this->redirectToRoute('listChef');
        }
        return $this->render('dash/updateChef.html.twig', ['form' => $form->createView(),'chef'=>$chef]);
    }

    #[Route('/cheflab/delete/{id}', name: 'delete_chef')]
    public function delete(ChefLabRepository $repo,$id,ManagerRegistry $manager): Response{
        $med=$repo->find($id);
        $res=$manager->getManager();
        $res->remove($med);
        $res->flush();
        return $this->redirectToRoute('listChef');
    }

    #[Route('admin/listchef', name: 'listChef')]
    public function listChef(ChefLabRepository $repo): Response
    {
        $list=$repo->findAll();
        return $this->render('dash/tableChef.html.twig', [
            'list' => $list
        ]);
    }
    //******************************medecin**************************************************************************

    #[Route('/admin/med/edit/{id}', name: 'editMed')]
    public function editMED($id,Request $request, ManagerRegistry $manager,EntityManagerInterface $entityManager): Response
    {
        $med = $entityManager->getRepository(medecin::class)->find($id);
        $form=$this->createForm(MedecinType::class,$med);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $res=$manager->getManager();
            $res->flush();
            return $this->redirectToRoute('listMed');
        }
        return $this->render('dash/updateMed.html.twig', ['form' => $form->createView(),'med'=>$med]);
    }

    #[Route('/med/delete/{id}', name: 'delete_med')]
    public function deleteMED(MedecinRepository $repo,$id,ManagerRegistry $manager): Response{
        $med=$repo->find($id);
        $res=$manager->getManager();
        $res->remove($med);
        $res->flush();
        return $this->redirectToRoute('listMed');
    }

    #[Route('admin/listMed', name: 'listMed')]
    public function listMed(MedecinRepository $repo): Response
    {
        $list=$repo->findAll();
        return $this->render('dash/tableMed.html.twig', [
            'list' => $list
        ]);
    }
//*********************************patient*****************************************************************************
    #[Route('/admin/patient/edit/{id}', name: 'editPat')]
    public function editPatient($id,Request $request, ManagerRegistry $manager,EntityManagerInterface $entityManager): Response
    {
        $pat = $entityManager->getRepository(patient::class)->find($id);
        $form=$this->createForm(PatientType::class,$pat);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $res=$manager->getManager();
            $res->flush();
            return $this->redirectToRoute('listPat');
        }
        return $this->render('dash/updatePat.html.twig', ['form' => $form->createView(),'pat'=>$pat]);
    }

    #[Route('/patient/delete/{id}', name: 'delete_pat')]
    public function deletePatient(PatientRepository $repo,$id,ManagerRegistry $manager): Response{
        $med=$repo->find($id);
        $res=$manager->getManager();
        $res->remove($med);
        $res->flush();
        return $this->redirectToRoute('listPat');
    }

    #[Route('admin/listPat', name: 'listPat')]
    public function listPat(PatientRepository $repo): Response
    {
        $list=$repo->findAll();
        return $this->render('dash/tablePat.html.twig', [
            'list' => $list
        ]);
    }
    //*********************************Pharmacien***********************************************************************
    #[Route('/admin/pharmacien/edit/{id}', name: 'editPha')]
    public function editPhar($id,Request $request, ManagerRegistry $manager,EntityManagerInterface $entityManager): Response
    {
        $pha = $entityManager->getRepository(pharmacien::class)->find($id);
        $form=$this->createForm(PharmacienType::class,$pha);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $res=$manager->getManager();
            $res->flush();
            return $this->redirectToRoute('listPha');
        }
        return $this->render('dash/updatePha.html.twig', ['form' => $form->createView(),'pha'=>$pha]);
    }

    #[Route('/pharmacien/delete/{id}', name: 'delete_pha')]
    public function deletePhar(PharmacienRepository $repo,$id,ManagerRegistry $manager): Response{
        $med=$repo->find($id);
        $res=$manager->getManager();
        $res->remove($med);
        $res->flush();
        return $this->redirectToRoute('listPha');
    }

    #[Route('admin/listPha', name: 'listPha')]
    public function listPha(PharmacienRepository $repo): Response
    {
        $list=$repo->findAll();
        return $this->render('dash/tablePha.html.twig', [
            'list' => $list
        ]);
    }
    //**************************************infermiere****************************************************************
    #[Route('/admin/inf/edit/{id}', name: 'editInf')]
    public function editinf($id,Request $request, ManagerRegistry $manager,EntityManagerInterface $entityManager): Response
    {
        $inf = $entityManager->getRepository(infirmier::class)->find($id);
        $form=$this->createForm(InfirmierType::class,$inf);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $res=$manager->getManager();
            $res->flush();
            return $this->redirectToRoute('listInf');
        }
        return $this->render('dash/updateInf.html.twig', ['form' => $form->createView(),'inf'=>$inf]);
    }

    #[Route('/inf/delete/{id}', name: 'delete_inf')]
    public function deleteinf(InfirmierRepository $repo,$id,ManagerRegistry $manager): Response{
        $med=$repo->find($id);
        $res=$manager->getManager();
        $res->remove($med);
        $res->flush();
        return $this->redirectToRoute('listInf');
    }

    #[Route('admin/listInf', name: 'listInf')]
    public function listInf(InfirmierRepository $repo): Response
    {
        $list=$repo->findAll();
        return $this->render('dash/tableInf.html.twig', [
            'list' => $list
        ]);
    }
//*********************************bloque/débloque**********************************************************************
    #[Route('/admin/bloque/{email}', name: 'bloque')]
    public function bloquer($email,EntityManagerInterface $entityManager):Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Recherche dans l'entité Medecin
        $medecin = $entityManager->getRepository(medecin::class)->findOneBy(['email' => $email]);;
        if ($medecin) {
            $medecin->setIsBlocked(1);
            $entityManager->flush();
            return $this->redirectToRoute('listMed');
        }

        // Recherche dans l'entité Infirmier
        $infirmier = $entityManager->getRepository(infirmier::class)->findOneBy(['email' => $email]);
        if ($infirmier) {
            $infirmier->setIsBlocked(1);
            $entityManager->flush();
            return $this->redirectToRoute('listInf');

        }

        // Recherche dans l'entité Pharmacien
        $pharmacien = $entityManager->getRepository(pharmacien::class)->findOneBy(['email' => $email]);
        if ($pharmacien) {
            $pharmacien->setIsBlocked(1);
            $entityManager->flush();
            return $this->redirectToRoute('listPha');

        }

        // Recherche dans l'entité ChefLab
        $chefLab = $entityManager->getRepository(chefLab::class)->findOneBy(['email' => $email]);
        if ($chefLab) {
            $chefLab->setIsBlocked(1);
            $entityManager->flush();
            return $this->redirectToRoute('listChef');

        }

        $patient = $entityManager->getRepository(patient::class)->findOneBy(['email' => $email]);
        if ($patient) {
            $patient->setIsBlocked(1);
            $entityManager->flush();
            return $this->redirectToRoute('listPat');

        }
        return $this->render('/');
    }

    #[Route('/debloque/{email}', name: 'debloque')]
    public function debloquer($email):Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Recherche dans l'entité Medecin
        $medecin = $entityManager->getRepository(medecin::class)->findOneBy(['email' => $email]);
        if ($medecin) {
            $medecin->setIsBlocked(0);
            $entityManager->flush();
            return $this->redirectToRoute('listMed');

        }

        // Recherche dans l'entité Infirmier
        $infirmier = $entityManager->getRepository(infirmier::class)->findOneBy(['email' => $email]);
        if ($infirmier) {
            $infirmier->setIsBlocked(0);
            $entityManager->flush();
            return $this->redirectToRoute('listInf');

        }

        // Recherche dans l'entité Pharmacien
        $pharmacien = $entityManager->getRepository(pharmacien::class)->findOneBy(['email' => $email]);
        if ($pharmacien) {
            $pharmacien->setIsBlocked(0);
            $entityManager->flush();
            return $this->redirectToRoute('listPha');

        }

        // Recherche dans l'entité ChefLab
        $chefLab = $entityManager->getRepository(chefLab::class)->findOneBy(['email' => $email]);
        if ($chefLab) {
            $chefLab->setIsBlocked(0);
            $entityManager->flush();
            return $this->redirectToRoute('listChef');

        }

        $patient = $entityManager->getRepository(patient::class)->findOneBy(['email' => $email]);
        if ($patient) {
            $patient->setIsBlocked(0);
            $entityManager->flush();
            return $this->redirectToRoute('listPat');

        }
        return $this->render('/');

    }


}