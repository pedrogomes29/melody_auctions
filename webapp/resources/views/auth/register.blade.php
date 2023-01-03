@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
@endsection
@section('scripts')
    <script type="text/javascript" src={{ asset('js/register.js') }} defer> </script>
@endsection

@section('content')
<section class="formSection">
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
    <input id="firstname" type="text" name="firstname" value="{{ old('firstname') }}" required>
    @if ($errors->has('firstname'))
      <span class="error">
          {{ $errors->first('firstname') }}
      </span>
    @endif

    <label for="lastname">Last Name</label>
    <input id="lastname" type="text" name="lastname" value="{{ old('lastname') }}" required>
    @if ($errors->has('lastname'))
      <span class="error">
          {{ $errors->first('lastname') }}
      </span>
    @endif

    <label for="username">Username</label>
    <input id="username" type="text" name="username" value="{{ old('username') }}" required>
    @if ($errors->has('username'))
      <span class="error">
          {{ $errors->first('username') }}
      </span>
    @endif
    
    <label for="contact">Contact</label>
    <input id="contact" type="text" name="contact" value="{{ old('contact') }}" required>
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

    <div class="col text-center mt-5">
      <button type="submit">
          Register
      </button>
  </div>
</form>
</section>

<div class="col text-center mt-5">
  <h4>
      Already have an account?
  <a class="link text-center text-decoration-none" href="{{ route('login') }}">Log in</a>
  </h4>
</div>

@endsection
