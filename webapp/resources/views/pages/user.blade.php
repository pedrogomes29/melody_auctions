@extends('layouts.app')
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/generic_search_bar.js') }}" defer> </script>
    <script type="text/javascript" src="{{ asset('js/user_profile.js') }}" defer> </script>
    <script type="text/javascript" src="{{ asset('js/auctions.js') }}" defer> </script>
    <script src="{{ asset('js/edit.js') }}" defer></script>
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
                <div class="profile-usertitle-description">
                    Description: {{ $user->description }}
                </div>
                <div class="profile-usertitle-contact">
                    Phone number: {{ $user->contact }} <br>
                    E-Mail: {{ $user->email }}
                </div>
            </div>
            <!-- END SIDEBAR USER TITLE -->
            
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
        </section>

        <!--
        @if (Auth::id() == $user->id)
            <form id="delete-user"action="{{ route('user.delete', $user->username) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Account</button>
            </form>
        @endif

        -->

        
    </section>

    <section id="profile_options" class="w-100">
        <nav>
        <div class="nav nav-tabs mb-2" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-auctions-tab" data-bs-toggle="tab" data-bs-target="#nav-auctions" type="button" role="tab" aria-controls="nav-auctions" aria-selected="true">Auctions owned</button>
            <button class="nav-link" id="nav-followed-tab" data-bs-toggle="tab" data-bs-target="#nav-followed" type="button" role="tab" aria-controls="nav-followed" aria-selected="false">Followed Auctions</button>
            
            @if(Auth::id() == $user->id || Auth::guard('admin')->user())
                <button class="nav-link" id="nav-edit-tab" data-bs-toggle="tab" data-bs-target="#nav-edit" type="button" role="tab" aria-controls="nav-edit" aria-selected="false">Edit Profile</button>
            @endif
            <button class="nav-link" id="nav-disabled-tab" data-bs-toggle="tab" data-bs-target="#nav-disabled" type="button" role="tab" aria-controls="nav-disabled" aria-selected="false" disabled>Disabled</button>
        </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-auctions" role="tabpanel" aria-labelledby="nav-auctions-tab" tabindex="0">
                @include('partials.profile.auctions_owned', ['auctions' => $auctions])
            </div>
            <div class="tab-pane fade" id="nav-followed" role="tabpanel" aria-labelledby="nav-followed-tab" tabindex="0">
                @include('partials.profile.follows', ['auctions' => $user->followed_auctions])
            </div>
            
            @if(Auth::id() == $user->id || Auth::guard('admin')->user())
                <div class="tab-pane fade" id="nav-edit" role="tabpanel" aria-labelledby="nav-edit-tab" tabindex="0">  
                    @include('partials.profile.edit', ['user' => $user, 'errors' => $errors])
                </div>
            @endif
            <div class="tab-pane fade" id="nav-disabled" role="tabpanel" aria-labelledby="nav-disabled-tab" tabindex="0">...</div>
        </div>
        
    </section>


</section>

@endsection