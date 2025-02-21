<?php
namespace App\Controller;

use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Entity\Evaluation;
use App\Form\EvaluationType;
use App\Entity\Question;
use App\Form\QuestionType;
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
    #[Route('/enseignant', name: 'app_evaluation_index_enseignant', methods: ['GET'])]
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
    
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // S'assurer que les champs requis ne sont pas vides
                if (empty(trim($evaluation->getTitle()))) {
                    $form->get('title')->addError(new FormError('⚠️ Le titre est obligatoire'));
                } else {
                    $entityManager->persist($evaluation);
                    $entityManager->flush();
                    $this->addFlash('success', 'L\'évaluation a été créée avec succès.');
                    return $this->redirectToRoute('app_question_new', ['evaluationId' => $evaluation->getId()], Response::HTTP_SEE_OTHER);
                }
            }
        }
    
        return $this->render('evaluation/new.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/enseignant/{id}/show', name: 'app_evaluation_show', methods: ['GET'])]
    public function show(Evaluation $evaluation, EntityManagerInterface $entityManager): Response
    {
        // Récupérer toutes les réponses pour cette évaluation
        $reponses = $entityManager->getRepository(Reponse::class)
            ->createQueryBuilder('r')
            ->join('r.question', 'q')
            ->where('q.evaluation = :evaluation')
            ->setParameter('evaluation', $evaluation)
            ->orderBy('r.userId', 'ASC')
            ->addOrderBy('q.ordreQuestion', 'ASC')
            ->getQuery()
            ->getResult();

        // Grouper les réponses par étudiant
        $reponsesParEtudiant = [];
        foreach ($reponses as $reponse) {
            $userId = $reponse->getUserId();
            if (!isset($reponsesParEtudiant[$userId])) {
                $reponsesParEtudiant[$userId] = [
                    'reponses' => [],
                    'total_note' => 0,
                    'total_possible' => 0
                ];
            }
            $reponsesParEtudiant[$userId]['reponses'][] = $reponse;
            $reponsesParEtudiant[$userId]['total_note'] += $reponse->getNote() ?? 0;
            $reponsesParEtudiant[$userId]['total_possible'] += $reponse->getQuestion()->getPoint();
        }

        return $this->render('evaluation/show.html.twig', [
            'evaluation' => $evaluation,
            'reponsesParEtudiant' => $reponsesParEtudiant
        ]);
    }

    #[Route('/enseignantremove/{id}', name: 'app_evaluation_delete', methods: ['GET'])]
    public function delete(Request $request, Evaluation $evaluation, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($evaluation);
        $entityManager->flush();

        $this->addFlash('success', 'L\'évaluation a été supprimée avec succès.');
        return $this->redirectToRoute('app_evaluation_index_enseignant');
    }
    
    #[Route('/enseignant/{id}/edit', name: 'app_evaluation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $evaluation = $entityManager->getRepository(Evaluation::class)->find($id);
        
        if (!$evaluation) {
            throw $this->createNotFoundException('L\'évaluation demandée n\'existe pas.');
        }

        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'L\'évaluation a été modifiée avec succès.');
            return $this->redirectToRoute('app_evaluation_index_enseignant');
        }

        return $this->render('evaluation/edit.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/enseignant/{id}/corriger', name: 'app_evaluation_corriger', methods: ['GET'])]
    public function corriger(int $id, EntityManagerInterface $entityManager): Response
    {
        $evaluation = $entityManager->getRepository(Evaluation::class)->find($id);
        
        if (!$evaluation) {
            throw $this->createNotFoundException('L\'évaluation demandée n\'existe pas.');
        }

        // Récupérer toutes les réponses pour cette évaluation avec les notes
        $query = $entityManager->createQuery(
            'SELECT DISTINCT r.userId, SUM(COALESCE(r.note, 0)) as totalScore
            FROM App\Entity\Reponse r
            JOIN r.question q
            WHERE q.evaluation = :evaluation
            GROUP BY r.userId'
        )
        ->setParameter('evaluation', $evaluation);

        $results = $query->getResult();

        // Organiser les données par étudiant
        $etudiants = [];
        foreach ($results as $result) {
            $etudiants[$result['userId']] = [
                'id' => $result['userId'],
                'score' => (int)$result['totalScore'],
                'scoreMax' => $evaluation->getMaxScore()
            ];
        }

        return $this->render('evaluation/corriger.html.twig', [
            'evaluation' => $evaluation,
            'etudiants' => $etudiants
        ]);
    }

    #[Route('/enseignant/{id}/corriger/{userId}', name: 'app_evaluation_corriger_etudiant', methods: ['GET', 'POST'])]
    public function corrigerEtudiant(Request $request, Evaluation $evaluation, int $userId, EntityManagerInterface $entityManager): Response
    {
        // Récupérer toutes les questions de l'évaluation avec leurs réponses pour cet étudiant
        $questions = $entityManager->getRepository(Question::class)
            ->createQueryBuilder('q')
            ->addSelect('r')
            ->leftJoin('q.reponses', 'r')
            ->where('q.evaluation = :evaluation')
            ->andWhere('r.userId = :userId OR r.userId IS NULL')
            ->setParameter('evaluation', $evaluation)
            ->setParameter('userId', $userId)
            ->orderBy('q.ordreQuestion', 'ASC')
            ->getQuery()
            ->getResult();

        // Traiter la soumission des notes
        if ($request->isMethod('POST')) {
            $notesData = $request->request->all();
            $notes = isset($notesData['notes']) ? $notesData['notes'] : [];
            
            foreach ($notes as $reponseId => $note) {
                $reponse = $entityManager->getRepository(Reponse::class)->find($reponseId);
                if ($reponse && $note !== null) {
                    // Vérifier que la note ne dépasse pas le maximum
                    $noteValue = min((float)$note, $reponse->getQuestion()->getPoint());
                    $reponse->setNote($noteValue);
                    $entityManager->persist($reponse);
                }
            }
            
            $entityManager->flush();
            
            $this->addFlash('success', 'Les notes ont été enregistrées avec succès.');
            return $this->redirectToRoute('app_evaluation_corriger', ['id' => $evaluation->getId()]);
        }

        return $this->render('evaluation/corriger_etudiant.html.twig', [
            'evaluation' => $evaluation,
            'questions' => $questions,
            'userId' => $userId
        ]);
    }

    // Routes pour l'étudiant
    #[Route('/etudiant', name: 'app_evaluation_index_etudiant', methods: ['GET'])]
    public function indexEtudiant(EntityManagerInterface $entityManager): Response
    {
        // ID utilisateur fixe pour le moment
        $userId = 12346;

        $evaluations = $entityManager->getRepository(Evaluation::class)
            ->findBy([], ['deadline' => 'DESC']);

        return $this->render('evaluation/index_etudiant.html.twig', [
            'evaluations' => $evaluations,
            'userId' => $userId
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
            return $this->redirectToRoute('etudiant_evaluation_index');
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
        if (!$evaluation) {
            throw $this->createNotFoundException('Évaluation non trouvée');
        }

        $existingResponses = $entityManager->getRepository(Reponse::class)
            ->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->join('r.question', 'q')
            ->where('q.evaluation = :evaluation')
            ->andWhere('r.userId = :userId')
            ->setParameter('evaluation', $evaluation)
            ->setParameter('userId', $this->getUser()->getId())
            ->getQuery()
            ->getSingleScalarResult();

        if ($existingResponses > 0) {
            $this->addFlash('error', 'Vous avez déjà répondu à cette évaluation.');
            return $this->redirectToRoute('etudiant_evaluation_index');
        }

        $questions = $entityManager->getRepository(Question::class)
            ->findBy(['evaluation' => $evaluation], ['ordreQuestion' => 'ASC']);

        if ($request->isMethod('POST')) {
            $formData = $request->request->all();
            $reponses = isset($formData['answers']) ? $formData['answers'] : [];
            
            foreach ($questions as $question) {
                if (isset($reponses[$question->getId()])) {
                    $reponse = new Reponse();
                    $reponse->setQuestion($question);
                    $reponse->setText($reponses[$question->getId()]);
                    $reponse->setUserId($this->getUser()->getId());
                    $entityManager->persist($reponse);
                }
            }
            
            $entityManager->flush();
            
            $this->addFlash('success', 'Vos réponses ont été enregistrées avec succès.');
            return $this->redirectToRoute('etudiant_evaluation_index');
        }

        return $this->render('evaluation/answer.html.twig', [
            'evaluation' => $evaluation,
            'questions' => $questions
        ]);
    }

    #[Route('/etudiant/{id}/resultats', name: 'app_evaluation_show_resultats', methods: ['GET'])]
    public function showResultats(Evaluation $evaluation, EntityManagerInterface $entityManager): Response
    {
        $userId = $this->getUser()->getId();
        
        // Récupérer toutes les questions et réponses de l'étudiant
        $questions = $entityManager->getRepository(Question::class)
            ->createQueryBuilder('q')
            ->leftJoin('q.reponses', 'r')
            ->where('q.evaluation = :evaluation')
            ->andWhere('r.userId = :userId OR r.userId IS NULL')
            ->setParameter('evaluation', $evaluation)
            ->setParameter('userId', $userId)
            ->orderBy('q.ordreQuestion', 'ASC')
            ->getQuery()
            ->getResult();

        // Calculer le score total
        $scoreTotal = 0;
        $scorePossible = 0;
        $toutesReponsesCorrigees = true;

        foreach ($questions as $question) {
            $scorePossible += $question->getPoint();
            foreach ($question->getReponses() as $reponse) {
                if ($reponse->getUserId() == $userId) {
                    if ($reponse->getNote() === null) {
                        $toutesReponsesCorrigees = false;
                    } else {
                        $scoreTotal += $reponse->getNote();
                    }
                }
            }
        }

        if (!$toutesReponsesCorrigees) {
            $this->addFlash('info', 'Vos réponses n\'ont pas encore été corrigées.');
            return $this->redirectToRoute('app_evaluation_index_etudiant');
        }

        return $this->render('evaluation/resultats.html.twig', [
            'evaluation' => $evaluation,
            'questions' => $questions,
            'scoreTotal' => $scoreTotal,
            'scorePossible' => $scorePossible,
            'userId' => $userId
        ]);
    }

    #[Route('/etudiant/{id}/mes-reponses', name: 'app_evaluation_mes_reponses', methods: ['GET'])]
    public function mesReponses(Evaluation $evaluation, EntityManagerInterface $entityManager): Response
    {
        $userId = $this->getUser()->getId();
        
        // Récupérer les questions et les réponses de l'étudiant
        $questions = $entityManager->getRepository(Question::class)
            ->createQueryBuilder('q')
            ->leftJoin('q.reponses', 'r')
            ->where('q.evaluation = :evaluation')
            ->andWhere('r.userId = :userId OR r.userId IS NULL')
            ->setParameter('evaluation', $evaluation)
            ->setParameter('userId', $userId)
            ->orderBy('q.ordreQuestion', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('evaluation/mes_reponses.html.twig', [
            'evaluation' => $evaluation,
            'questions' => $questions,
            'userId' => $userId
        ]);
    }

    #[Route('/etudiant/mes-reponses', name: 'app_evaluation_mes_reponses_list', methods: ['GET'])]
    public function mesReponsesList(EntityManagerInterface $entityManager): Response
    {
        $userId = 12346; // ID utilisateur temporaire

        $evaluations = $entityManager->createQuery(
            'SELECT DISTINCT e, q, r
            FROM App\Entity\Evaluation e
            JOIN e.Questions q
            LEFT JOIN q.reponses r
            WHERE r.userId = :userId
            ORDER BY e.deadline DESC'
        )
        ->setParameter('userId', $userId)
        ->getResult();

        return $this->render('evaluation/mes_reponses.html.twig', [
            'evaluations' => $evaluations
        ]);
    }

    #[Route('/mes-reponses/{id}', name: 'app_evaluation_mes_reponses_detail', methods: ['GET'])]
    public function mesReponsesDetail(Evaluation $evaluation, EntityManagerInterface $entityManager): Response
    {
        $userId = 12346; // ID utilisateur temporaire

        // Récupérer toutes les questions de l'évaluation avec les réponses de l'étudiant
        $questions = $entityManager->getRepository(Question::class)
            ->createQueryBuilder('q')
            ->addSelect('r')
            ->leftJoin('q.reponses', 'r')
            ->where('q.evaluation = :evaluation')
            ->andWhere('r.userId = :userId OR r.userId IS NULL')
            ->setParameter('evaluation', $evaluation)
            ->setParameter('userId', $userId)
            ->orderBy('q.ordreQuestion', 'ASC')
            ->getQuery()
            ->getResult();

        // Calculer le score total
        $scoreTotal = 0;
        foreach ($questions as $question) {
            foreach ($question->getReponses() as $reponse) {
                if ($reponse->getUserId() === $userId && $reponse->getNote() !== null) {
                    $scoreTotal += $reponse->getNote();
                }
            }
        }

        return $this->render('evaluation/mes_reponses.html.twig', [
            'evaluation' => $evaluation,
            'questions' => $questions,
            'userId' => $userId,
            'scoreTotal' => $scoreTotal
        ]);
    }
}
