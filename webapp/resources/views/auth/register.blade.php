@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}
    <label for="email">E-Mail Address</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif

    <label for="firstname">First Name</label>
    <input id="firstname" type="text" name="firstname" value="{{ old('firstname') }}" required autofocus>
    @if ($errors->has('firstname'))
      <span class="error">
          {{ $errors->first('firstname') }}
      </span>
    @endif

    <label for="lastname">Last Name</label>
    <input id="lastname" type="text" name="lastname" value="{{ old('lastname') }}" required autofocus>
    @if ($errors->has('lastname'))
      <span class="error">
          {{ $errors->first('lastname') }}
      </span>
    @endif

    <label for="username">Username</label>
    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>
    @if ($errors->has('username'))
      <span class="error">
          {{ $errors->first('username') }}
      </span>
    @endif
    
    <label for="contact">Contact</label>
    <input id="contact" type="text" name="contact" value="{{ old('contact') }}" required autofocus>
    @if ($errors->has('contact'))
      <span class="error">
          {{ $errors->first('contact') }}
      </span>
    @endif

    <label for="password">Password</label>
    <input id="password" type="password" name="password" required>
    @if ($errors->has('password'))
      <span class="error">
          {{ $errors->first('password') }}
      </span>
    @endif

    <label for="password-confirm">Confirm Password</label>
    <input id="password-confirm" type="password" name="password_confirmation" required>

    <button type="submit">
      Register
    </button>
    <a class="button button-outline" href="{{ route('login') }}">Login</a>
</form>
@endsection
