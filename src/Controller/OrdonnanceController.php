<?php

namespace App\Controller;

use App\Entity\ordonnance;
use App\Form\OrdonnanceType;
use App\Repository\LabRepository;
use App\Repository\MaladieRepository;
use App\Repository\MedecinRepository;
use App\Repository\MedicamentRepository;
use App\Repository\OrdonnanceRepository;
use App\Repository\PatientRepository;
use App\Repository\PharmacieRepository;
use App\Repository\RendezVousRepository;
use App\Services\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class OrdonnanceController extends AbstractController
{
    #[Route('/ordonnance/all/{medecinId}', name: 'ord_all')]
    public function listAll(
        $medecinId,
        OrdonnanceRepository $ordonnanceRepository,
        MedecinRepository $medecinRepository
    ): Response {
        // Trouver le médecin en fonction de l'ID fourni
        $medecin = $medecinRepository->find($medecinId);
        $id=$medecinId;
        // Vérifier si le médecin a été trouvé
        if (!$medecin) {
            throw $this->createNotFoundException('Médecin non trouvé.');
        }

        // Récupérer toutes les ordonnances associées à ce médecin spécifique
        $ord = $ordonnanceRepository->findBy(['idMedecins' => $medecin]);

        return $this->render('ordonnance/list.html.twig', [
            'ord' => $ord,
            'medecinN' => $medecin->getNom(),
            'medecinP' => $medecin->getPrenom(),
            'medecinId' => $medecin->getId(),
            'id' => $id,
        ]);
    }

    #[Route('/ordonnance/{id}', name: 'app_ordonnance')]
    public function createOrdonnance(
        MedecinRepository $medecinRepository,
        PharmacieRepository $pharmacieRepository,
        RendezVousRepository $rendezvousRepository,
        MaladieRepository $maladieRepository,
        MedicamentRepository $medicamentRepository,
        LabRepository $labRepository,
        MailerInterface $mailer,
        Request $request,
        $id
    ): Response {
        // Fetching necessary data
        $listMedecins = $medecinRepository->findAll();
        $listPharmacies = $pharmacieRepository->findAll();
        $rendezVous = $rendezvousRepository->find($id);

        // Fetching patient details
        $medecin = $rendezVous->getIdMedecins();
        $medecinNom=$medecin->getNom();
        $medecinPrenom=$medecin->getPrenom();
        $medecinId=$medecin->getId();

        $patient = $rendezVous->getIdPatients();
        $namePatient = $patient->getNom();
        $genrePatient = $patient->getGenre();
        $adressePatient = $patient->getAdresse();
        $agePatient = $patient->getAge();
        $emailPatient = $patient->getEmail();
        $numTelPatient = $patient->getNtel();

        // Fetching other related entities
        $maladies = $maladieRepository->findAll();
        $medicaments = $medicamentRepository->findAll();
        $labs = $labRepository->findAll();

        // Creating new Ordonnance instance and form
        $ordonnance = new Ordonnance();
        $form = $this->createForm(OrdonnanceType::class, $ordonnance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handling form submission
            $entityManager = $this->getDoctrine()->getManager();

            // Setting properties for the Ordonnance entity
            $ordonnance->setDate($rendezVous->getDate());
            $ordonnance->setIdMedecins($rendezVous->getIdMedecins());
            $ordonnance->setIdPatients($patient);
            $ordonnance->setIdRendezVous($rendezVous);

            $code = $this->generateCode($rendezVous, $patient); // Generate code based on rendezvous and patient data
            $ordonnance->setCode($code);
            //$medecinEmail = $medecin->getEmail();
            $ordonnance->setEtat("En attente");
            $entityManager->persist($ordonnance);
            $entityManager->flush();
            $htmlContent = $this->renderView('ordonnance/email.html.twig',[
                'nomP' => $namePatient,
            ]);
            $email = (new Email())
                ->from('trim.noreplay@gmail.com')
                ->to($emailPatient)
                ->subject('New Ordonnance Created')
                ->html($htmlContent);
            $mailer->send($email);
            return $this->redirectToRoute('ord_all', ['medecinId' => $medecinId]);
        }

        // Rendering the form and related data for the template
        return $this->render('ordonnance/shared.html.twig', [
            'meds' => $listMedecins,
            'pharmacie' => $listPharmacies,
            'rdv' => $rendezVous,
            'maladies' => $maladies,
            'medicaments' => $medicaments,
            'lab' => $labs,
            'form' => $form->createView(),
            'nomP' => $namePatient,
            'genreP' => $genrePatient,
            'adresseP' => $adressePatient,
            'ageP' => $agePatient,
            'emailP' => $emailPatient,
            'numTelP' => $numTelPatient,
            'rdvDate' => $rendezVous->getDate(),
            'medecinNom' => $medecinNom,
            'medecinPrenom' => $medecinPrenom,
            'medecinId' => $medecinId,
        ]);
    }

    //================================================================
    #[Route('/ordonnance/edit/{id}', name: 'edit_ord')]
    public function editOrdonnance(
        ordonnance $ordonnance,
        Request $request,
        EntityManagerInterface $entityManager,
        PatientRepository $patientRepository,
        MedecinRepository $medecinRepository,
        $id
    ): Response {
        // Fetching the ordonnance
        $ord = $entityManager->getRepository(ordonnance::class)->find($id);

        // Fetching the rendezVous associated with the ordonnance
        $rendezVous = $ord->getIdRendezVous();

        // Initializing medecin name variables
        $medecinNom = null;
        $medecinPrenom = null;
        $medecinId = null;

        // Checking if rendezVous exists and has associated medecin
        if ($rendezVous && $rendezVous->getIdMedecins()) {
            $medecinId = $rendezVous->getIdMedecins()->getId();
            $medecin = $medecinRepository->find($medecinId);

            // If medecin is found, assign its name to variables
            if ($medecin) {
                $medecinNom = $medecin->getNom();
                $medecinPrenom = $medecin->getPrenom();
            }
        }
        $form = $this->createForm(OrdonnanceType::class, $ordonnance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Optionally set any other fields here if needed before flushing
            $entityManager->flush();

            // Redirect the user to another page after successful update
            return $this->redirectToRoute('ord_all',['medecinId' => $medecinId]);
        }

        return $this->render('ordonnance/editOrd.html.twig', [
            'form' => $form->createView(),
            'ord' => $ord,
            'p' => $patientRepository->findAll(),
            'medecinNom' => $medecinNom,
            'medecinPrenom' => $medecinPrenom,
            'medecinId' => $medecinId,
        ]);
    }

    //============================================================
    #[Route('/ordonnance/delete/{id}', name: 'delete_ord')]
    public function deleteOrdonnance($id, OrdonnanceRepository $ordonnanceRepository, EntityManagerInterface $entityManager): Response {
        $ord = $ordonnanceRepository->find($id);
        if (!$ord) {
            throw new NotFoundHttpException('Ordonnance not found for ID: '.$id);
        }

        // Fetch medecinId from the deleted ordonnance
        $medecinId = $ord->getIdMedecins()->getId();

        $entityManager->remove($ord);
        $entityManager->flush();

        // Redirect to the ord_all route with the medecinId parameter
        return $this->redirectToRoute('ord_all', ['medecinId' => $medecinId]);
    }



    #[Route('/ordonnance/pdf/{id}', name: 'generate_pdf')]
    public function generatePdf(
        Ordonnance $ordonnance,
        PdfService $pdfService,
    ): Response {

        $patientNom = $ordonnance->getIdPatients()->getNom();
        $patientPrenom = $ordonnance->getIdPatients()->getPrenom();
        $patientGenre = $ordonnance->getIdPatients()->getGenre();
        $patientAge = $ordonnance->getIdPatients()->getAge();
        $patientEmail = $ordonnance->getIdPatients()->getEmail();
        $patientTel = $ordonnance->getIdPatients()->getNtel();

        //medecin information maintenant
        $medecinNom = $ordonnance->getIdMedecins()->getNom();
        $speciality = $ordonnance->getIdMedecins()->getSpecialite();
        $medecinTel = $ordonnance->getIdMedecins()->getNTel();
        $pdfContent = $this->renderView('ordonnance/ordonnance_pdf.html.twig', [
            'ordonnance' => $ordonnance,
            'patientNom'=>$patientNom,
            'patientPrenom'=>$patientPrenom,
            'patientGenre'=>$patientGenre,
            'patientAge'=>$patientAge,
            'patientEmail'=>$patientEmail,
            'patientTel'=>$patientTel,
            'medecinNom'=>$medecinNom,
            'speciality'=>$speciality,
            'medecinTel'=>$medecinTel,
        ]);
        try {
            $pdfBinary = $pdfService->generateBinaryPDF($pdfContent);
            return new Response(
                $pdfBinary,
                Response::HTTP_OK,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="ordonnance.pdf"',
                ]
            );
        } catch (\Exception $e) {
            return new Response('PDF generation failed', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function generateCode($rendezVous, $patient): string
    {
        $date = $rendezVous->getDate()->format('Ymd'); // Format date as YYYYMMDD
        $userData = $patient->getNom() . $patient->getNtel(); // Concatenate user data
        $code = md5($date . $userData); // Apply hashing MD5 algorithm
        return strtoupper(substr($code, 0, 8)); // Extract first 8 characters and convert to uppercase
    }

    #[Route('/profile')]
    public function profile() {
        return $this->render('ordonnance/medecinProfil.html.twig',[]);
    }
}