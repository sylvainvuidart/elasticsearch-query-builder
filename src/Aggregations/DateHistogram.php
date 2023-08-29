<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations;

use Spatie\ElasticsearchQueryBuilder\AggregationCollection;
use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithAggregations;

class DateHistogram extends Aggregation
{

    use WithAggregations;

    protected string $field;
    protected $calendarInterval;
    protected $fixedInterval;

    public static function create(string $name, string $field, $calendarInterval = null, $fixedInterval = null, ...$aggregations): self
    {
        return new self($name, $field, $calendarInterval, $fixedInterval, ...$aggregations);
    }

    public function __construct(string $name, string $field, $calendarInterval = null, $fixedInterval = null, ...$aggregations)
    {
        $this->name = $name;
        $this->field = $field;
        $this->calendarInterval = $calendarInterval;
        $this->fixedInterval = $fixedInterval;

        $this->aggregations = new AggregationCollection(...$aggregations);
    }

    public function payload(): array
    {
        $parameters = [
            'field' => $this->field,
        ];

        if($this->calendarInterval){
            $parameters['calendar_interval'] = $this->calendarInterval;
        }
        if($this->fixedInterval){
            $parameters['fixed_interval'] = $this->fixedInterval;
        }

        $toReturn = [
            'date_histogram' => $parameters,
        ];

        if(!$this->aggregations->isEmpty()){
            $toReturn['aggs'] = $this->aggregations->toArray();
        }

        return $toReturn;
    }
}
