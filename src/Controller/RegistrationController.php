<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EmailSender $emailSender): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données de formulaire (entité User + mot de passe)
            $user = $form->getData();
            $password = $form->get('plainPassword')->getData();

            // hash du mot de passe et création du jeton
            $user
                ->setPassword($passwordEncoder->encodePassword($user, $password))
                ->renewToken()
            ;

            $this->manager->persist($user);
            $this->manager->flush();
            // Envoi de l'email de confirmation
            $emailSender->sendAccountConfirmationEmail($user);

            $this->addFlash('success', 'Vous avez bien été inscrit ! Un email de confirmation vous a été envoyé.');
            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * Confirmation du compte (via un lien envoyé par email)
     * @Route("/confirm-account/{id<\d+>}/{token}", name="account_confirmation")
     */
    
    public function confirmAccount($id, $token, UserRepository $repository)
    {
        // Recherche de l'utilisateur
        $user = $repository->findOneBy([
            'id' => $id,
            'token' => $token,
        ]);

        if ($user === null) {
            $this->addFlash('danger', 'Utilisateur ou jeton invalide.');
            return $this->redirectToRoute('app_login');
        }

        // Utilisateur trouvé: confirmation du compte
        $user
            ->confirmAcount()
            ->renewToken()
        ;
        $this->manager->flush();

        $this->addFlash('success', 'Votre compte est confirmé, vous pouvez vous connecter !');
        return $this->redirectToRoute('app_login');
    }
}
