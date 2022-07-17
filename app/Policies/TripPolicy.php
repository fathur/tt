<?php

namespace App\Policies;

use App\Models\{User, Trip};
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TripPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function show(User $user, Trip $trip)
    {
        return $user->id === $trip->user_id
            ? Response::allow()
            : Response::denyWithStatus(403);
    }

    public function destroy(User $user, Trip $trip)
    {
        return $user->id === $trip->user_id
            ? Response::allow()
            : Response::denyWithStatus(403);
    }
}
