<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Trip;

class TripTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Trip $trip)
    {
        return [
            'id' => $trip->id,
            'title' => $trip->title,
            'origin' => $trip->origin,
            'destination' => $trip->destination,
            'description' => $trip->description,
        ];
    }
}
