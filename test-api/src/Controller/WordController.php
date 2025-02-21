<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\WordService;

class WordController extends AbstractController {

    /**
     * Message repository.
     */
    private $wordService;

    function __construct(WordService $wordService) {
        $this->wordService = $wordService;
    }

    public function new() {
        return new JsonResponse($this->wordService->getNewWord());
    }

    /**
     * Return a list of messages.
     * @return JsonResponse
     */
    public function compare(Request $request) {
        return new JsonResponse($this->wordService->compareWords(json_decode($request->getContent())));
    }

    public function end(Request $request) {
        return new JsonResponse($this->wordService->endWord(json_decode($request->getContent())));
    }
}