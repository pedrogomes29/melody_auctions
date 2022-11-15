@extends('layouts.app')
@section('styles')
  <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection
@section('content')
<section class="admin">
  <header>
      <h1>
          &#64{{ $admin->username }}
      </h1>
  <section>
      <!-- SIDEBAR USERPIC -->
      <div class="admin-userpic">
          @if ($admin->profile_picture)
          <img src="{{ asset('storage/pics' . $admin->profile_picture) }}" class="profilepic" alt="User Image">
          @else
          <img src="{{ asset('default_image/default.jpg') }}"class="default_profilepic" alt="User Image">
          @endif
      </div>


      <div class="admin-usertitle">
          <div class="admin-usertitle-name">
              {{ $admin->firstname }} {{ $admin->lastname }}
          </div>
          <div class="admin-usertitle-description">
              Description: {{ $admin->description }}
          </div>
          <div class="admin-usertitle-contact">
              Phone number: {{ $admin->contact }} <br>
              E-Mail: {{ $admin->email }}
          </div>
      </div>
      <!-- END SIDEBAR USER TITLE -->
  </section>
</section>
  <a href="/admin/{{$admin->id}}/auctions/"> Administrate Auctions </a>
  <a href=""> Administrate Reports </a>
@endsection