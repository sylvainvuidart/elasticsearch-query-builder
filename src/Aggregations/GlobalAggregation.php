<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations;

use Spatie\ElasticsearchQueryBuilder\AggregationCollection;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithAggregations;

class GlobalAggregation extends Aggregation
{
    use WithAggregations;

    public static function create(string $name): self
    {
        return new self($name);
    }

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->aggregations = new AggregationCollection();
    }

    public function size(int $size): self
    {
        return $this;
    }

    public function order(array $order): self
    {
        return $this;
    }

    public function payload(): array
    {
        $aggregation = [
            'global' => new \stdClass(),
        ];

        if (! $this->aggregations->isEmpty()) {
            $aggregation['aggs'] = $this->aggregations->toArray();
        }

        return $aggregation;
    }
}
