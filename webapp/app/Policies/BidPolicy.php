<?php

namespace App\Policies;

use App\Models\Bid;
use App\Models\AuthenticatedUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Auth;

class BidPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any bid.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(AuthenticatedUser $user)
    {
        // any user can be any bid
        return TRUE;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bid  $bid
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(AuthenticatedUser $user, Bid $bid)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User     $user
     * @param  \App\Models\Bid      $bid
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(AuthenticatedUser $user)
    {

        return Auth::check();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bid  $bid
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(AuthenticatedUser $user, Bid $bid)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bid  $bid
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(AuthenticatedUser $user, Bid $bid)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bid  $bid
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(AuthenticatedUser $user, Bid $bid)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bid  $bid
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(AuthenticatedUser $user, Bid $bid)
    {
        //
    }
}
