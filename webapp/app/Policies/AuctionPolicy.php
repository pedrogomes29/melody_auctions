<?php

namespace App\Policies;

use App\Models\Auction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuctionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    public function show(User $user, Auction $auction)
    {
      // Only a card owner can see it
      return True;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        error_log("STPRE");
        //TODO return Auth::check();
        return TRUE;
    }

    
    public function store(User $user, Auction $auction)
    {
        error_log("STPRE");
        //TODO return Auth::check();
        // return $auction->owner_id = User->id;
        return TRUE;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Auction $auction)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Auction $auction)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Auction $auction)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Auction $auction)
    {
        //
    }
}
