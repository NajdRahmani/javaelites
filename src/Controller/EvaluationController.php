<?php
namespace App\Controller;

use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Entity\Evaluation;
use App\Form\EvaluationType;
use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Repository\EvaluationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormError;

#[Route('/evaluation')]
final class EvaluationController extends AbstractController
{
    // Routes pour l'enseignant
    #[Route('/enseignant', name: '
    enseignant', methods: ['GET'])]
    public function indexEnseignant(EvaluationRepository $evaluationRepository): Response
    {
        return $this->render('evaluation/indexenseignant.html.twig', [
            'evaluations' => $evaluationRepository->findAll(),
        ]);
    }

    #[Route('/enseignant/new', name: 'app_evaluation_new_enseignant', methods: ['GET', 'POST'])]
    public function newEnseignant(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evaluation = new Evaluation();
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evaluation);
            $entityManager->flush();
            return $this->redirectToRoute('app_question_new', ['evaluationId' => $evaluation->getId()], Response::HTTP_SEE_OTHER);
        }
        
    
        return $this->render('evaluation/new.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/enseignantremove/{id}', name: 'app_evaluation_showenseignant', methods: ['GET', 'POST'])]
    public function showEnseignant(
        Request $request, 
        Evaluation $evaluation, 
        QuestionRepository $questionRepository, 
        EntityManagerInterface $entityManager
    ): Response {
        // Récupérer les questions associées à l'évaluation
        $questions = $questionRepository->findBy(['evaluation' => $evaluation]);
    
        // Récupérer les réponses des étudiants par question
        $responsesByQuestion = [];
        foreach ($questions as $question) {
            $responsesByQuestion[$question->getId()] = $entityManager->getRepository(Reponse::class)
                ->findBy(['question' => $question]);
        }
    
        // Création d'une nouvelle question via un formulaire
        $newQuestion = new Question();
        $newQuestion->setEvaluation($evaluation);
        $form = $this->createForm(QuestionType::class, $newQuestion);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Associer un ordre de question unique
            $lastQuestion = $entityManager->getRepository(Question::class)
                ->findOneBy(['evaluation' => $evaluation], ['ordreQuestion' => 'DESC']);
            $newOrder = $lastQuestion ? $lastQuestion->getOrdreQuestion() + 1 : 1;
            $newQuestion->setOrdreQuestion($newOrder);
    
            // Sauvegarder la nouvelle question
            $entityManager->persist($newQuestion);
            $entityManager->flush();
    
            $this->addFlash('success', 'La question a été ajoutée avec succès.');
    
            return $this->redirectToRoute('app_evaluation_showenseignant', ['id' => $evaluation->getId()]);
        }
    
        return $this->render('evaluation/showenseignant.html.twig', [
            'evaluation' => $evaluation,
            'questions' => $questions,
            'responsesByQuestion' => $responsesByQuestion,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/enseignant/{id}/edit', name: 'app_evaluation_edit', methods: ['GET', 'POST'])]

    public function editEnseignant(Request $request, Evaluation $evaluation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evaluation);
            $entityManager->flush();
            $this->addFlash('success', 'Évaluation mise à jour avec succès.');
            return $this->redirectToRoute('app_evaluation_showenseignant', ['id' => $evaluation->getId()], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('evaluation/edit.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/enseignant/{id}', name: 'app_evaluation_delete', methods: ['POST'])]
    public function deleteEnseignant(Request $request, Evaluation $evaluation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $evaluation->getId(), $request->get('_token'))) {
            $entityManager->remove($evaluation);
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_evaluation_indexenseignant', [], Response::HTTP_SEE_OTHER);
    }
    
    // Routes pour l'étudiant
    #[Route('/etudiant', name: 'etudiant_evaluation_index', methods: ['GET'])]
    public function indexEtudiant(EvaluationRepository $evaluationRepository): Response
    {
        return $this->render('evaluation/index.html.twig', [
            'evaluations' => $evaluationRepository->findAll(),
        ]);
    }

    #[Route('/etudiant/{id}', name: 'etudiant_evaluation_show', methods: ['GET'])]
    public function showEtudiant(Request $request, Evaluation $evaluation, QuestionRepository $questionRepository, EntityManagerInterface $entityManager): Response
    {
        $questionsArray = $questionRepository->findBy(['evaluation' => $evaluation], ['orderQuestion' => 'ASC']);
        
        if (empty($questionsArray)) {
            $this->addFlash('warning', 'Aucune question n\'est disponible pour cette évaluation.');
            return $this->redirectToRoute('app_evaluation_index');
        }

        if ($request->isMethod('POST')) {
            $responses = $request->request->get('responses', []);
            foreach ($questionsArray as $question) {
                if (isset($responses[$question->getId()])) {
                    $reponse = new Reponse();
                    $reponse->setQuestion($question);
                    $reponse->setContent($responses[$question->getId()]);
                    $reponse->setEvaluation($evaluation);
                    $reponse->setUser($this->getUser());
                    $entityManager->persist($reponse);
                }
            }
    
            $entityManager->flush();
            $this->addFlash('success', 'Vos réponses ont été enregistrées avec succès.');
            return $this->redirectToRoute('etudiant_evaluation_show', ['id' => $evaluation->getId()]);
        }

        return $this->render('evaluation/showEtudiant.html.twig', [
            'evaluation' => $evaluation,
            'questions' => $questionsArray,
        ]);
    }

    #[Route('/etudiant/{evaluationId}/repondre', name: 'etudiant_evaluation_answer', methods: ['GET', 'POST'])]
    public function answerEtudiant(int $evaluationId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $evaluation = $entityManager->getRepository(Evaluation::class)->find($evaluationId);
        $questions = $entityManager->getRepository(Question::class)->findBy(['evaluation' => $evaluation]);

        if (!$evaluation) {
            throw $this->createNotFoundException('Évaluation non trouvée');
        }

        $responses = [];
        foreach ($questions as $question) {
            $response = new Reponse();
            $response->setQuestion($question);
            $responses[] = $response;
        }

        $form = $this->createForm(ReponseType::class, $responses);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($responses as $response) {
                $entityManager->persist($response);
            }
            $entityManager->flush();
            return $this->redirectToRoute('etudiant_evaluation_results', ['evaluationId' => $evaluationId]);
        }

        return $this->render('evaluation/answer.html.twig', [
            'evaluation' => $evaluation,
            'questions' => $questions,
            'form' => $form->createView(),
        ]);
    }
}

