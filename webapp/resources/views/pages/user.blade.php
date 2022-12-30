@extends('layouts.app')
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/tabs.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('js/generic_search_bar.js') }}" defer> </script>
    <script type="text/javascript" src="{{ asset('js/user_profile.js') }}" defer> </script>
    <script type="text/javascript" src="{{ asset('js/auctions.js') }}" defer> </script>
    <script type="text/javascript" src="{{ asset('js/report.js') }}" defer> </script>
    <script type="text/javascript" src="{{ asset('js/bids.js') }}" defer> </script>
    <script type="text/javascript" src="{{ asset('js/edit.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('js/review.js') }}" defer> </script>
@endsection
@section('styles')
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endsection
@section('content')
<section class="user-profile d-flex ">

    <section class="profile" > 
        <header id="profile-name" >
                <h1 id="user-name">
                <a id="username-at" href=""> &#64{{ $user->username }}</a>
                </h1>
        </header>
        @if (Auth::id() == $user->id)
            <form id="logout"action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>         
            </form>
        @endif
        <section class="profile-sidebar">
            <!-- SIDEBAR USERPIC -->
            <div id= "prof_pic" class="profile-userpic">
                @if ($user->photo)
                <img id="real_pic" class="pic"src="{{ asset($user->photo) }}" class="profilepic" alt="User Image">
                @else
                <img id="default_pic" class="pic"src="{{ asset('default_images/default.jpg') }}"class="default_profilepic" alt="User Image">
                @endif
            </div>
            @if(Auth::id() == $user->id || Auth::guard('admin')->user())
            <div style="display:none" id="image_form"class="relative flex items-center min-h-screen justify-center overflow-hidden">
                <form action="{{ route('user.photo', $user->username) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <label class="block mb-4">
                        <span style="display:none" class="sr-only">Choose File</span>
                        <input required style="display:none" id="imageUpload" type="file" name="image"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                        @error('image')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </label>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
                
            @endif
            <div class="profile-usertitle">
                <div class="profile-usertitle-name">
                    {{ $user->firstname }} {{ $user->lastname }}
                </div>
                @if (isset($average))
                <div class="average-rating">
                    Average Rating: {{ $average }} &#11088
                </div>
                @else
                <div class="average-rating">
                    Average Rating: 0 &#11088
                </div>
                @endif
                <div class="profile-usertitle-description">
                    Description: {{ $user->description }}
                </div>
                <div class="profile-usertitle-contact">
                    Phone number: {{ $user->contact }} <br>
                    E-Mail: {{ $user->email }}
                </div>
            </div>
            <!-- END SIDEBAR USER TITLE -->
            @if (Auth::check() && Auth::id() != $user->id)
                <button id="report-button" class="btn btn-primary">Report</button>
            @endif
            <form style="display:none" id="report-form" action="{{route('report.create',$user->username)}}" method="POST">
                @csrf
                @if ($errors->has('report'))
                <p class="alert alert-danger">
                    {{ $errors->first('report') }}
                </p>
                @endif
                @if ($errors->has('reportstext'))
                <p class="alert alert-danger">
                    {{ $errors->first('reportstext') }}
                </p>
                @endif
                <label for="complaint">Complaint</label>
                <textarea id="complaint" name="reportstext" rows="4" cols="50"></textarea>
                <button type="submit" class="btn btn-primary">Report</button>
            </form>
                
            <a href="{{route('user.reviews', $user->username)}}"> <button id="showReviews" class="btn btn-primary">Show User Reviews</button></a>

            @if(Auth::id() == $user->id)
            <div id="balance">
                <h2 id="current_balance">
                    Balance:  <output id="actual_balance">{{$user->balance }}</output> $
                </h2>
                <button id="add-balance" class="btn btn-primary">Add Balance</button>
                <form style="display:none" id="add-balance-form" action="{{ route('user.balance', $user->username) }}" method="POST">
                    <meta name="csrf-token" content="{{ csrf_token() }}" />
                    @method('PUT')
                    <label for="balance">Amount</label>
                    <input type="number" name="balance" id="balance-input" placeholder="Balance" required min="1">
                    <button id="add" type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
            @endif
            @if (Auth::check() && Auth::id() != $user->id)
            <button id="review-button" class="btn btn-primary">Review</button>
            <div id="review">
                <form style="display:none" id="review-form" method="POST" action="{{ route('review.create', $user->username) }}">
                    {{ csrf_field() }}
                    <label for="rating">Rating</label>
                    <input id="rating" type="number" name="rating" value="5" min="1" max="5" required autofocus>
                    @if ($errors->has('rating'))
                    <span class="error">
                        {{ $errors->first('rating') }}
                    </span>
                    @endif

                    <label for="comment">Review</label>
                    <textarea id="comment" name="comment" autofocus></textarea>
                    @if ($errors->has('review'))
                    <span class="error">
                        {{ $errors->first('review') }}
                    </span>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        Review
                    </button>
                </form>
            @endif
        </section>

        @if (Auth::check() && Auth::id() == $user->id)
            <form id="delete-user"action="{{ route('user.delete', $user->username) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Account</button>
            </form>
        @endif

        
    </section>

    <section id="profile_options" class="w-100">
        <nav>
        <div class="nav nav-tabs mb-2" id="nav-tab" role="tablist">
            <button class="nav-link active text-dark" id="nav-auctions-tab" hash="auctions" data-bs-toggle="tab" data-bs-target="#nav-auctions" type="button" role="tab" aria-controls="nav-auctions" aria-selected="true">Auctions owned</button>
            <button class="nav-link text-dark" id="nav-followed-tab" hash="follows" data-bs-toggle="tab" data-bs-target="#nav-followed" type="button" role="tab" aria-controls="nav-followed" aria-selected="false">Followed Auctions</button>
            
            @if(Auth::id() == $user->id || Auth::guard('admin')->user())
                <button class="nav-link text-dark" id="nav-edit-tab" hash="edit" data-bs-toggle="tab" data-bs-target="#nav-edit" type="button" role="tab" aria-controls="nav-edit" aria-selected="false">Edit Profile</button>
            @endif
            <button class="nav-link text-dark" id="nav-bids-tab" hash="bids" data-bs-toggle="tab" data-bs-target="#nav-bids" type="button" role="tab" aria-controls="nav-bids" aria-selected="false" >My Bids</button>
        </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-auctions" hash="auctions" role="tabpanel" aria-labelledby="nav-auctions-tab" tabindex="0">
                @include('partials.profile.auctions_owned', ['auctions' => $auctions])
            </div>
            <div class="tab-pane fade" id="nav-followed" role="tabpanel" hash="follows" aria-labelledby="nav-followed-tab" tabindex="0">
                @include('partials.profile.follows', ['auctions' => $user->followed_auctions])
            </div>
            
            @if(Auth::id() == $user->id || Auth::guard('admin')->user())
                <div class="tab-pane fade" id="nav-edit" role="tabpanel" hash="edit" aria-labelledby="nav-edit-tab" tabindex="0">  
                    @include('partials.profile.edit', ['user' => $user, 'errors' => $errors])
                </div>
            @endif
            <div class="tab-pane fade" id="nav-bids" role="tabpanel" hash="bids" aria-labelledby="nav-bids-tab" tabindex="0">
                    
                    <?php
                        $order = app('request')->input('order') ?? '';
                        $sort = app('request')->input('sort') ?? '';
                        $bids = $user->bids($order, $sort);
                    ?>
                    @include('partials.profile.bids', ['user' => $user, 'bids' => $bids, 'order' => $order, 'sort' => $sort])

            </div>
        </div>
        
    </section>


</section>

@endsection