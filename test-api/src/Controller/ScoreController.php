<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\ScoreService;

class ScoreController extends AbstractController {

    private $scoreService;

    function __construct(ScoreService $scoreService) {
        $this->scoreService = $scoreService;
    }
    
    /**
     * Return a list of messages.
     * @return JsonResponse
     */
    public function list() {
        return new JsonResponse($this->scoreService->getScores());
    }
}