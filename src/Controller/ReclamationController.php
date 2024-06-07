<?php

namespace App\Controller;

use App\Entity\reclamation;
use App\Form\ReclamationType;
use App\Repository\PatientRepository;
use App\Repository\ReclamationRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ReclamationController extends AbstractController
{

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */

//*******************************************************************************************************
    #[Route('/patient/{id}', name:'app_reclamation2')]
    public function createReclamation($id,Request $request, PatientRepository $repo): Response
    {
        $patient = $repo->find($id);
        $rec= new reclamation();
        $form = $this->createForm(ReclamationType::class, $rec);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $description = $rec->getDescription();
            $httpClient = HttpClient::create();
            $response = $httpClient->request('GET', 'https://neutrinoapi.net/bad-word-filter',[
                'query' => [
                    'content' => $description
                ],
                'headers' => [
                    'User-ID' => 'salah',
                    'API-Key' => 'EsYvk7fUlch8gCjBpGtsBFHea0wYB79FrOM1jxlHZDIAgSUU',
                ]
            ]);
            if ($response->getStatusCode() === 200) {
                $result = $response->toArray();
                if ($result['is-bad'] ) {
                    $this->addFlash('danger', '</i>Your Question contains inappropriate language and cannot be posted.');
                    return $this->redirectToRoute('bad_words');
                }
            }
            $rec->setDaterec(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rec);
            $rec->setIdPatients($patient);
            $entityManager->flush();

            $this->addFlash('success', 'L\'auteur a été enregistré avec succès.');

            // Redirigez l'utilisateur vers une autre page (par exemple, une liste d'auteurs)
            return $this->redirectToRoute('app_reclamation2', ['id' => 1]);
        }

        return $this->render('reclamation/addRec.html.twig',['form' => $form->createView(),]);
    }

    #[Route('/{id}', name:'app_reclamation9')]
    public function createReclamation9($id,Request $request, PatientRepository $repo): Response
    {
        $patient = $repo->find($id);
        $rec= new reclamation();
        $form = $this->createForm(ReclamationType::class, $rec);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $description = $rec->getDescription();
            $httpClient = HttpClient::create();
            $response = $httpClient->request('GET', 'https://neutrinoapi.net/bad-word-filter',[
                'query' => [
                    'content' => $description
                ],
                'headers' => [
                    'User-ID' => 'salah',
                    'API-Key' => 'EsYvk7fUlch8gCjBpGtsBFHea0wYB79FrOM1jxlHZDIAgSUU',
                ]
            ]);
            if ($response->getStatusCode() === 200) {
                $result = $response->toArray();
                if ($result['is-bad'] ) {
                    $this->addFlash('danger', '</i>Your Question contains inappropriate language and cannot be posted.');
                    return $this->redirectToRoute('bad_words');
                }
            }
            $rec->setDaterec(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rec);
            $rec->setIdPatients($patient);
            $entityManager->flush();

            $this->addFlash('success', 'L\'auteur a été enregistré avec succès.');

            // Redirigez l'utilisateur vers une autre page (par exemple, une liste d'auteurs)
            return $this->redirectToRoute('app_reclamation9', ['id' => 1]);
        }

        return $this->render('reclamation/addRec2.html.twig',['form' => $form->createView(),]);
    }
    #[Route('/bad_words', name: 'bad_words')]
    function Affiche_bad(ReclamationRepository $repository)
    {
        $reclamation = $repository->findAll();
        return $this->render('reclamation/bad_word.html.twig');
    }
//*******************************************************************************************************
    #[Route('/reclamation/show', name: 'app_affiche_reclamation')]
    public function list(ReclamationRepository $repo): Response
    {
        $list=$repo->findAll();
        return $this->render('reclamation/reclamation.html.twig', [
            'reclamation' => $list,
        ]);
    }

    #[Route('/recdelete/{id}', name:'delete_rec')]
    public function delete_rec($id,ReclamationRepository $repo,ManagerRegistry $manager)
    {
        $rec=$repo->find($id);
        $em=$manager->getManager();
        $em->remove($rec);
        $em->flush();
        return $this->redirectToRoute('app_affiche_reclamation');
    }
    #[Route('/recshowO/{id}', name:'showA')]
    public function showO($id,ReclamationRepository $repo)
    {
        $reclamation=$repo->find($id);
        return $this->render('reclamation/showO.html.twig',['reclamation'=>$reclamation]);

    }
}