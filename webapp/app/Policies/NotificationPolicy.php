<?php

namespace App\Policies;

use App\Models\AuthenticatedUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    public function markAsRead(AuthenticatedUser $user, $userId)
    {
        return $user->id == $userId;
    }
}
