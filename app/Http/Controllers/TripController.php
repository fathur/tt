<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTripRequest;
use App\Http\Requests\UpdateTripRequest;
use App\Models\Trip;
use App\Transformers\TripTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Illuminate\Support\Facades\Cache;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $tripPaginator = $user->trips()->paginate(10);
        $trips = $tripPaginator->getCollection();

        return fractal($trips, new TripTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($tripPaginator))
            ->respond();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTripRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTripRequest $request)
    {
        $user = auth()->user();
        $trip = $user->trips()->create([
            'title' => $request->get('title'),
            'origin' => $request->get('origin'),
            'destination' => $request->get('destination'),
            'description' => $request->get('description'),
        ]);
        return fractal($trip, new TripTransformer())->respond(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function show(int $tripId)
    {
        $trip = Cache::rememberForever("trip@{$tripId}", function () use ($tripId) {
            return Trip::find($tripId);
        });

        $this->authorize('show', $trip);

        return fractal($trip, new TripTransformer())->respond();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTripRequest  $request
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTripRequest $request, Trip $trip)
    {
        $trip->title = $request->get('title');
        $trip->origin = $request->get('origin');
        $trip->destination = $request->get('destination');
        $trip->description = $request->get('description');
        $trip->save();

        return fractal($trip, new TripTransformer())->respond();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trip $trip)
    {
        // Check for access right
        $this->authorize('destroy', $trip);

        $trip->delete();

        // Return http 204
        return response()->noContent();
    }
}
