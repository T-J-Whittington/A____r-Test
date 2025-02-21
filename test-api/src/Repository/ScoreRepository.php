<?php

namespace App\Repository;

use App\Entity\Score;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Score>
 */
class ScoreRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Score::class);
        $this->entityManager = $entityManager;
    }

    public function save($word) {
        $score = new Score;
        $score->setValue(strlen($word));
        $score->setWord($word);
        $this->entityManager->persist($score);
        $this->entityManager->flush();
    }
}
