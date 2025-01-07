<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Robot;
use App\Form\CartType;
use App\Repository\CartRepository;
use App\Repository\RobotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\DiscountCodeRepository;

#[Route('/cart')]
final class CartController extends AbstractController
{
    #[Route('/add/{id}', name: 'cart_add')]
    public function add(Robot $robot, SessionInterface $session, Request $request): Response
    {
        $id = $robot->getId();
        $panier = $session->get('panier', []);

        if (isset($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);
        $this->addFlash('success', 'Produit ajouté au panier !');

        return $this->redirectToRoute('cart_index');
    }

    #[Route(name: 'cart_index', methods: ['GET'])]
    public function index(SessionInterface $session, RobotRepository $robotRepository, DiscountCodeRepository $discountCodeRepository): Response
    {
        $panier = $session->get('panier', []);
        $discountCode = isset($panier['discount_code']) ? $discountCodeRepository->findOneBy(['code' => $panier['discount_code']]) : null;
    
        // Initialisation des variables
        $data = [];
        $total = 0;
    
        foreach ($panier as $id => $quantity) {
            if ($id === 'discount_code') {
                continue;
            }
    
            $robot = $robotRepository->find($id);
    
            if (!$robot) {
                unset($panier[$id]);
                $session->set('panier', $panier);
                continue;
            }
    
            $data[] = [
                'robot' => $robot,
                'quantity' => $quantity,
            ];
            $total += $robot->getPrice() * $quantity;
        }
    
        // Appliquer la réduction
        $discountAmount = 0;
        if ($discountCode) {
            $discountAmount = $discountCode->isPercentage()
                ? $total * ($discountCode->getDiscount() / 100)
                : $discountCode->getDiscount();
    
            if ($total >= $discountCode->getMinimumOrderAmount()) {
                $total -= $discountAmount;
            }
        }
    
        return $this->render('cart/index.html.twig', [
            'data' => $data,
            'total' => max($total, 0), // Pas de total négatif
            'discount_code' => $discountCode ? $discountCode->getCode() : null,
            'discountAmount' => $discountAmount,
        ]);
    }
    

    #[Route('/remove/{id}', name: 'cart_remove')]
    public function remove(Robot $robot, SessionInterface $session): Response
    {
        $id = $robot->getId();
        $panier = $session->get('panier', []);

        if (isset($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                // Retirer 
                unset($panier[$id]);
            }
            // MàJ
            $session->set('panier', $panier);
        }

        // Rediriger vers la page du panier
        return $this->redirectToRoute('cart_index');
    }


    #[Route('/delete/{id}', name: 'cart_delete')]
    public function delete(Robot $robot, SessionInterface $session): Response
    {
        $id = $robot->getId();
        $panier = $session->get('panier', []);

        // Supprimer uniquement l'article associé à l'ID
        if (isset($panier[$id])) {
            unset($panier[$id]);
        }

        // Mettre à jour la session avec le panier modifié
        $session->set('panier', $panier);

        return $this->redirectToRoute('cart_index');
    }

    #[Route('/apply-discount', name: 'cart_apply_discount', methods: ['POST'])]
    public function applyDiscount(
        Request $request,
        DiscountCodeRepository $discountCodeRepository,
        SessionInterface $session
    ): RedirectResponse {
        $code = $request->request->get('discount_code');
        $discountCode = $discountCodeRepository->findOneBy(['code' => $code]);
    
        if (!$discountCode) {
            $this->addFlash('error', 'Code promo invalide.');
            return $this->redirectToRoute('cart_index');
        }
    
        // Récupérer le panier de la session
        $panier = $session->get('panier', []);
    
        // Ajouter le code promo au panier
        $panier['discount_code'] = $discountCode->getCode();
        $session->set('panier', $panier);
    
        $this->addFlash('success', 'Code promo appliqué avec succès.');
        return $this->redirectToRoute('cart_index');
    }

}
