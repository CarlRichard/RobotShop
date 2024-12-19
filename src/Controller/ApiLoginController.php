<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Entity\User;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(#[CurrentUser] ?User $user): JsonResponse
    {
        if (null === $user) {
            return $this->json([
                'message' => 'Identifiant ou mot de passe incorrect.',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        // Génère un token d'accès simple (exemple)
        $tokenPayload = [
            'email' => $user->getUserIdentifier(),
            'exp' => time() + 3600, // Expire dans 1 heure
        ];
        $token = base64_encode(json_encode($tokenPayload));

        return $this->json([
            'user'  => $user->getUserIdentifier(),
            'token' => $token,
        ]);
    }
}
