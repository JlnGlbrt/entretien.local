<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserLoginType;
use App\Form\UserRegisterType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController {

    /**
     * Connexion d'un utilisateur
     *
     * @Route("/login", name="login")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     *
     * @param AuthenticationUtils $authUtils
     * @param Request $request
     * @return Response
     */
    public function login(AuthenticationUtils $authUtils, Request $request): Response
    {
        // On crée le formulaire de connexion
        $form = $this->createForm(UserLoginType::class, new User());
        $form->handleRequest($request);

        // On retourne la vue
        return $this->render('user/login.html.twig', [
            // last username entered by the user (if any)
            'last_username' => $authUtils->getLastUsername(),
            // last authentication error (if any)
            'error' => $authUtils->getLastAuthenticationError(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * Inscription d'un utilisateur
     *
     * @Route("/register", name="register")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // On crée le formulaire d'inscription
        $user = new User();
        $form = $this->createForm(UserRegisterType::class, $user);
        $form->handleRequest($request);

        // Si le formulaire à été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On crypte et on met  à jour le mot de passe de l'utilisateur à inscrire
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            // On insère l'utilisateur en BDD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // On retourne à la page de connexion
            return $this->redirectToRoute('login');
        }

        // On retourne le formulaire d'inscription
        return $this->render('user/register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Déconnexion de l'utilisateur : gérée automatiquement
     *
     * @Route("/logout", name="logout")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function logout(): void
    {
    }
}