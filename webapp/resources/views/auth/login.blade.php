@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
@endsection


@section('content')
<form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}
    @if (session('status'))
        <div class="alert alert-success" role="alert">
        {{ session('status') }}
        </div>
    @endif

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

    <div class="col text-center">
        <button type="submit">
            Login
        </button>
    </div>
    <div class="col text-center">
        <a class="link fw-bold text-center text-decoration-none" href="{{ route('password.request') }}">Forgot your password?</a>
    </div>
</form>

<div class="col text-center mt-5">
    <h4>
        Don't have an account account?
        <a class="link text-decoration-none" href="{{ route('register') }}">Sign up</a>
    </h4>
<!-- Login with google login -->
<div style="display: flex; justify-content: center; width:50%; margin: 0 auto; padding: 20px;">
    <a href="{{route('google.login')}}" class="btn btn-lg btn-primary btn-block">
        <i class="fa fa-google-plus pull-left"></i> Login with Google
    </a>
</div>
@endsection
