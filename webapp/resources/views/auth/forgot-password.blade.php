@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
@endsection

@section('content')
<h3 class="text-center mb-5"> Please enter the e-mail address associated with your account </h3>
<form method="POST" action="{{ route('password.email') }}">
    {{ csrf_field() }}
    @if (session('status'))
        <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
    @endif
    <label for="email">E-Mail Address</label>
    <input id="email" type="email" name="email" required>
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif
    <div class="col text-center mt-4">
        <button type="submit">
            Send e-mail
        </button>
    </div>
</form>
@endsection