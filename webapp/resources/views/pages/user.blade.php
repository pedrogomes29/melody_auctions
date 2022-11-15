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
            {{ $user->username }}'s profile
        </h1>
    </header>
    <section class="profile-sidebar">
        <!-- SIDEBAR USERPIC -->
        <div class="profile-userpic">
            @if ($user->profile_picture)
            <img src="{{ asset('storage/pics' . $user->profile_picture) }}" class="profilepic" alt="User Image">
            @else
            <img src="{{ asset('default_image/default.jpg') }}"class="default_profilepic" alt="User Image">
            @endif
        </div>
        @if(Auth::id() == $user->id)
            <a href="{{ route('user.edit', $user->username,$user->id) }}">
                <button id = "editprofile">
                    Edit Profile
                </button>
                </a>
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
    </section>
</section>
@endsection