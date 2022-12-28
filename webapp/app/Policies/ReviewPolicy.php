<?php

namespace App\Policies;

use App\Models\AuthenticatedUser;
use App\Models\Review;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ReviewPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\AuthenticatedUser  $authenticatedUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(AuthenticatedUser $authenticatedUser)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\AuthenticatedUser  $authenticatedUser
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(AuthenticatedUser $authenticatedUser, Review $review)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\AuthenticatedUser  $authenticatedUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(AuthenticatedUser $authenticatedUser)
    {
        return Auth::check();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\AuthenticatedUser  $authenticatedUser
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(AuthenticatedUser $authenticatedUser, Review $review)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\AuthenticatedUser  $authenticatedUser
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(AuthenticatedUser $authenticatedUser, Review $review)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\AuthenticatedUser  $authenticatedUser
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(AuthenticatedUser $authenticatedUser, Review $review)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\AuthenticatedUser  $authenticatedUser
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(AuthenticatedUser $authenticatedUser, Review $review)
    {
        //
    }
}
