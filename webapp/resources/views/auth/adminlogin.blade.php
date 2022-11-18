@extends('layouts.app')
@section('content')

@section('styles')
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
@endsection


@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form class="form-horizontal" method="POST" action="{{ route('admin.login.post') }}">
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
</form>
@endsection