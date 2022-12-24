<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuthenticatedUser;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use App\Policies\AuthenticatedUserPolicy;

class ReviewController extends Controller
{
    public function showReviews($username){
        $userID = AuthenticatedUser::where('username', $username)->firstOrFail()->id;
        $reviews = Review::where('reviewed_user_id', $userID)->get();
    }
}
