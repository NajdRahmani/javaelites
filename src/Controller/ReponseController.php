<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reponse')]
final class ReponseController extends AbstractController
{
    #[Route(name: 'app_reponse_index', methods: ['GET'])]
    public function index(ReponseRepository $reponseRepository): Response
    {
        return $this->render('reponse/index.html.twig', [
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

    #[Route('/new/{questionId}', name: 'app_reponse_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, QuestionRepository $questionRepository, $questionId): Response
    {
        // Récupérer la question par ID
        $question = $questionRepository->find($questionId);

        if (!$question) {
            throw $this->createNotFoundException('Question non trouvée');
        }

        // Créer une nouvelle réponse liée à la question
        $reponse = new Reponse();
        $reponse->setQuestion($question);

        // Récupérer un ID utilisateur temporaire (à remplacer par un utilisateur authentifié plus tard)
        $userId = 123; // Remplacez cela par l'ID utilisateur réel lorsque l'authentification sera en place
        $reponse->setUserId($userId);

        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification de la réponse correcte
            if ($this->isAnswerCorrect($reponse, $question)) {
                $reponse->setIsCorrect(true); // Marquer la réponse comme correcte
            } else {
                $reponse->setIsCorrect(false); // Marquer la réponse comme incorrecte
            }

            $entityManager->persist($reponse);
            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponse/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
            'question' => $question->getText(), // Passer la question à la vue
        ]);
    }

    #[Route('/{id}', name: 'app_reponse_show', methods: ['GET'])]
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reponse_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification de la réponse correcte lors de l'édition
            if ($this->isAnswerCorrect($reponse, $reponse->getQuestion())) {
                $reponse->setIsCorrect(true); // Marquer la réponse comme correcte
            } else {
                $reponse->setIsCorrect(false); // Marquer la réponse comme incorrecte
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_reponse_delete', methods: ['POST'])]
    public function delete(Request $request, Reponse $reponse, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reponse->getId(), $request->get('token'))) {
            $entityManager->remove($reponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reponse_index', [], Response::HTTP_SEE_OTHER);
    }

    // Fonction pour vérifier si la réponse est correcte
    private function isAnswerCorrect(Reponse $reponse, $question): bool
    {
        // Logique pour comparer la réponse à une réponse correcte prédéfinie
        // Exemple : la réponse correcte pourrait être définie dans l'entité Question
        // ou être une valeur associée à la question. Voici un exemple simple :
        
        $correctAnswer = $question->getCorrectAnswer(); // Supposons que la question ait une réponse correcte
        if (!$correctAnswer) {
            // Gestion de cas où il n'y a pas de réponse correcte définie pour la question
            return false;
        }

        // Comparer la réponse soumise avec la réponse correcte
        return strtolower($reponse->getText()) === strtolower($correctAnswer);
    }
}
