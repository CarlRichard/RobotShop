<?php

namespace App\Controller;

use App\Entity\Robot;
use App\Form\RobotType;
use App\Repository\CategoryRepository;
use App\Repository\RobotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/robot')]
final class RobotController extends AbstractController
{
    #[Route(name: 'app_robot_index', methods: ['GET'])]
    public function index(RobotRepository $robotRepository): Response
    {
        return $this->render('robot/index.html.twig', [
            'robots' => $robotRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_robot_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $robot = new Robot();
        $form = $this->createForm(RobotType::class, $robot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($robot);
            $entityManager->flush();

            return $this->redirectToRoute('app_robot_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('robot/new.html.twig', [
            'robot' => $robot,
            'form' => $form,
        ]);
    }

    #[Route('/shop', name: 'robot_shop')]
    public function shop(Request $request, RobotRepository $robotRepository, CategoryRepository $categoryRepository): Response
    {
        // Récupération de la page dans l'URL ou défaut à la première page
        $page = $request->query->getInt('page', 1);
        
        // Récupérer le filtre de catégorie depuis l'URL
        $categoryId = $request->query->getInt('category_id', 0);  // Si pas de catégorie spécifiée, utiliser 0
        
        // Récupérer toutes les catégories
        $categories = $categoryRepository->findAll();
    
        // Si une catégorie est sélectionnée et valide, récupérer les robots filtrés
        if ($categoryId > 0) {
            // Filtrer par catégorie
            $robots = $robotRepository->findByCategory($categoryId);
        } else {
            // Récupérer tous les robots (si pas de filtre ou catégorie invalide)
            $robots = $robotRepository->paginate($request);
        }
    
        // Pagination des robots (9 robots par page)
        $totalRobots = count($robots); // Total des robots après filtre
        $maxPage = ceil($totalRobots / 9); 
    
        return $this->render('robot/shop.html.twig', [
            'robots' => $robots,
            'maxPage' => $maxPage,
            'page' => $page,
            'categories' => $categories,
            'category_id' => $categoryId, // Passer l'ID de la catégorie sélectionnée pour pré-sélectionner le filtre
        ]);
    }
    
    
    

    #[Route('/{id}', name: 'app_robot_show', methods: ['GET'])]
    public function show(Robot $robot): Response
    {
        return $this->render('robot/show.html.twig', [
            'robot' => $robot,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_robot_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Robot $robot, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RobotType::class, $robot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_robot_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('robot/edit.html.twig', [
            'robot' => $robot,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_robot_delete', methods: ['POST'])]
    public function delete(Request $request, Robot $robot, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$robot->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($robot);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_robot_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
