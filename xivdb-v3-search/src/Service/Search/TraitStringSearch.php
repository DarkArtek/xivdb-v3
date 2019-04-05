<?php

namespace App\Service\Search;

/**
 * Handle string search
 */
trait TraitStringSearch
{
    public function performStringSearch(SearchRequest $searchRequest)
    {
        // reset query
        $this->elasticClient->QueryBuilder->resetQuery();

        // do nothing if no string
        if (strlen($searchRequest->string) < 1) {
            return;
        }

        switch($searchRequest->stringAlgo) {
            case 'wildcard':
                $this->elasticClient->QueryBuilder->wildcard(
                    $searchRequest->stringColumn,
                    $searchRequest->string .'*'
                );
                break;

            case 'multi_match':
                $this->elasticClient->QueryBuilder->match(
                    $searchRequest->stringColumn,
                    $searchRequest->string,
                    'multi_match', [
                        'type' => 'phrase_prefix',
                        'fields' => [$searchRequest->stringColumn]
                    ]);
                break;

            case 'query_string':
                $this->elasticClient->QueryBuilder->match(
                    $searchRequest->stringColumn,
                    $searchRequest->string,
                    'query_string', [
                        'default_field' => $searchRequest->stringColumn,
                        'query' => $searchRequest->string
                    ]);
                break;

            case 'term':
                $this->elasticClient->QueryBuilder->term(
                    $searchRequest->stringColumn,
                    $searchRequest->string
                );
                break;

            case 'match_phrase_prefix':
                $this->elasticClient->QueryBuilder->match(
                    $searchRequest->stringColumn,
                    $searchRequest->string,
                    'match_phrase_prefix'
                );
                break;

            case 'fuzzy':
                $this->elasticClient->QueryBuilder->fuzzy(
                    $searchRequest->stringColumn,
                    $searchRequest->string,
                    [
                        'boost' => 1,
                        'fuzziness' => 2,
                        'prefix_length' => 0,
                        'max_expansions' => 100,
                    ]);
                break;
        }
    }
}
