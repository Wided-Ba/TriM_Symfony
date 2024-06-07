<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\admin;
use App\Entity\chefLab;
use App\Entity\infirmier;
use App\Entity\medecin;
use App\Entity\patient;
use App\Entity\pharmacien;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/seConnecter', name: 'seConnecter')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        // Récupérer les informations de connexion
        $email = $request->request->get('email');
        $password = $request->request->get('password');

        // Obtenir l'utilisateur depuis la base de données en fonction de l'email
        $user = $this->getUserFromDatabase($email);
        $isBlocked=$user->isIsBlocked();

        // Vérifier si un utilisateur a été trouvé avec cet email
        if (!$user) {
            // L'utilisateur n'existe pas
            return $this->redirectToRoute('app_login');
        }

        // Vérifier si le mot de passe est correct
        // Ici, vous devez adapter cette vérification en fonction de la structure de vos entités
        // Par exemple, si vous avez un champ password dans chaque entité, vous pouvez le comparer comme ceci :
        if($isBlocked==0) {
            if ($password === $user->getPassword()) {
                // Mot de passe correct, rediriger vers une autre page en fonction du type d'utilisateur
                if ($user->getRole() === 'Medecin') {
                    $id = $user->getId();
                    return $this->redirectToRoute('app_profil_med', ['id' => $id]);
                } elseif ($user->getRole() === 'Pharmacien') {
                    $id = $user->getId();
                    return $this->redirectToRoute('app_profil_pha', ['id' => $id]);
                } elseif ($user->getRole() === 'Chef laboratoire') {
                    $id = $user->getId();
                    return $this->redirectToRoute('chef_lab', ['id' => $id]);
                } elseif ($user->getRole() === 'Patient') {
                    $id = $user->getId();
                    return $this->redirectToRoute('app_profil_pat', ['id' => $id]);
                } elseif ($user->getRole() === 'Admin') {
                    $id = $user->getId();
                    return $this->redirectToRoute('dash', ['id' => $id]);
                } else {
                    // Gérer le cas où l'utilisateur n'a pas de rôle valide
                    return $this->redirectToRoute('app_login');
                }
            } else {
                // Mot de passe incorrect
                return $this->redirectToRoute('app_login');
            }
        }else {
            return $this->redirectToRoute('app_home');
        }

    }

    public function getUserFromDatabase($email)
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Recherche dans l'entité Medecin
        $medecinRepository = $entityManager->getRepository(medecin::class);
        $medecin = $medecinRepository->findOneBy(['email' => $email]);
        if ($medecin) {
            return $medecin;
        }

        // Recherche dans l'entité Infirmier
        $infirmierRepository = $entityManager->getRepository(infirmier::class);
        $infirmier = $infirmierRepository->findOneBy(['email' => $email]);
        if ($infirmier) {
            return $infirmier;
        }

        // Recherche dans l'entité Pharmacien
        $pharmacienRepository = $entityManager->getRepository(pharmacien::class);
        $pharmacien = $pharmacienRepository->findOneBy(['email' => $email]);
        if ($pharmacien) {
            return $pharmacien;
        }

        // Recherche dans l'entité ChefLab
        $chefLabRepository = $entityManager->getRepository(chefLab::class);
        $chefLab = $chefLabRepository->findOneBy(['email' => $email]);
        if ($chefLab) {
            return $chefLab;
        }

        $patientRepository = $entityManager->getRepository(patient::class);
        $patient = $patientRepository->findOneBy(['Email' => $email]);
        if ($patient) {
            return $patient;
        }

        $adminRepository = $entityManager->getRepository(admin::class);
        $admin = $adminRepository->findOneBy(['email' => $email]);
        if ($admin) {
            return $admin;
        }

        return null;
    }


    #[Route('/forgotPass', name: 'forgotPass')]
    public function requestResetPassword(Request $request,  MailerInterface $mailer, UserPasswordEncoderInterface $passwordEncoder):Response
    {
        // Validate form submission and get the email
        $email = $request->request->get('email');

        // 2. Check if email exists
        $user = $this->getUserFromDatabase($email);

        // 3. Generate a new password
        $newPassword = bin2hex(random_bytes(8)); // Generate a random password

        // 4. Update Password in the Database
        $entityManager = $this->getDoctrine()->getManager();
        $user->setPassword($newPassword);
        $entityManager->flush();

        // 5. Send Email with New Password
        $email = (new Email())
            ->from('trim.noreplay@gmail.com')
            ->to($email)
            ->subject('Password Reset')
            ->html('<h4> Voici votre nouveau mot de passe<h2 style="color: #4f1915">' .$newPassword.'</h2></h4>');

        $mailer->send($email);
        // Envoie d'un msg
        $this->addFlash('notice','vérifiez votre courrier pour un nouveau mot de passe! ');
        // 6. Notify User
        // Redirect or show a success message
        return $this->redirectToRoute('app_home');
    }







}