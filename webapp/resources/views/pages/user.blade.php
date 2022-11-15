@extends('layouts.app')
@section('scripts')
    <script type="text/javascript" src={{ asset('js/generic_search_bar.js') }} defer> </script>
@endsection
@section('styles')
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endsection
@section('content')
<section class="profile">
    <header id="profile-name" >
            <h1 id="user-name">
                &#64{{ $user->username }}
            </h1>
    </header>
    @if (Auth::user()->id == $user->id)
        <form action="{{ route('user.delete', $user->username) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Account</button>
        </form>
    @endif
    <section class="profile-sidebar">
        <!-- SIDEBAR USERPIC -->
        <div class="profile-userpic">
            @if ($user->profile_picture)
            <img src="{{ asset('storage/pics' . $user->profile_picture) }}" class="profilepic" alt="User Image">
            @else
            <img src="{{ asset('default_image/default.jpg') }}"class="default_profilepic" alt="User Image">
            @endif
        </div>
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
            @if(Auth::id() == $user->id)
                <button id="editprofile" class="btn btn-primary">
                    Edit Info
                </button>
            @endif
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
    </section>
    @if(Auth::id() == $user->id)
        <div id="balance">
            <h2>
                Balance: {{ $user->balance }} $
            </h2>
            <button id="add-balance" class="btn btn-primary">Add Balance</button>
            <form style="display:none" id="add-balance-form" action="{{ route('user.balance.update', $user->username) }}" method="POST">
                {{ csrf_field() }}
                @method('PUT')
                <label for="balance">Amount</label>
                <input type="number" name="balance" id="balance-input" placeholder="Balance" required min="1">
                <button type="submit" class="btn btn-primary">Add</button>
            </form>
            <script src={{ asset('js/edit.js') }}></script>
        </div>
    @endif
</section>
@endsection