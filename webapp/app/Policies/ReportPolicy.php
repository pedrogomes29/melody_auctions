<?php

namespace App\Policies;

use App\Models\AuthenticatedUser;
use App\Models\Report;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ReportPolicy
{
    use HandlesAuthorization;

    
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

}
