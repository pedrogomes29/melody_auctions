<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuthenticatedUser;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use App\Policies\AuthenticatedUserPolicy;

class ReviewController extends Controller
{

    public function create(Request $request, $username){
        $this->authorize('create', Review::class);
        $review = new Review();
        $id = Review::max('id')+ 1;
        $review->id = $id;
        $review->reviewed_id = AuthenticatedUser::where('username', $username)->firstOrFail()->id;
        $review->reviewer_id = Auth::user()->id;
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->save();
        return redirect()->route('user', ['username' => $username]);
    }
}
