<?php

namespace App\Service;

use App\Entity\Score;
use App\Repository\ScoreRepository;

class ScoreService {
    private $scoreRepository;

    public function __construct(ScoreRepository $scoreRepository)
    {
        $this->scoreRepository = $scoreRepository;
    }

    /**
     * Get up to scores as an array
     */
    public function getScores(){
        return $this->manyToArray($this->scoreRepository->findBy([], ['value' => 'DESC'], 10));
    }

    public function saveScore($word){
        if(!$this->scoreRepository->findOneBy(['word' => $word])){
            $this->scoreRepository->save($word);
        }
    }

    /**
     * Convert an array of Scores to an array.
     * @param array[Score]
     * @return array[array]
     */
    private function manyToArray($scores){
        return array_map(function($score) {
            return $this->toArray($score);
        }, $scores);
    }

    /**
     * Convert a given Score to an array.
     * @param Score
     * @return array
     */
    private function toArray($score) {
        return [
            'id' => $score->getId(),
            'value' => $score->getValue(),
            'word' => $score->getWord()
        ];
    }
}