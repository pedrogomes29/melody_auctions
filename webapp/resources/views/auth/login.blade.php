@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
@endsection


@section('content')
<form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    <label for="email">E-Mail</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
    @if ($errors->has('email'))
        <span class="error">
          {{ $errors->first('email') }}
        </span>
    @endif

    <label for="password" >Password</label>
    <input id="password" type="password" name="password" required>
    @if ($errors->has('password'))
        <span class="error">
            {{ $errors->first('password') }}
        </span>
    @endif

    <button type="submit">
        Login
    </button>
    <a class="button button-outline" href="{{ route('register') }}">Register</a>
</form>
<!-- Login with google login -->
<div style="display: flex; justify-content: center; width:50%; margin: 0 auto; padding: 20px;">
<a href="{{route('google.login')}}" class="btn btn-lg btn-primary btn-block">
    <i class="fa fa-google-plus pull-left"></i> Login with Google
</a>
</div>
@endsection
