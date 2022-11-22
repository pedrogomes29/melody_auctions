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
<section class="user-profile">
    <section class="profile">
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
                <img id="real_pic" class="pic"src="{{ asset('storage/' . $user->photo) }}" class="profilepic" alt="User Image">
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
            <div id="alter-info">
                @if(Auth::id() == $user->id || Auth::guard('admin')->user())
                    <button id="editprofile" class="btn btn-primary">
                        Edit Info
                    </button>
                @endif
                    <a href="{{route('user.follows', $user->username)}}"> <button id="showFollowedAuctions" class="btn btn-primary">Show Followed Auctions</button> </a>
                <form style="display:none" id="edituser" method="POST" action="{{ route('user.update', $user->username) }}">
                    {{ csrf_field() }}
                    @method('PUT')
                    <label for="email">E-Mail Address</label>
                    <input id="email" type="email" name="email" value="{{ $user->email }}" required>
                    @if ($errors->has('email'))
                    <span class="error">
                        {{ $errors->first('email') }}
                    </span>
                    @endif

                    <label for="firstname">First Name</label>
                    <input id="firstname" type="text" name="firstname" value="{{ $user->firstname }}" required autofocus>
                    @if ($errors->has('firstname'))
                    <span class="error">
                        {{ $errors->first('firstname') }}
                    </span>
                    @endif

                    <label for="lastname">Last Name</label>
                    <input id="lastname" type="text" name="lastname" value="{{ $user->lastname }}" required autofocus>
                    @if ($errors->has('lastname'))
                    <span class="error">
                        {{ $errors->first('lastname') }}
                    </span>
                    @endif

                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" value="{{ $user->username }}" required autofocus>
                    @if ($errors->has('username'))
                    <span class="error">
                        {{ $errors->first('username') }}
                    </span>
                    @endif
                    
                    <label for="description">Description</label>
                    <textarea id="description" name="description" autofocus> {{ $user->description }} </textarea>
                    @if ($errors->has('description'))
                    <span class="error">
                        {{ $errors->first('description') }}
                    </span>
                    @endif

                    <label for="contact">Contact</label>
                    <input id="contact" type="text" name="contact" value="{{ $user->contact  }}"  autofocus>
                    @if ($errors->has('contact'))
                    <span class="error">
                        {{ $errors->first('contact') }}
                    </span>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        Edit
                    </button>
                </form>
            </div>
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
    @if(count($auctions)>0)
        <section id='owned-auctions'>
            <h1>Auctions owned</h1>
            <div class="ms-1">  
                    @include('partials.auctions', ['auctions' => $auctions])
            </div>
            <button id="owned-button" type="button" class="ms-5 mt-5 btn btn-dark btn-rounded">See all auctions owned</button>
        </section>
    @endif
</section>
@endsection