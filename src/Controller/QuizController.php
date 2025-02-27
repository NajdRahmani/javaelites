<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Entity\QuizResponse;
use App\Entity\UserQuizSelection;
use App\Entity\UserQuizResult;
use App\Entity\QuizQuestion;
use App\Entity\Response as UserResponse;
use App\Form\QuizType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quiz')]
final class QuizController extends AbstractController
{
   #[Route('/submit/{id}', name: 'quiz_submit', methods: ['POST'])]
public function submitQuiz(int $id, Request $request, EntityManagerInterface $entityManager): Response
{
    // Hardcoded user information for testing
    $username = 'Kevin'; // Pretend the username is "Kevin"

    $quiz = $entityManager->getRepository(Quiz::class)->find($id);

    if (!$quiz) {
        throw $this->createNotFoundException('Quiz not found');
    }

    // Get the entire request payload as an array
    $requestData = $request->request->all();

    // Extract the responses array
    $responses = $requestData['responses'] ?? [];

    // Create and persist the UserQuizSelection entity
    $userQuizSelection = new UserQuizSelection();
    $userQuizSelection->setUsername($username);
    $userQuizSelection->setQuiz($quiz);
    $userQuizSelection->setSelectionDate(new \DateTime());

    // Store the responses in UserQuizResult entities
    // Inside the foreach loop where responses are processed
    foreach ($responses as $questionId => $responseData) {
        $question = $entityManager->getRepository(QuizQuestion::class)->find($questionId);

        // ... existing validation code ...

        $userQuizResult = new UserQuizResult();
        $userQuizResult->setUserQuizSelection($userQuizSelection);
        $userQuizResult->setQuiz($quiz);
        $userQuizResult->setQuizQuestion($question); // Make sure this line exists
        $userQuizResult->setSoumisLe(new \DateTime());

        if ($question->getType() === 'text') {
            $userQuizResult->setResponse($responseData);
        }
        elseif ($question->getType() === 'single_choice') {
            // Get the answer text from the selected ID
            $answer = $entityManager->getRepository(QuizResponse::class)->find($responseData);
            if ($answer) {
                $userQuizResult->setResponse($answer->getTexte());
            } else {
                $this->addFlash('error', 'Invalid answer selected for question.');
                continue;
            }
        }
        elseif ($question->getType() === 'multiple_choice') {
            // Get answer texts from selected IDs
            $answers = $entityManager->getRepository(QuizResponse::class)->findBy(['id' => $responseData]);
            if (!empty($answers)) {
                $answerTexts = array_map(function($answer) {
                    return $answer->getTexte();
                }, $answers);
                $userQuizResult->setResponse(implode(', ', $answerTexts));
            } else {
                $this->addFlash('error', 'Invalid answers selected for question.');
                continue;
            }
        }

        $entityManager->persist($userQuizResult);
    }

        $entityManager->persist($userQuizSelection);
        $entityManager->flush();

        // Add a flash message
        $this->addFlash('success', 'Quiz submitted successfully!');

        // Redirect to the UserQuizSelection show page
        return $this->redirectToRoute('app_user_quiz_selection_show', ['id' => $userQuizSelection->getId()]);
    }


    #[Route('/', name: 'app_quiz_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $quizzes = $entityManager
            ->getRepository(Quiz::class)
            ->findAll();

        return $this->render('quiz/index.html.twig', [
            'quizzes' => $quizzes,
        ]);
    }

    #[Route('/new', name: 'app_quiz_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quiz = new Quiz();
        
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quiz);
            $entityManager->flush();

            return $this->redirectToRoute('app_q_u_i_z_q_u_e_s_t_i_o_n_new', ['id'=> $quiz->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quiz/new.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quiz_show', methods: ['GET'])]
    public function show(Quiz $quiz): Response
    {
        return $this->render('quiz/show.html.twig', [
            'quiz' => $quiz,
        ]);
    }

    #[Route('/{id}/show2', name: 'quiz_show2', methods: ['GET'])]
public function show2(int $id, EntityManagerInterface $entityManager): Response
{
    $quiz = $entityManager->getRepository(Quiz::class)->find($id);

    if (!$quiz) {
        throw $this->createNotFoundException('Quiz not found');
    }

    // Debugging: Check if questions and responses are loaded
    foreach ($quiz->getQuestions() as $question) {
        dump($question->getReponses()); // Check if responses are loaded
    }

    return $this->render('quiz/show2.html.twig', [
        'quiz' => $quiz,
    ]);
}

    #[Route('/{id}/edit', name: 'app_quiz_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            return $this->redirectToRoute('app_q_u_i_z_q_u_e_s_t_i_o_n_edit', ['id'=> $quiz->getId()], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quiz_delete', methods: ['POST'])]
    public function delete(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quiz->getId(), $request->request->get('_token'))) {
            $entityManager->remove($quiz);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
    }
}