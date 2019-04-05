<?php

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

class PageRepository extends ServiceEntityRepository
{
    const PAGE_LIMIT = 10;
    
    // default where
    const WHERE = [
        'published' => true,
    ];
    
    // default filters
    const FILTERS = [
        'published',
        'series',
    ];
    
    // default order
    const ORDER = [
        'updated' => 'desc',
    ];
    
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Page::class);
    }
    
    public function search(Request $request)
    {
        // get default filters
        $where = (object)self::WHERE;
        
        // loop through possible filters
        foreach (self::FILTERS as $filter) {
            if ($filterValue = $request->get($filter)) {
                $where->{$filter} = $filterValue;
            }
        }
        
        // order
        $order = self::ORDER;
        
        if ($orderValue = $request->get('order')) {
            [$column, $direction] = explode(',', $orderValue);
            $order = [ $column => $direction ];
        }
        
        // limit
        $limit = $request->get('limit') ?: self::PAGE_LIMIT;
        
        // page
        $page = $request->get('page') ?: 1;
        $offset = ($page - 1) * $limit;
        
        return $this->findBy((array)$where, $order, $limit, $offset);
    }
}
