<?php

namespace Spatie\ElasticsearchQueryBuilder;

use Elasticsearch\Client;
use Elasticsearch\Response\Elasticsearch;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Aggregation;
use Spatie\ElasticsearchQueryBuilder\Queries\BoolQuery;
use Spatie\ElasticsearchQueryBuilder\Queries\Query;
use Spatie\ElasticsearchQueryBuilder\Sorts\Sort;

class Builder
{
    protected ?BoolQuery $query = null;

    protected ?AggregationCollection $aggregations = null;

    protected ?SortCollection $sorts = null;

    protected ?string $searchIndex = null;

    protected ?int $size = null;

    protected ?int $from = null;

    protected ?array $searchAfter = null;

    protected ?array $fields = null;

    protected bool $withAggregations = true;

    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function addQuery(Query $query, string $boolType = 'must')
    {
        if (! $this->query) {
            $this->query = new BoolQuery();
        }

        $this->query->add($query, $boolType);

        return $this;
    }

    public function addAggregation(Aggregation $aggregation)
    {
        if (! $this->aggregations) {
            $this->aggregations = new AggregationCollection();
        }

        $this->aggregations->add($aggregation);

        return $this;
    }

    public function addSort(Sort $sort)
    {
        if (! $this->sorts) {
            $this->sorts = new SortCollection();
        }

        $this->sorts->add($sort);

        return $this;
    }

    public function search()
    {
        $payload = $this->getPayload();

        $params = [
            'body' => $payload,
        ];

        if ($this->searchIndex) {
            $params['index'] = $this->searchIndex;
        }

        if ($this->size !== null) {
            $params['size'] = $this->size;
        }

        if ($this->from !== null) {
            $params['from'] = $this->from;
        }

        return $this->client->search($params);
    }

    public function index(string $searchIndex)
    {
        $this->searchIndex = $searchIndex;

        return $this;
    }

    public function size(int $size)
    {
        $this->size = $size;

        return $this;
    }

    public function from(int $from)
    {
        $this->from = $from;

        return $this;
    }

    public function searchAfter(?array $searchAfter)
    {
        $this->searchAfter = $searchAfter;

        return $this;
    }

    public function fields(array $fields)
    {
        $this->fields = array_merge($this->fields ?? [], $fields);

        return $this;
    }

    public function withoutAggregations()
    {
        $this->withAggregations = false;

        return $this;
    }

    public function getPayload(): array
    {
        $payload = [];

        if ($this->query) {
            $payload['query'] = $this->query->toArray();
        }

        if ($this->withAggregations && $this->aggregations) {
            $payload['aggs'] = $this->aggregations->toArray();
        }

        if ($this->sorts) {
            $payload['sort'] = $this->sorts->toArray();
        }

        if ($this->fields) {
            $payload['_source'] = $this->fields;
        }

        if ($this->searchAfter) {
            $payload['search_after'] = $this->searchAfter;
        }

        return $payload;
    }
}
