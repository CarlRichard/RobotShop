<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface; // Remplacez EntityManager par EntityManagerInterface
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
    
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            
            // Chercher l'utilisateur par email
            $user = $em->getRepository(User::class)->findOneByEmail($email); 
    
            // Vérifier si l'utilisateur existe
            if ($user && $passwordHasher->isPasswordValid($user, $password)) {
                // L'authentification réussit
                return $this->redirectToRoute('robot_shop');  
            } else {
                // Échec de l'authentification
                $error = 'Identifiant ou mot de passe invalide';
            }
        }
    
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }
    

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Cette méthode sera interceptée par le gestionnaire de déconnexion
        throw new \LogicException('Cette méthode peut être vide - elle sera interceptée par le logout key sur votre firewall.');
    }
}
