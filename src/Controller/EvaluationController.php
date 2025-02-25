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
use App\Repository\ReponseRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Service\StatisticsService;

#[Route('/evaluation')]
final class EvaluationController extends AbstractController
{
    private StatisticsService $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    // Routes pour l'enseignant
    #[Route('/enseignant', name: 'app_evaluation_indexenseignant', methods: ['GET'])]
    public function indexEnseignant(EvaluationRepository $evaluationRepository): Response
    {
        return $this->render('evaluation/indexenseignant.html.twig', [
            'evaluations' => $evaluationRepository->findAll(),
        ]);
    }

    #[Route('/enseignant/new', name: 'app_evaluation_newenseignant', methods: ['GET', 'POST'])]
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

    #[Route('/enseignant/{id}', name: 'app_evaluation_showenseignant', methods: ['GET'])]
    public function showEnseignant(Evaluation $evaluation): Response
    {
        return $this->render('evaluation/show.html.twig', [
            'evaluation' => $evaluation,
        ]);
    }

    #[Route('/enseignant/{id}/edit', name: 'app_evaluation_editenseignant', methods: ['GET', 'POST'])]
    public function editEnseignant(Request $request, Evaluation $evaluation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_evaluation_indexenseignant', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evaluation/edit.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/enseignantremove/{id}', name: 'app_evaluation_showenseignantremove', methods: ['GET', 'POST'])]
    public function showEnseignantRemove(
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
    
    #[Route('/enseignant/{id}', name: 'app_evaluation_deleteenseignant', methods: ['POST'])]
    public function deleteEnseignant(Request $request, Evaluation $evaluation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $evaluation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evaluation);
            $entityManager->flush();
            $this->addFlash('success', 'L\'évaluation a été supprimée avec succès.');
        }

        return $this->redirectToRoute('app_evaluation_indexenseignant', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/enseignant/{id}/correct', name: 'evaluation_correctenseignant', methods: ['GET'])]
    public function correctListEnseignant(
        Evaluation $evaluation,
        ReponseRepository $reponseRepository
    ): Response {
        // Récupérer toutes les réponses pour cette évaluation via les questions
        $reponses = [];
        foreach ($evaluation->getQuestions() as $question) {
            $questionReponses = $reponseRepository->findBy(['question' => $question]);
            $reponses = array_merge($reponses, $questionReponses);
        }

        // Organiser les réponses par utilisateur
        $userResponses = [];
        foreach ($reponses as $reponse) {
            $userId = $reponse->getUserId();
            if (!isset($userResponses[$userId])) {
                $userResponses[$userId] = [
                    'userId' => $userId,
                    'reponses' => [],
                    'isCorrected' => true
                ];
            }
            $userResponses[$userId]['reponses'][] = $reponse;
            if (!$reponse->isCorrect()) {
                $userResponses[$userId]['isCorrected'] = false;
            }
        }

        return $this->render('evaluation/correct_list.html.twig', [
            'evaluation' => $evaluation,
            'userResponses' => $userResponses
        ]);
    }

    #[Route('/enseignant/{evaluationId}/etudiant/{studentId}/correct', name: 'evaluation_correctenseignant_student', methods: ['GET'])]
    public function correctStudent(
        int $evaluationId,
        int $studentId,
        EvaluationRepository $evaluationRepository,
        ReponseRepository $reponseRepository
    ): Response {
        $evaluation = $evaluationRepository->find($evaluationId);
        if (!$evaluation) {
            throw $this->createNotFoundException('Evaluation non trouvée');
        }

        // Récupérer toutes les réponses de l'étudiant pour cette évaluation
        $reponses = [];
        foreach ($evaluation->getQuestions() as $question) {
            $questionReponses = $reponseRepository->findBy([
                'question' => $question,
                'userId' => $studentId
            ]);
            $reponses = array_merge($reponses, $questionReponses);
        }

        return $this->render('evaluation/correct_student.html.twig', [
            'evaluation' => $evaluation,
            'studentId' => $studentId,
            'reponses' => $reponses
        ]);
    }

    #[Route('/enseignant/evaluation/{evaluationId}/etudiant/{studentId}/save-notes', name: 'evaluation_save_notes', methods: ['POST'])]
    public function saveNotes(
        Request $request,
        int $evaluationId,
        int $studentId,
        EvaluationRepository $evaluationRepository,
        ReponseRepository $reponseRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $evaluation = $evaluationRepository->find($evaluationId);
        if (!$evaluation) {
            throw $this->createNotFoundException('Evaluation non trouvée');
        }

        $submittedData = $request->request->all();
        $notes = isset($submittedData['notes']) ? $submittedData['notes'] : [];
        
        foreach ($notes as $reponseId => $note) {
            $reponse = $reponseRepository->find($reponseId);
            if ($reponse && $reponse->getUserId() === $studentId) {
                $reponse->setNote((float) $note);
                $reponse->setIsCorrect(true);
                $entityManager->persist($reponse);
            }
        }
        
        $entityManager->flush();

        $this->addFlash('success', 'Les notes ont été enregistrées avec succès');
        return $this->redirectToRoute('evaluation_correctenseignant', [
            'id' => $evaluationId
        ]);
    }

    #[Route('/enseignant/{evaluationId}/correct/{studentId}', name: 'app_evaluation_correct_student', methods: ['GET'])]
    public function correctStudentEnseignant(
        int $evaluationId,
        int $studentId,
        EvaluationRepository $evaluationRepository,
        ReponseRepository $reponseRepository
    ): Response {
        $evaluation = $evaluationRepository->find($evaluationId);

        if (!$evaluation) {
            throw $this->createNotFoundException('Évaluation non trouvée');
        }

        // Récupérer toutes les réponses de l'étudiant pour cette évaluation
        $answers = $reponseRepository->findBy(
            ['evaluation' => $evaluation, 'userId' => $studentId],
            ['question' => 'ASC']
        );

        return $this->render('evaluation/correct_student.html.twig', [
            'evaluation' => $evaluation,
            'studentId' => $studentId,
            'answers' => $answers
        ]);
    }

    #[Route('/enseignant/{evaluationId}/save-correction/{studentId}', name: 'evaluation_save_correctionenseignant', methods: ['POST'])]
    public function saveCorrectionEnseignant(
        Request $request,
        int $evaluationId,
        int $studentId,
        EntityManagerInterface $entityManager,
        ReponseRepository $reponseRepository
    ): Response {
        $points = $request->request->get('points', []);
        $comments = $request->request->get('comments', []);

        foreach ($points as $answerId => $score) {
            $answer = $reponseRepository->find($answerId);
            if ($answer) {
                $answer->setScore($score);
                $answer->setComment($comments[$answerId] ?? '');
                $entityManager->persist($answer);
            }
        }

        $entityManager->flush();

        $this->addFlash('success', 'La correction a été enregistrée avec succès.');
        return $this->redirectToRoute('evaluation_correctenseignant', ['id' => $evaluationId]);
    }

    #[Route('/etudiant/{id}/resultat', name: 'app_evaluation_show_result', methods: ['GET'])]
    public function showResult(
        Evaluation $evaluation,
        ReponseRepository $reponseRepository,
        Security $security
    ): Response {
        $user = $security->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer les réponses de l'étudiant pour cette évaluation
        $reponses = [];
        foreach ($evaluation->getQuestions() as $question) {
            $questionReponses = $reponseRepository->findBy([
                'question' => $question,
                'userId' => $user->getId()
            ]);
            $reponses = array_merge($reponses, $questionReponses);
        }

        // Calculer le score total
        $totalScore = 0;
        foreach ($reponses as $reponse) {
            if ($reponse->getNote() !== null) {
                $totalScore += $reponse->getNote();
            }
        }

        return $this->render('evaluation/show_result.html.twig', [
            'evaluation' => $evaluation,
            'reponses' => $reponses,
            'totalScore' => $totalScore
        ]);
    }

    #[Route('/etudiant/{id}', name: 'etudiant_evaluation_show', methods: ['GET', 'POST'])]
    public function showEtudiant(Request $request, Evaluation $evaluation, QuestionRepository $questionRepository, EntityManagerInterface $entityManager): Response
    {
        $questionsArray = $questionRepository->findBy(['evaluation' => $evaluation], ['ordreQuestion' => 'ASC']);
        
        if (empty($questionsArray)) {
            $this->addFlash('warning', 'Aucune question n\'est disponible pour cette évaluation.');
            return $this->redirectToRoute('app_evaluation_indexetudiant');
        }

        if ($request->isMethod('POST')) {
            $responses = $request->request->all()['responses'] ?? [];
            foreach ($questionsArray as $question) {
                $responseContent = $responses[$question->getId()] ?? null;
                if ($responseContent) {
                    $reponse = new Reponse();
                    $reponse->setQuestion($question);
                    $reponse->setText($responseContent);
                    $reponse->setEvaluation($evaluation);
                    $entityManager->persist($reponse);
                }
            }
    
            $entityManager->flush();
            $this->addFlash('success', 'Vos réponses ont été enregistrées avec succès.');
            return $this->redirectToRoute('app_evaluation_indexetudiant');
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

    private function isEvaluationGradedForStudent(Evaluation $evaluation, User $student): bool
    {
        // Vérifier si toutes les réponses ont un score
        foreach ($evaluation->getQuestions() as $question) {
            $response = $question->getReponseForStudent($student);
            if (!$response || $response->getScore() === null) {
                return false;
            }
        }
        return true;
    }

    private function calculateStudentScore(Evaluation $evaluation, User $student): int
    {
        $totalScore = 0;
        foreach ($evaluation->getQuestions() as $question) {
            $response = $question->getReponseForStudent($student);
            if ($response && $response->getScore() !== null) {
                $totalScore += $response->getScore();
            }
        }
        return $totalScore;
    }
    
    // Routes pour l'étudiant
    #[Route('/etudiant', name: 'app_evaluation_indexetudiant', methods: ['GET'])]
    public function indexEtudiant(
        EvaluationRepository $evaluationRepository,
        ReponseRepository $reponseRepository
    ): Response {
        $evaluations = $evaluationRepository->findAll();
        $submittedEvaluations = [];

        return $this->render('evaluation/indexetudiant.html.twig', [
            'evaluations' => $evaluations,
            'submittedEvaluations' => $submittedEvaluations
        ]);
    }

    private function isEvaluationCorrected(Evaluation $evaluation, int $studentId, ReponseRepository $reponseRepository): bool
    {
        $allResponses = [];
        foreach ($evaluation->getQuestions() as $question) {
            $responses = $reponseRepository->findBy([
                'question' => $question,
                'userId' => $studentId
            ]);
            $allResponses = array_merge($allResponses, $responses);
        }

        if (empty($allResponses)) {
            return false;
        }

        foreach ($allResponses as $response) {
            if (!$response->isCorrect()) {
                return false;
            }
        }

        return true;
    }

    #[Route('/enseignant/{id}/statistics', name: 'app_evaluation_statistics', methods: ['GET'])]
    public function showStatistics(Evaluation $evaluation, ReponseRepository $reponseRepository): Response
    {
        $statistics = $this->statisticsService->calculateStatistics($evaluation);
        $reponses = $reponseRepository->findBy(['evaluation' => $evaluation]);
        $totalScore = 0;
        foreach ($reponses as $reponse) {
            $totalScore += $reponse->getNote() ?? 0;
        }
        
        return $this->render('evaluation/show_result.html.twig', [
            'evaluation' => $evaluation,
            'statistics' => $statistics,
            'reponses' => $reponses,
            'totalScore' => $totalScore
        ]);
    }
}
