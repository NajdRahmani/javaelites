<?php
namespace App\Controller;

use App\Entity\Evaluation;
use App\Form\EvaluationType;
use App\Repository\EvaluationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormError;

#[Route('/evaluation')]
final class EvaluationController extends AbstractController
{
    #[Route(name: 'app_evaluation_index', methods: ['GET'])]
    public function index(EvaluationRepository $evaluationRepository): Response
    {
        return $this->render('evaluation/index.html.twig', [
            'evaluations' => $evaluationRepository->findAll(),
        ]);
    }

    #[Route('/home', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/new', name: 'app_evaluation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créer une nouvelle instance d'évaluation
        $evaluation = new Evaluation();
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);
    
        // Vérifier si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification de la date limite
            $currentDate = new \DateTime();
            if ($evaluation->getDeadline() <= $currentDate) {
                // Ajouter une erreur de validation sur le champ 'deadline' si la date limite est passée
                $form->get('deadline')->addError(new FormError('La date limite doit être supérieure à la date actuelle.'));
            } else {
                // Persister l'évaluation si la date limite est valide
                $entityManager->persist($evaluation);
                $entityManager->flush();
    
                // Rediriger vers la page de création de question en passant l'ID de l'évaluation
                return $this->redirectToRoute('app_question_new', ['evaluationId' => $evaluation->getId()], Response::HTTP_SEE_OTHER);
            }
        }
    
        // Renvoyer le formulaire si non soumis ou non valide
        return $this->render('evaluation/new.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/{id}', name: 'app_evaluation_show', methods: ['GET'])]
    public function show(Evaluation $evaluation): Response
    {
        return $this->render('evaluation/show.html.twig', [
            'evaluation' => $evaluation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evaluation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evaluation $evaluation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvaluationType::class, $evaluation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_evaluation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evaluation/edit.html.twig', [
            'evaluation' => $evaluation,
            'form' => $form,
        ]);
    }
    #[Route('/{evaluationId}/repondre', name: 'app_evaluation_answer', methods: ['GET', 'POST'])]
public function answer(int $evaluationId, Request $request, EntityManagerInterface $entityManager): Response
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

        return $this->redirectToRoute('app_evaluation_results', ['evaluationId' => $evaluationId]);
    }

    return $this->render('evaluation/answer.html.twig', [
        'evaluation' => $evaluation,
        'questions' => $questions,
        'form' => $form->createView(),
    ]);
}


    #[Route('/{id}', name: 'app_evaluation_delete', methods: ['POST'])]
    public function delete(Request $request, Evaluation $evaluation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $evaluation->getId(), $request->get('_token'))) {
            $entityManager->remove($evaluation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evaluation_index', [], Response::HTTP_SEE_OTHER);
    }
}
