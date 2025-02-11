<?php

namespace App\Controller;

use App\Entity\UserQuizSelection;
use App\Form\UserQuizSelectionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user/quiz/selection')]
final class UserQuizSelectionController extends AbstractController
{
    #[Route(name: 'app_user_quiz_selection_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $userQuizSelections = $entityManager
            ->getRepository(UserQuizSelection::class)
            ->findAll();

        return $this->render('user_quiz_selection/index.html.twig', [
            'user_quiz_selections' => $userQuizSelections,
        ]);
    }

    #[Route('/new', name: 'app_user_quiz_selection_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userQuizSelection = new UserQuizSelection();
        $form = $this->createForm(UserQuizSelectionType::class, $userQuizSelection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($userQuizSelection);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_quiz_selection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_quiz_selection/new.html.twig', [
            'user_quiz_selection' => $userQuizSelection,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_quiz_selection_show', methods: ['GET'])]
    public function show(UserQuizSelection $userQuizSelection): Response
    {
        return $this->render('user_quiz_selection/show.html.twig', [
            'user_quiz_selection' => $userQuizSelection,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_quiz_selection_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserQuizSelection $userQuizSelection, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserQuizSelectionType::class, $userQuizSelection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_quiz_selection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_quiz_selection/edit.html.twig', [
            'user_quiz_selection' => $userQuizSelection,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_quiz_selection_delete', methods: ['POST'])]
    public function delete(Request $request, UserQuizSelection $userQuizSelection, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userQuizSelection->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($userQuizSelection);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_quiz_selection_index', [], Response::HTTP_SEE_OTHER);
    }
}
