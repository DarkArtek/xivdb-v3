<?php

namespace App\Service\Search;

use App\Misc\ContentSeoLegacy;
use XIVCommon\SEO\SEO;

class SearchResponse
{
    /** @var SearchRequest */
    private $request;
    /** @var object */
    public $response = [
        'pagination' => [],
        'results'    => [],
        'ms'         => 0,
    ];
    
    public function __construct(SearchRequest $request)
    {
        $this->request = $request;
    }
    
    /**
     * Set results from elastic search
     */
    public function setResults(array $results)
    {
        $this->response = (Object)$this->response;
        
        // no results? return now
        if (!$results) {
            return;
        }
    
        $this->response->ms         = $results['took'];
        $this->response->results    = $this->formatResults($results['hits']['hits']);
    
        // Pagination
        $totalResults = (int)$results['hits']['total'];
        $pageTotal = $totalResults > 0 ? ceil($totalResults / $this->request->limit) : 0;
        $page = $this->request->page ?: 1;
        $page = $page >= 1 ? $page : 1;
        $pageNext = ($page + 1) <= $pageTotal ? ($page + 1) : false;
        $pagePrev = $page-1 > 0 ? $page-1 : false;
        $this->response->pagination = [
            'page' => $page,
            'page_total' => $pageTotal,
            'page_next' => $pageNext,
            'page_prev' => $pagePrev,
            'results' => count($results['hits']['hits']),
            'results_per_page' => $this->request->limit,
            'results_total' => $totalResults,
        ];
    }
    
    /**
     * Format the search results
     */
    public function formatResults($hits)
    {
        $results = [];
        foreach ($hits as $hit) {
            $index  = $hit['_index'];
            $source = $hit['_source'];
            
            $results[] = $this->buildView($index, $source);
        }
        
        return $results;
    }
    
    public function buildView($index, $source)
    {
        $row = [ '_' => $index ];
        $view = array_merge(SearchData::views($index), [
            'ID',
            'Icon',
            'Url',
        ]);
        
        foreach ($view as $field) {
            $column = str_ireplace('_%s', null, $field);
            $field  = sprintf($field, $this->request->language);
            $row[$column] = $source[$field] ?? false;
        }
        
        $row['GameType'] = explode('/', $row['Url'])[1];
        $row['SiteUrl']  = $this->buildSiteUrl($row);
        
        return $row;
    }
    
    public function buildSiteUrl($row)
    {
        $domain = array_flip(SEO::CONTENT)[$row['GameType']];
        $name = str_ireplace(' ', '+', $row['Name']);
        return "/{$domain}/{$row['ID']}/{$name}";
    }
}
