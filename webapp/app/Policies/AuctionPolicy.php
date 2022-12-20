<?php

namespace App\Policies;

use App\Models\Auction;
use App\Models\AuthenticatedUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Auth;
class AuctionPolicy
{
    use HandlesAuthorization;


    public function show(AuthenticatedUser $user)
    {
      // every one can see
      return True;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\AuthenticatedUser  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(AuthenticatedUser $user)
    {
        return Auth::check();
    }

    
    public function store(AuthenticatedUser $user)
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\AuthenticatedUser  $user
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(AuthenticatedUser $user, Auction $auction)
    {   
        return $user->id == $auction->owner_id && Auth::check() || Auth::guard('admin')->user();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\AuthenticatedUser  $user
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(AuthenticatedUser $user, Auction $auction)
    {
        return $user->id == $auction->owner_id && Auth::check();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\AuthenticatedUser  $user
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(AuthenticatedUser $user, Auction $auction)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\AuthenticatedUser  $user
     * @param  \App\Models\Auction  $auction
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(AuthenticatedUser $user, Auction $auction)
    {
        //
    }
}
