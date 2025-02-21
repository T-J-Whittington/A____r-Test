<?php

namespace App\Service;

use App\Service\ScoreService;

class WordService {
    private const NEW_WORD_LENGTH = 10;
    private const WORD_FILE = __DIR__ . "/words_alpha.txt";

    private $scoreService;
    private $comparativeWordList = [];

    function __construct(ScoreService $scoreService) {
        $this->scoreService = $scoreService;
        $this->comparativeWordList = $this->loadWordList();
    }

    /**
     * Return a newly generated string
     * @return string
     */
    public function getNewWord(){
        return $this->generateAnagramString();
    }

    /**
     * Compare a given word, if it passes, add to score and remove used letters.
     * @return array[string]
     */
    public function compareWords($words){
        $userAttemptWord = $words->attemptWord;
        $remainingLetters = $words->remainingLetters;
        $currentScore = isset($words->currentScore) ? $words->currentScore : 0;

        if(array_search($userAttemptWord, $this->comparativeWordList)){
            // Save new score to the scoreboard and update the current score.
            $this->scoreService->saveScore($userAttemptWord);
            $currentScore = $currentScore + strlen($userAttemptWord);

            // Remove the used letters from the anagram string.
            $remainingLetters = trim($remainingLetters, $userAttemptWord);

            // Check to see if there are any possible words left.
            if(!strlen($remainingLetters) || $this->noRemainingWords($remainingLetters)){
                return [
                    'currentScore' => $currentScore
                ];
            }
        }

        return [
            'remainingLetters' => $remainingLetters,
            'currentScore' => $currentScore
        ];
    }

    /**
     * Based on the given end state, return the user's final score and any potential words.
     * @param $endState
     * @return array[mixed]
     */
    public function endWord($endState){
        return [
            'finalScore' => $endState->currentScore,
            'remaminingAnagrams' => $this->findAnagrams($endState->remainingLetters)
        ];
    }

    /**
     * Check given letters for any remaining anagrams.
     * @param string
     * @return boolean
     */
    private function noRemainingWords($remainingLetters) {
        return count($this->findAnagrams($remainingLetters)) === 0;
    }
    
    /**
     * Generate a set of random characters, length matching the NEW_WORD_LENGTH constant.
     * Checks against $this->isValidWord to ensure at least one word in the set exists.
     * @return string
     */
    private function generateAnagramString() {
        $validWordFound = false;
        
        while (!$validWordFound) {
            // Generate a random word
            $letters = [];
            for ($i = 0; $i < $this::NEW_WORD_LENGTH; $i++) {
                $letters[] = chr(rand(97, 122)); // Random lowercase letter
            }
            $anagramString = implode("", $letters);

            // Check permutations to see if at least one is a real word
            if ($this->findAnagrams($anagramString)) {
                $validWordFound = true;
            }
        }
        
        return $anagramString;
    }

    /**
     * Count the frequency of characters in a string
     * @param string
     * @return array
     */
    private function getCharCount($str) {
        $charCount = [];
        foreach (str_split($str) as $char) {
            if (!isset($charCount[$char])) {
                $charCount[$char] = 0;
            }
            $charCount[$char]++;
        }
        return $charCount;
    }

    /**
     * Check if a word can be formed from the characters of a given string
     * @param string $word
     * @param string $str
     * @return bool
     */
    private function canFormWord($word, $str) {
        $wordCount = $this->getCharCount($word);
        $strCount = $this->getCharCount($str);

        // Check if the word can be formed by comparing the frequencies of each character
        foreach ($wordCount as $char => $count) {
            if (!isset($strCount[$char]) || $strCount[$char] < $count) {
                return false; // If the string doesn't contain enough of a character, return false
            }
        }
        return true;
    }


    /**
     * Find all valid anagrams of a string from a dictionary
     * @param string $str
     * @return array
     */
    private function findAnagrams($str) {
        $anagrams = [];
    
        foreach ($this->comparativeWordList as $word) {
            if ($this->canFormWord($word, $str)) {
                $anagrams[] = $word;
            }
        }
    
        return $anagrams;
    }

    /**
     * Get all the words from the words_alpha file and return an array.
     * Another option would be to use an API but most don't suit our needs.
     */
    private function loadWordList() {
        $words = [];
        $wordFile = fopen($this::WORD_FILE, "r");
        if ($wordFile) {
            while (($word = fgets($wordFile)) !== false) {
                $word = trim(strtolower($word));
                if (strlen($word) <= $this::NEW_WORD_LENGTH) {
                    $words[] = $word;
                }
            }
            fclose($wordFile);
        }
        return $words;
    }

}