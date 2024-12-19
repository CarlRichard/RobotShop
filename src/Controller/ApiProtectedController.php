<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiProtectedController extends AbstractController
{
    #[Route('/api/protected', name: 'api_protected', methods: ['GET'])]
    public function protectedRoute(): JsonResponse
    {
        return $this->json([
            'message' => 'Accès autorisé. Vous êtes authentifié.',
        ]);
    }
}
