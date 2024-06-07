<?php

namespace App\Controller;
use App\Entity\patient;
use App\Entity\rendezVous;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RendezVousController extends AbstractController
{
    #[Route('/rendez/vous', name:'app_rendez-vous2')]
    public function createReclamation(Request $request,ManagerRegistry $manager)
    {
        $specialite = $request->request->get('fieldName');
        $em = $manager->getManager();
        $medecins = $em->createQuery('SELECT m FROM App\Entity\medecin m WHERE m.specialite = :specialite')
            ->setParameter('specialite', $specialite)
            ->getResult();

        $rec= new rendezVous();
        $rec->setStatus("en attente");
        $form = $this->createForm(RendezVousType::class, $rec);

        $form->handleRequest($request);
        $patient = $this->getDoctrine()->getRepository(Patient::class)->find(1);

        // Définissez directement l'entité patient dans votre entité VotreEntity
        $rec->setIdPatients($patient);
        $specialite = $request->request->get('fieldName');

        // Assurez-vous que la valeur est correctement récupérée
        dump($specialite);

        $em = $manager->getManager();
        $medecins = $em->createQuery('SELECT m FROM App\Entity\medecin m WHERE m.specialite = :specialite')
            ->setParameter('specialite', $specialite)
            ->getResult();

        if ($form->isSubmitted() && $form->isValid()) {
            // Les données du formulaire sont valides, vous pouvez les sauvegarder dans la base de données
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($rec);
            $entityManager->flush();

            return $this->redirectToRoute('app_affiche_rendez3');
        }
        return $this->render('rendez_vous/patient.html.twig', [
            'medecins' => $medecins,
            'form' => $form->createView(),

        ]);

    }

    #[Route('/showMedecinsBySpecialite', name:'showMedecinsBySpecialite2')]
    public function listMedecinsBySpecialite(Request $request, ManagerRegistry $manager)
    {
        // Récupérer la valeur sélectionnée dans le champ "fieldName"
        $specialite = $request->request->get('fieldName');

        // Assurez-vous que la valeur est correctement récupérée
        dump($specialite);

        $em = $manager->getManager();
        $medecins = $em->createQuery('SELECT m FROM App\Entity\medecin m WHERE m.specialite = :specialite')
            ->setParameter('specialite', $specialite)
            ->getResult();

        return $this->render('rendez_vous/list_dentist_medecins.html.twig', ['medecins' => $medecins]);
    }

    #[Route('/rendez/vous2', name: 'app_affiche_rendez3')]
    public function list(RendezVousRepository $repo): Response
    {
        $list=$repo->findAll();
        return $this->render('rendez_vous/showrenez-vous.html.twig', [
            'list' => $list,
        ]);
    }

    #[Route('/deleterendez-vous/{id}', name:'delete')]
    public function delete($id,RendezVousRepository $repo,ManagerRegistry $manager)
    {
        $author=$repo->find($id);
        $em=$manager->getManager();
        $em->remove($author);
        $em->flush();
        return $this->redirectToRoute('app_affiche_rendez3');
    }

    #[Route('/rendezvous/update/{id}', name: 'updateRendez')]
    public function updaterendezvous($id,RendezVousRepository $repo,Request $req,ManagerRegistry $manager)
    {
        $rendez=$repo->find($id);
        $form=$this->createForm(RendezVousType::class,$rendez);
        $form->add('Update', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary btn-user btn-block', // Ajoutez les classes Bootstrap nécessaires
            ],
        ]);

        $form->handleRequest($req);

        $patient = $this->getDoctrine()->getRepository(Patient::class)->find(1);

        // Définissez directement l'entité patient dans votre entité VotreEntity
        $rendez->setIdPatients($patient);
        $specialite = $req->request->get('fieldName');

        // Assurez-vous que la valeur est correctement récupérée
        dump($specialite);

        $em = $manager->getManager();
        $medecins = $em->createQuery('SELECT m FROM App\Entity\medecin m WHERE m.specialite = :specialite')
            ->setParameter('specialite', $specialite)
            ->getResult();
        if($form->isSubmitted())
        {
            $em=$manager->getManager();
            $em->persist($rendez);
            $em->flush();
            return $this->redirectToRoute('app_affiche_rendez3');
        }

        return $this->render('rendez_vous/update.html.twig', [
            'medecins' => $medecins,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/rendez/vous4/{id}', name: 'app_affiche_rendez4')]
    public function list2(RendezVousRepository $repo, $id): Response
    {
        $medecinId = 1;

        // Récupérer les rendez-vous pour le médecin avec l'ID spécifié
        $list = $repo->findBy(['idMedecins' => $medecinId]);

        return $this->render('medecin/showRendezVous.html.twig', [
            'list' => $list,
            'id' => $id,
        ]);
    }

    #[Route('/rendezvous/updateM/{id}', name: 'updateRendezMedecin')]
    public function updaterendezvousMedecin($id,RendezVousRepository $repo,Request $req,ManagerRegistry $manager)
    {
        $rendez = $repo->find($id);
        $idmed = $rendez->getIdMedecins()->getId();
        $form = $this->createForm(RendezVousType::class, $rendez);
        $form->add('Accepter', SubmitType::class); // bouton d'ajout add
        $form->handleRequest($req);
        $rendez->setStatus("accepter");
        $em = $manager->getManager();
        $id=$rendez->getIdPatients()->getId();
        $em->flush();
        return $this->redirectToRoute('app_affiche_rendez4', ['id' => $idmed]);

    }

    #[Route('/deleterendez-vousM/{id}', name:'deleteM')]
    public function deleteM($id,RendezVousRepository $repo,ManagerRegistry $manager)
    {
        $author=$repo->find($id);
        $idmed = $author->getIdMedecins()->getId();
        $em=$manager->getManager();
        $em->remove($author);
        $em->flush();
        return $this->redirectToRoute('app_affiche_rendez4', ['id' => $idmed]);
    }
    #[Route('rendez/rendezvous/statistique', name:'statistique')]
    public function rendezVousStatistique(RendezVousRepository $repo): Response
    {
        $rendezVous = $repo->findAll(); // récupérer tous les rendez-vous

        $motifs = [];
        foreach ($rendezVous as $rdv) {
            $motif = $rdv->getMotif();
            if (!isset($motifs[$motif])) {
                $motifs[$motif] = 0;
            }
            $motifs[$motif]++;
        }

        return $this->render('rendez_vous/stat.html.twig', [
            'motifs' => $motifs,
        ]);
    }


}