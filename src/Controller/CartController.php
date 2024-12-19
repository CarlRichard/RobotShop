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

#[Route('/cart')]
final class CartController extends AbstractController
{


    #[Route('/add/{id}', name: 'cart_add')]
    public function add(Robot $robot, SessionInterface $session): Response
    {

        $id = $robot->getId();
        $panier = $session->get('panier', []);
    
        // Ajout  ou +1
        if (isset($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
    
        // MàJ
        $session->set('panier', $panier);
    
        return $this->redirectToRoute('cart_index');
    }
    

    #[Route(name: 'cart_index', methods: ['GET'])]
    public function index(SessionInterface $session, RobotRepository $robotRepository): Response
    {
        $panier = $session->get('panier', []);
    
        //init variable
        $data = [];
        $total = 0;
    
        foreach ($panier as $id => $quantity) {
            $robot = $robotRepository->find($id);
    
            if (!$robot) {
                // Si le robot n'existe pas, vous pouvez le retirer du panier
                unset($panier[$id]);
                $session->set('panier', $panier);
                continue; // Passer à l'article suivant
            }
    
            $data[] = [
                'robot' => $robot,
                'quantity' => $quantity,
            ];
            $total += $robot->getPrice() * $quantity;
        }
    
        return $this->render('cart/index.html.twig', compact('data', 'total'));
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

        if (empty($panier[$id])) {
            unset($panier[$id]);
        }
        $session->set('panier', []);

        return $this->redirectToRoute('cart_index');
    }


    // #[Route('/new', name: 'app_cart_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $cart = new Cart();
    //     $form = $this->createForm(CartType::class, $cart);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($cart);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('cart/new.html.twig', [
    //         'cart' => $cart,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_cart_show', methods: ['GET'])]
    // public function show(Cart $cart): Response
    // {
    //     return $this->render('cart/show.html.twig', [
    //         'cart' => $cart,
    //     ]);
    // }

    // #[Route('/{id}/edit', name: 'app_cart_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(CartType::class, $cart);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('cart/edit.html.twig', [
    //         'cart' => $cart,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_cart_delete', methods: ['POST'])]
    // public function delete(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete' . $cart->getId(), $request->getPayload()->getString('_token'))) {
    //         $entityManager->remove($cart);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_cart_index', [], Response::HTTP_SEE_OTHER);
    // }
}
