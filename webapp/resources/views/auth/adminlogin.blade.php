@extends('layouts.app')
@section('content')

@section('styles')
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
@endsection


        <!-- Login basic -->
        @if(\Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="alert-body">
                {{ \Session::get('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        {{ \Session::forget('success') }}
        @if(\Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="alert-body">
                {{ \Session::get('error') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <section class="formSection">
            <form class="auth-login-form mt-2" action="{{route('adminLoginPost')}}" method="post">
                {{ csrf_field() }}
                <div class="mb-1">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="{{old('email') }}" autofocus />
                    @if ($errors->has('email'))
                    <span class="help-block font-red-mint">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="mb-1">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">Password</label>
                    </div>
                    <div class="input-group input-group-merge form-password-toggle">
                        <input type="password" class="form-control form-control-merge" id="password" name="password" tabindex="2" />
                    </div>
                    @if ($errors->has('password'))
                    <span class="help-block font-red-mint">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary w-100" tabindex="4">Login</button>
            </form>
        </section>
        <!-- /Login basic -->
    
@endsection