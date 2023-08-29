<?php

namespace Spatie\ElasticsearchQueryBuilder\Sorts;

class Sort
{
    public const ASC = 'asc';
    public const DESC = 'desc';

    protected string $field;

    protected string $order;

    protected ?string $missing = null;

    protected ?string $unmappedType = null;

    public static function create(string $field, string $order = 'desc')
    {
        return new self($field, $order);
    }

    public function __construct(string $field, string $order)
    {
        $this->field = $field;
        $this->order = $order;
    }

    public function missing(string $missing)
    {
        $this->missing = $missing;

        return $this;
    }

    public function unmappedType(string $unmappedType)
    {
        $this->unmappedType = $unmappedType;

        return $this;
    }

    public function toArray(): array
    {
        $payload = [
            'order' => $this->order,
        ];

        if ($this->missing) {
            $payload['missing'] = $this->missing;
        }

        if ($this->unmappedType) {
            $payload['unmapped_type'] = $this->unmappedType;
        }

        return [
            $this->field => $payload,
        ];
    }
}
