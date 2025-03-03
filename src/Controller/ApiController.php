<?php

namespace App\Controller;

use App\Service\ProfanityCheckerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/check-profanity', name: 'api_check_profanity', methods: ['GET'])]
    public function checkProfanity(Request $request, ProfanityCheckerService $profanityChecker): JsonResponse
    {
        $text = $request->query->get('text', '');
        
        return new JsonResponse([
            'containsProfanity' => $profanityChecker->containsProfanity($text)
        ]);
    }
}
