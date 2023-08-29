<?php

namespace Spatie\ElasticsearchQueryBuilder\Queries;

class MatchQuery implements Query
{
    public static function create(
        string $field,
        $query,
        $fuzziness = null
    ): self {
        return new self($field, $query, $fuzziness);
    }

    public function __construct(
        string $field,
        $query,
        $fuzziness = null
    ) {
    }

    public function toArray(): array
    {
        $match = [
            'match' => [
                $this->field => [
                    'query' => $this->query,
                ],
            ],
        ];

        if ($this->fuzziness) {
            $match['match'][$this->field]['fuzziness'] = $this->fuzziness;
        }

        return $match;
    }
}
