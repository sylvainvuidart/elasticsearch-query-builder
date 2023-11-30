<?php

namespace Spatie\ElasticsearchQueryBuilder\Aggregations;

use Spatie\ElasticsearchQueryBuilder\Aggregations\Concerns\WithMissing;

class ScriptedMetricAggregation extends Aggregation
{
    use WithMissing;

    protected string $init;
    protected string $map;
    protected string $combine;
    protected string $reduce;

    public static function create(string $name, $init, $map, $combine, $reduce): self
    {
        return new self($name, $init, $map, $combine, $reduce);
    }

    public function __construct(string $name, string $init, string $map, string $combine, string $reduce)
    {
        $this->name = $name;
        $this->init = $init;
        $this->map = $map;
        $this->combine = $combine;
        $this->reduce = $reduce;
    }

    public function payload(): array
    {
        $aggregation = [
            'scripted_metric' => [
                'init_script' => $this->init,
                'map_script' => $this->map,
                'combine_script' => $this->combine,
                'reduce_script' => $this->reduce,
            ],
        ];

        return $aggregation;
    }
}
