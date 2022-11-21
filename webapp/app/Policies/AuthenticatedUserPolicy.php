<?php

namespace App\Policies;

use App\Models\AuthenticatedUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuthenticatedUserPolicy
{
    use HandlesAuthorization;
    public function update(AuthenticatedUser $user, AuthenticatedUser $authenticatedUser)
    {
        // only the authenticated user can update their own profile
        return $authenticatedUser->id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\AuthenticatedUser  $authenticatedUser
     * @param  \App\Models\AuthenticatedUser  $authenticatedUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(AuthenticatedUser $user, AuthenticatedUser $authenticatedUser)
    {
        // only the authenticated user can delete their own profile
        return $authenticatedUser->id === $user->id;
    }
}
