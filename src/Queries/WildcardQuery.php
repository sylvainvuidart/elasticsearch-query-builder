<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class WildcardQuery implements Query
{
    protected string $field;

    protected $value;

    public static function create(string $field, string $value)
    {
        return new self($field, $value);
    }

    public function __construct(
        string $field,
        string $value
    ) {
        $this->field = $field;
        $this->value = $value;
    }

    public function toArray(): array
    {
        return [
            'wildcard' => [
                $this->field => [
                    'value' => $this->value,
                ],
            ],
        ];
    }
}
