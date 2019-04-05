<?php

namespace App\Repository;

use App\Entity\Text;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

class TextRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Text::class);
    }
    
    public function search(Request $request)
    {
        $qbs = $this->createQueryBuilder('t')
            ->orderBy('t.updated', 'ASC');
        
        $filters = [
            'idString', 'en', 'de', 'fr', 'ja', 'cn', 'kr'
        ];
        
        foreach($filters as $filter) {
            if ($value = $request->get($filter)) {
                $qbs->andWhere("t.{$filter} LIKE :{$filter}")
                    ->setParameter($filter, "%{$value}%");
            }
        }
    
        return $qbs->getQuery()->getResult();
    }
}
