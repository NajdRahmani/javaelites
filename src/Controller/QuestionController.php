<?php

namespace App\Controller;

use App\Entity\Evaluation;
use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/question')]
final class QuestionController extends AbstractController
{
    #[Route('/showquestions/{evaluationId}',name: 'app_question_index', methods: ['GET'])]
    public function index($evaluationId,QuestionRepository $questionRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'évaluation (si vous souhaitez l'afficher)
        $evaluation = $entityManager->getRepository(Evaluation::class)->find($evaluationId); // Remplacez 1 par l'ID de l'évaluation, ou récupérez-la autrement

        // Si vous avez besoin de lier les questions à une évaluation spécifique
        $questions = $questionRepository->findBy(['evaluation' => $evaluation], ['id' => 'DESC']);

        return $this->render('question/index.html.twig', [
            'questions' => $questions,
            'evaluation' => $evaluation, // Passer l'évaluation à la vue
        ]);
    }

    #[Route('/new/{evaluationId}', name: 'app_question_new', methods: ['GET', 'POST'])]
    public function new(int $evaluationId, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'évaluation par son ID
        $evaluation = $entityManager->getRepository(Evaluation::class)->find($evaluationId);
    
        // Si l'évaluation n'est pas trouvée, afficher une erreur
        if (!$evaluation) {
            throw $this->createNotFoundException('Évaluation non trouvée');
        }
    
        // Créer une nouvelle question
        $question = new Question();
        
        // Associer l'évaluation à la question
        $question->setEvaluation($evaluation);
    
        // Trouver la dernière question de l'évaluation pour déterminer l'ordre
        $lastQuestion = $entityManager->getRepository(Question::class)
            ->findOneBy(['evaluation' => $evaluation], ['ordreQuestion' => 'DESC']);
        
        // Définir l'ordre de la nouvelle question
        $newOrder = $lastQuestion ? $lastQuestion->getOrdreQuestion() + 1 : 1; // Si pas de question existante, commence à 1
        $question->setOrdreQuestion($newOrder);
    
        // Créer le formulaire pour la question
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);
    
        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Persister la question dans la base de données
            $entityManager->persist($question);
            $entityManager->flush();
    
            // Rediriger vers la page des questions de l'évaluation
            return $this->redirectToRoute('app_question_index', ['evaluationId' => $evaluation->getId()], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('question/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_question_show', methods: ['GET'])]
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_question_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_question_index', ['evaluationId' => $question->getEvaluation()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

   #[Route('/{id}', name: 'app_question_delete', methods: ['POST'])]
public function delete(Request $request, Question $question, EntityManagerInterface $entityManager): Response
{
    $evaluationId = $question->getEvaluation()->getId(); // Récupération de l'évaluation associée

    if ($this->isCsrfTokenValid('delete' . $question->getId(), $request->get('token'))) {
        $entityManager->remove($question);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_question_index', ['evaluationId' => $evaluationId], Response::HTTP_SEE_OTHER);
}


    #[Route('/{id}/repondre', name: 'app_question_repondre', methods: ['GET'])]
    public function repondre(int $id, EntityManagerInterface $entityManager): Response
    {
        $question = $entityManager->getRepository(Question::class)->find($id);
        if (!$question) {
            throw $this->createNotFoundException('Question non trouvée');
        }

        return $this->redirectToRoute('app_reponse_new', ['questionId' => $id]);
    }
}
