<?php

namespace App\Service\Search;

use XIVCommon\ElasticSearch\ElasticClient;

class Search
{
    use TraitStringSearch;
    use TraitFilterSearch;
    
    /** @var ElasticClient */
    private $elasticClient;

    function __construct()
    {
        // connect to production redis
        $server = getenv('IS_LOCAL') ? ['127.0.0.1', '9200'] : ['', ''];
        $this->elasticClient = new ElasticClient($server[0], $server[1]);
    }
    
    /**
     * @throws \Exception
     */
    public function handleRequest(SearchRequest $searchRequest, SearchResponse $searchResponse)
    {
        $this->elasticClient->QueryBuilder
            ->reset()
            ->sort($searchRequest->sortField, $searchRequest->sortOrder)
            ->limit($searchRequest->limitStart, $searchRequest->limit);
    
        $this->performStringSearch($searchRequest);
        $this->performFilterSearch($searchRequest);
        
        #$this->elasticClient->QueryBuilder->build(true);
        
        try {
            $searchResponse->setResults(
                $this->elasticClient->search($searchRequest->indexes ?: SearchData::indexes(), 'search') ?: []
            );
        } catch (\Exception $ex) {
            // if this is an elastic exception, clean the error
            if (substr(get_class($ex), 0, 13) == 'Elasticsearch') {
                $error = json_decode($ex->getMessage())->error->root_cause[0]->reason;
                throw new \Exception($error, $ex->getCode(), $ex);
            }
            
            throw $ex;
        }
    }
}
