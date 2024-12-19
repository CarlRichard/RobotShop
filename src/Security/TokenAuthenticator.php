<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class TokenAuthenticator extends AbstractAuthenticator
{
    public function supports(Request $request): ?bool
    {
        // Vérifie si un token existe dans l'en-tête Authorization
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        // Récupère le token depuis l'en-tête Authorization
        $authorizationHeader = $request->headers->get('Authorization');
        if (!$authorizationHeader || !str_starts_with($authorizationHeader, 'Bearer ')) {
            throw new AuthenticationException('Token manquant ou invalide.');
        }

        // Extraire et nettoyer le token
        $token = substr($authorizationHeader, 7);

        // Ici, ajoute une logique pour vérifier ton token
        // Par exemple, décode un JWT ou vérifie une base de données

        // Exemple simplifié : utiliser l'email comme token (à adapter selon ton besoin)
        return new SelfValidatingPassport(new UserBadge($token));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return new Response('Authentification échouée.', Response::HTTP_UNAUTHORIZED);
    }

    public function onAuthenticationSuccess(Request $request, $token, string $firewallName): ?Response
    {
        // Pas besoin de réponse spécifique, continue l'exécution
        return null;
    }
}
