<?php

namespace App\Controller;
require __DIR__ . '/../../vendor/autoload.php';
use App\Entity\analyse;
use App\Entity\lab;
use App\Entity\chefLab;
use App\Entity\ordonnance;
use App\Form\AnalyseType;
use App\Form\ChefLabType;
use App\Form\CodeType;
use App\Form\OrdlabType;
use App\Form\UploadFileType;
use App\Repository\AnalyseRepository;
use App\Repository\ChefLabRepository;
use App\Repository\LabRepository;
use App\Repository\MedecinRepository;
use App\Repository\OrdonnanceRepository;
use App\Form\LabType;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Dompdf\Options;
use OpenAI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;


class ChefLabController extends AbstractController
{
    #[Route('/cheflab/{id}', name: 'chef_lab')]
    public function index1($id,chefLabRepository $repo, analyserepository $repoAnalyse, ordonnanceRepository $repoOrd): Response
    {
        $nombreOrdonnancesEnAttente = 0;
        $cheflab = $repo->find($id);
        $idLab=$cheflab->getIdLab();
        $analyse = $repoAnalyse->findBy(['idLab' => $idLab]);
        if ($cheflab->getIdLab() == null) {
            $notyf = "erreur";
        }
        if ($cheflab->getIdLab() !== null) {
            if (empty($analyse)) {
                $notyf = "urgence";
            } else {
                $ordonnances = $repoOrd->findBy(['idLabs' => $idLab]);

                foreach ($ordonnances as $ordonnance) {
                    if ($ordonnance->getEtat() === 'En attente') {
                        $nombreOrdonnancesEnAttente++;
                    }
                }
                if ($nombreOrdonnancesEnAttente == 0) {
                    $notyf = "success";
                } else {
                    $notyf = "success2";
                }
            }
        }
        $chefLabRepository = $this->getDoctrine()->getRepository(ChefLab::class);
        $chefLab = $chefLabRepository->find($id);
        $laboratoireId = $chefLab->getIdLab();
        $laboratoireRepository = $this->getDoctrine()->getRepository(Lab::class);
        $laboratoire = $laboratoireRepository->find($laboratoireId);

        return $this->render('chef_lab/index.html.twig', [
            'cheflab' => $chefLab,
            'laboratoire' => $laboratoire,
            'notyf' => $notyf,
            'nombreOrdonnancesEnAttente' => $nombreOrdonnancesEnAttente,
        ]);
    }

    #[Route('/cheflab/labo/addLab/{id}', name:'add_lab')]
    public function createLab($id, Request $request, chefLabRepository $repo): Response
    {
        $cheflab = $repo->find($id);
        if ($cheflab->getIdLab() !== null) {
            // Afficher un message d'erreur
            $this->addFlash('error', 'Vous ne pouvez pas ajouter un laboratoire car vous en possédez déjà un.');
            // Rediriger l'utilisateur vers une autre page par exemple
            return $this->redirectToRoute('list_lab', ['id' => 1]);
        }
        $lab = new lab();
        $form = $this->createForm(LabType::class, $lab);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Les données du formulaire sont valides, vous pouvez les sauvegarder dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($lab);
            $cheflab->setIdLab($lab);
            $entityManager->flush();
            return $this->redirectToRoute('list_lab', ['id' => 1]);
        }

        return $this->render('chef_lab/addLab.html.twig', ['form' => $form->createView(),]);
    }


    #[Route('/cheflab/labo/editLab/{id}', name:'edit_lab')]
    public function editLab($id, Request $request, EntityManagerInterface $entityManager)
    {
        // Récupérer l'auteur à partir de l'ID
        $lab = $entityManager->getRepository(lab::class)->find($id);

        if (!$lab) {
            throw $this->createNotFoundException('Lab non trouvé');
        }

        // Créer le formulaire de modification de l'auteur en utilisant l'entité récupérée
        $form = $this->createForm(LabType::class, $lab);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les modifications dans la base de données
            $entityManager->flush();

            // Rediriger l'utilisateur vers une autre page (par exemple, la liste des auteurs)
            return $this->redirectToRoute('list_lab', ['id' => 1]);
        }

        return $this->render('chef_lab/editLab.html.twig', [
            'form' => $form->createView(),
            'lab' => $lab, // Passer l'entité Author pour afficher les informations actuelles
        ]);
    }

    #[Route('/delete/{id}', name:'delete_lab')]
    public function deleteLab($id, LabRepository $repo, ManagerRegistry $manager, ChefLabRepository $chefLabRepo)
    {
        $lab =$repo->find($id);
        $en=$manager->getManager();
        $en->remove($lab);
        $en->flush();

        // Rediriger l'utilisateur vers une autre page (par exemple, la liste des auteurs)
        return $this->redirectToRoute('list_lab',['id' => 1]);
    }

    //*************************************************************************************************************

    #[Route('/cheflab/labo/addanalyse/{id}', name:'add_analyse' , methods: ['GET','POST'])]
    public function createAnalyse($id, Request $request, EntityManagerInterface $entityManager, LabRepository $labRepository): Response
    {
        $lab = $labRepository->find($id);
        $analyse = new analyse();
        $answer = '';
        $form = $this->createForm(AnalyseType::class, $analyse);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($analyse);
            $analyse->setIdLab($lab);
            $entityManager->flush();
            return $this->redirectToRoute('list_lab', ['id' => 1]);
        }

        return $this->render('chef_lab/addAnalyse.html.twig', ['form' => $form->createView(),'answer' => $answer,]);
    }

    #[Route('/cheflab/labo/addanalyse2/{id}', name:'add_analyse2' , methods: ['GET','POST'])]
    public function createAnalyse2($id, Request $request, LabRepository $labRepository): Response
    {
        $lab = $labRepository->find($id);
        $analyse = new analyse();
        $form = $this->createForm(AnalyseType::class, $analyse);
        $form->handleRequest($request);
        $question = '';
        if ($form->isSubmitted() && $form->isValid()) {
            $question=$form->get('nom')->getData();
        }
        //Implémentation du chat gpt

        $myApiKey = $_ENV['OPENAI_KEY'];


        $client = OpenAI::client($myApiKey);

        $result = $client->completions()->create([
            'model' => 'gpt-3.5-turbo-instruct',
            'prompt' => $question,
            'max_tokens'=>2048
        ]);

        $response=$result->choices[0]->text;


        return $this->forward('chef_lab/addAnalyse.html.twig', [

            'question' => $question,
            'response' => $response
        ]);
    }

    #[Route('/cheflab/labo/editanalyse/{id}', name:'edit_analyse')]
    public function editanalyse($id, Request $request, EntityManagerInterface $entityManager)
    {
        // Récupérer l'auteur à partir de l'ID
        $analyse = $entityManager->getRepository(analyse::class)->find($id);

        if (!$analyse) {
            throw $this->createNotFoundException('analyse non trouvé');
        }

        // Créer le formulaire de modification de l'auteur en utilisant l'entité récupérée
        $form = $this->createForm(analyseType::class, $analyse);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les modifications dans la base de données
            $entityManager->flush();

            // Rediriger l'utilisateur vers une autre page (par exemple, la liste des auteurs)
            return $this->redirectToRoute('list_lab', ['id' => 1]);
        }

        return $this->render('chef_lab/editanalyse.html.twig', [
            'form' => $form->createView(),
            'analyse' => $analyse, // Passer l'entité Author pour afficher les informations actuelles
        ]);
    }

    #[Route('/deleteanalyse/{id}', name:'delete_analyse')]
    public function deleteanalyse($id, AnalyseRepository $analyrepo, ManagerRegistry $manager, ChefLabRepository $chefLabRepo): Response
    {
        $analyse = $analyrepo->find($id);

        if (!$analyse) {
            // Gérer le cas où l'analyse avec l'ID spécifié n'existe pas
            $this->addFlash('error', 'L\'analyse que vous essayez de supprimer n\'existe pas.');
            return $this->redirectToRoute('list_lab',['id' => 1]);
        }

        $en = $manager->getManager();
        $en->remove($analyse);
        $en->flush();

        // Rediriger l'utilisateur vers une autre page (par exemple, la liste des analyses)
        return $this->redirectToRoute('list_lab',['id' => 1]);
    }

    //******************************************************************************************************************
    #[Route('/cheflab/labo/{id}', name: 'list_lab')]
    public function list($id,cheflabRepository $repo,LabRepository $labRepo, AnalyseRepository $anRepo): Response
    {
        $hasLab = false;
        $chefLab = $repo->find($id);
        if ($chefLab->getIdLab() !== null) {
            $hasLab = true;
        }
        $idLab = $chefLab->getIdlab();
        $labs = $labRepo->findBy(['id' => $idLab]);
        $laboratoireRepository = $this->getDoctrine()->getRepository(Lab::class);
        $laboratoire = $laboratoireRepository->find($idLab);
        $analyses = $anRepo->findBy(['idLab' => $idLab]);
        return $this->render('chef_lab/labo.html.twig', [
            'cheflab' => $chefLab,
            'laboratoire' => $laboratoire,
            'Labs' => $labs,
            'analyses' => $analyses,
            'hasLab' => $hasLab,
        ]);
    }

    #[Route('/cheflab/ord/{id}', name: 'list_ord')]
    public function list2($id,cheflabRepository $repo, LabRepository $labRepo, OrdonnanceRepository $ordRepo): Response
    {
        $chefLab = $repo->find($id);
        $idLab = $chefLab->getIdlab();
        $laboratoireRepository = $this->getDoctrine()->getRepository(Lab::class);
        $laboratoire = $laboratoireRepository->find($idLab);
        $ord = $ordRepo->findCompletedByLabId($idLab);
        return $this->render('chef_lab/ord.html.twig', [
            'cheflab' => $chefLab,
            'laboratoire' => $laboratoire,
            'ords' => $ord,
        ]);
    }



    //***********************************************statistique*****************************************************
    #[Route('/cheflab/static/{id}', name: 'stat_lab')]
    public function stat($id,Request $request,cheflabRepository $repo, OrdonnanceRepository $ordRepo,AnalyseRepository $analyseRepo): Response
    {
        $chefLab = $repo->find($id);
        if (!$chefLab) {
            throw $this->createNotFoundException('ChefLab non trouvé avec l\'ID : '.$id);
        }

        // Récupérer l'ID du laboratoire associé au ChefLab
        $laboId = $chefLab->getIdlab()->getId();

        // Récupérer les données d'ordonnances pour le laboratoire spécifié
        $ordonnances = $ordRepo->findByLaboId($laboId);

        // Initialiser un tableau pour stocker le nombre d'ordonnances par mois
        $ordonnancesParMois = [];

        // Compter le nombre d'ordonnances par mois
        foreach ($ordonnances as $ordonnance) {
            $mois = $ordonnance->getDate()->format('Y-m');
            if (!isset($ordonnancesParMois[$mois])) {
                $ordonnancesParMois[$mois] = 0;
            }
            $ordonnancesParMois[$mois]++;
        }

        // Initialiser un tableau pour stocker le nombre d'analyses par type
        $typesAnalyse = [];

        // Compter le nombre d'analyses par type
        foreach ($ordonnances as $ordonnance) {
            $analyseId = $ordonnance->getAnalyseList(); // ID de l'analyse
            $analyse = $analyseRepo->find($analyseId); // Récupérer l'objet Analyse à partir de l'ID

            // Vérifier si l'analyse existe
            if ($analyse) {
                $nomAnalyse = $analyse->getNom();
                if (!isset($typesAnalyse[$nomAnalyse])) {
                    $typesAnalyse[$nomAnalyse] = 0;
                }
                $typesAnalyse[$nomAnalyse]++;
            }
        }

        // Calculer le pourcentage de chaque type d'analyse
        $totalAnalyses = array_sum($typesAnalyse);
        $pourcentages = [];
        $labels = [];
        foreach ($typesAnalyse as $type => $nombre) {
            $pourcentage = ($nombre / $totalAnalyses) * 100;
            $pourcentages[] = round($pourcentage, 2); // Arrondir à deux décimales
            $labels[] = $type;
        }

        // Transformer les données pour les utiliser dans un graphique
        $labels1 = [];
        $donnees1 = [];
        foreach ($ordonnancesParMois as $mois => $nombre) {
            $labels1[] = $mois;
            $donnees1[] = $nombre;
        }

        // Rendre la vue avec les données pour le graphique
        return $this->render('chef_lab/static.html.twig', [
            'cheflab' => $chefLab,
            'labels1' => json_encode($labels1),
            'donnees1' => json_encode($donnees1),
            'labels' => json_encode($labels),
            'pourcentages' => json_encode($pourcentages),
        ]);
    }


    #[Route('/cheflab/static/pdf/{laboId}', name: 'pdf_stat')]
    public function statpdf($laboId,OrdonnanceRepository $ordRepo, AnalyseRepository $analyseRepo, LabRepository $laboRepo): Response
    {
        $labo = $laboRepo->find($laboId);

        // Vérifier si le laboratoire existe
        if (!$labo) {
            throw $this->createNotFoundException('Laboratoire non trouvé');
        }

        // Récupérer les ordonnances du laboratoire spécifié
        $ordonnances = $ordRepo->findBy(['idLabs' => $laboId]);
        $analysesParMois = [];
        $totalAnalyses = 0; // Initialisation du nombre total d'analyses

        foreach ($ordonnances as $ordonnance) {
            $mois = $ordonnance->getDate()->format('Y-m');
            $analyseId = $ordonnance->getAnalyseList();
            $analyse = $analyseRepo->find($analyseId);

            if ($analyse) {
                $nomAnalyse = $analyse->getNom();
                if (!isset($analysesParMois[$mois][$nomAnalyse])) {
                    $analysesParMois[$mois][$nomAnalyse] = 0;
                }
                $analysesParMois[$mois][$nomAnalyse]++;
                $totalAnalyses++; // Incrément du nombre total d'analyses
            }
        }

        $htmlContent = $this->renderView('chef_lab/pdf.html.twig', [
            'analysesParMois' => $analysesParMois,
            'totalAnalyses' => $totalAnalyses, // Passage du nombre total d'analyses à la vue Twig
            'analyseRepo' => $analyseRepo,
            'labo' => $labo, // Passage de l'entité du laboratoire à la vue Twig
        ]);


        // Initialisez Dompdf

        $options = new Options();
        $options->set('chroot', realpath(''));
        $dompdf = new Dompdf($options);

        // Chargez le contenu HTML dans Dompdf
        $dompdf->loadHtml($htmlContent);

        // Définissez le format du papier et l'orientation
        $dompdf->setPaper('A4');
        // Rendre le PDF
        $dompdf->render();

        // Récupérez le contenu du PDF généré
        $pdfOutput = $dompdf->output();

        // Retournez une réponse avec le contenu PDF
        return new Response($pdfOutput, Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
        ]);

    }


    //*********************************Kenza****************************************************************************

    #[Route('/cheflab/add', name: 'chef_add')]
    public function create(Request $request):Response
    {
        $chef = new chefLab();
        $form = $this->createForm(ChefLabType::class, $chef);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Les données du formulaire sont valides, vous pouvez les sauvegarder dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($chef);
            $entityManager->flush();

            $this->addFlash('success', 'le compte a été enregistré avec succès.');

            // Redirigez l'utilisateur vers une autre page (par exemple, une liste d'auteurs)
            return $this->redirectToRoute('app_home');
        }
        return $this->render('dash/chef_inscri.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //****************************************email*****************************************************************
    #[Route('/cheflab/editordlab/{id}', name:'edit_ordlab')]
    public function editOrd($id, Request $request,
                            EntityManagerInterface $entityManager,
                            PatientRepository $patrepo,
                            AnalyseRepository $Anarepo,
                            MailerInterface $mailer
                        )
    {
        $ord = $entityManager->getRepository(ordonnance::class)->find($id);
        $idPatient = $ord->getIdPatients();
        $idanalyse = $ord->getAnalyseList();
        $analyse = $Anarepo->find($idanalyse);
        $patient = $patrepo->find($idPatient);
        $form = $this->createForm(OrdlabType::class, $ord);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($ord->getEtat() === 'Terminé') {
                $entityManager->flush();
                    return $this->redirectToRoute('ord_fichier',['id' => $id]);
            }
            if ($ord->getEtat() === 'En cours') {
                $code = mt_rand(1000, 9999);
                $ord->setCode($code);

                $htmlContent = $this->renderView('chef_lab/email3.html.twig', [
                    'patient' => $patient,
                    'code' => $code,// Passez les paramètres nécessaires à votre page Twig
                ]);

                $emailPatient = (new Email())
                    ->from('trim.noreply@gmail.com')
                    ->to($patient->getEmail())
                    ->subject('Confirmation de votre ordonnance')
                    ->html($htmlContent);
                $mailer->send($emailPatient);

                $entityManager->flush();
                return $this->redirectToRoute('ord_code',['id' => $id]);
            }else {
                $entityManager->flush();
                return $this->redirectToRoute('list_ord',['id' => 1]);
            }
        }
        return $this->render('chef_lab/editOrd.html.twig', [
            'form' => $form->createView(),
            'ord' => $ord,
            'analyse' => $analyse,
        ]);
    }

    #[Route('/cheflab/editordlab/code/{id}', name: 'ord_code')]
    public function code($id, Request $request,
                         EntityManagerInterface $entityManager,
                         MedecinRepository $medrepo,
                         PatientRepository $patrepo,
                         MailerInterface $mailer): Response
    {
        $ord = $entityManager->getRepository(ordonnance::class)->find($id);

        $form = $this->createForm(CodeType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $enteredCode = $formData['code'];
                if ($enteredCode == $ord->getCode()){
                    return $this->redirectToRoute('list_ord',['id' => 1]);
                }
                else {
                    $ord->setEtat('En attente');
                    $entityManager->flush();
                    return $this->redirectToRoute('list_ord',['id' => 1 , 'error' => true]);
                }
        }
        return $this->render('chef_lab/code.html.twig', [
            'form' => $form->createView(),
            'ord' => $ord,
        ]);
    }

    #[Route('/cheflab/editordlab/file/{id}', name: 'ord_fichier')]
    public function fichier($id, Request $request,
                            EntityManagerInterface $entityManager,
                            MedecinRepository $medrepo,
                            PatientRepository $patrepo,
                            MailerInterface $mailer): Response
    {
        $ord = $entityManager->getRepository(ordonnance::class)->find($id);

        $form = $this->createForm(UploadFileType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        $idMed = $ord->getIdMedecins();
        $idPatient = $ord->getIdPatients();
        $medecin = $medrepo->find($idMed);
        $patient = $patrepo->find($idPatient);

        $file =$form->get('file')->getData();
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $file->getClientOriginalExtension();
        $file->move($this->getParameter('file_directory'), $filename);
        $htmlContent = $this->renderView('chef_lab/email2.html.twig', [
                'medecin' => $medecin,
                'patient' => $patient,
                'id' => $id,// Passez les paramètres nécessaires à votre page Twig
            ]);

            $emailMedecin = (new Email())
            ->from('trim.noreply@gmail.com')
            ->to($medecin->getEmail())
            ->subject('Ordre médical terminé')
            ->html($htmlContent)
            ->attachFromPath($this->getParameter('file_directory').'/'.$filename, $filename);
        $mailer->send($emailMedecin);

        $htmlContent2 = $this->renderView('chef_lab/email.html.twig', [
                'patient' => $patient,
                'id' => $id,// Passez les paramètres nécessaires à votre page Twig
            ]);
        $emailPatient = (new Email())
            ->from('trim.noreply@gmail.com')
            ->to($patient->getEmail())
            ->subject('Votre ordonnance est prête')
            ->html($htmlContent2)
            ->attachFromPath($this->getParameter('file_directory').'/'.$filename, $filename);
        $mailer->send($emailPatient);

        return $this->redirectToRoute('list_ord',['id' => 1]);
        }
        return $this->render('chef_lab/upload_file.html.twig', [
            'form' => $form->createView(),
            'ord' => $ord,
        ]);
    }
//*********************** *************************email*****************************************************************


}
