<?php

namespace App\Policies;

use App\Models\Bid;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BidPolicy
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

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bid  $bid
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Bid $bid)
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
    public function create(User $user, Bid $bid)
    {
        // nao é um admin
        //TODO

        $auction = Auction::find($bid->auction_id);

        
        // DEBUG
        error_log(json_encode(['bid' => $bid]));
        error_log(json_encode(['auction' => $auction]));

        // dono nao pode dar bid na auction
        if($auction->owner_id === $bid->authenticateduser_id)
            return FALSE;
        

        $date = date('Y-m-d H:i:s');
        // nao se pode dar bid quando a auction acabou
        if($date > $auction->enddate)
            return FALSE;
        

        // o value tem q ser maior q o currentbidprice + minbiddiff

        return TRUE;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Bid  $bid
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Bid $bid)
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
    public function delete(User $user, Bid $bid)
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
    public function restore(User $user, Bid $bid)
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
    public function forceDelete(User $user, Bid $bid)
    {
        //
    }
}
