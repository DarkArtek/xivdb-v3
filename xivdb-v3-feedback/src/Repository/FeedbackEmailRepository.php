<?php

namespace App\Repository;

use App\Entity\FeedbackComment;
use App\Entity\FeedbackEmail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class FeedbackEmailRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FeedbackEmail::class);
    }
}
