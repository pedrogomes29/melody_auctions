<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
   <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->

    <link href="{{ asset('css/milligram.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    @yield('styles')

    <!-- Scripts -->

    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    <script type="text/javascript" src={{ asset('js/app.js') }} defer></script>
    @yield('scripts')
  </head>
    <body>

      <header class="d-flex flex-row justify-content-between align-items-center pb-3 mb-5">
        <img
          src= {{ asset('default_images/auction.svg')}}
          id="logo"
          height="60"
          class="m-3"
          alt="Melody auctions Logo"
        />

        <a id="home" class="nav-link h6 px-5 text-dark" href="{{ url('/') }}">Home</a>

        <a id="sell" class="nav-link h6 px-5 text-dark" href="{{ url('/auction/create') }}">Sell</a>

        <a id="buy" class="nav-link h6 px-5 text-dark" href="{{ url('/auction') }}">Buy</a>

        <div class="d-flex flex-row flex-grow-1 m-top">
          <div class="d-flex flex-row flex-grow-1">
            <div class="flex-grow-1">
              <input id="search_bar" class="form-control border-end-0 border rounded-pill" type="search" value=@yield('searchContent')>
              <div id="search_results" class="mx-3">
                <div class="dropdown-menu bx-3">
                </div>
              </div>
            </div>
            <span class="input-group-append">
                <button id="search-button" class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" type="button">
                  <i class="fa fa-search" aria-hidden="true"></i>
                </button>
            </span>
          </div>
          <div id="auctionsOrUsers" class="dropdown ms-3 me-5">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="auctionsOrUsersButton" data-bs-toggle="dropdown" aria-expanded="false">Auctions</button>
            <ul class="dropdown-menu" aria-labelledby="auctionsOrUsersButton">
              <li><p class="dropdown-item chosen">Auctions</p></li>
              <li><p class="dropdown-item">Users</p></li>
            </ul>
          </div>
        </div>
        @if (Auth::check())
          <div id= "user-profile" class="{{Auth::user()->username}} profile-userpic me-3">
              @if (Auth::user()->photo !="")
                <img class="rounded-circle" src="{{ asset('storage/' . Auth::user()->photo) }}" class="profilepic" alt="User Image">
              @else
                <img class="rounded-circle" src="{{ asset('default_images/default.jpg') }}"class="default_profilepic" alt="User Image">
              @endif
          </div>
        @elseif(Auth::guard('admin')->user())
          <div id= "admin-profile" class="{{Auth::guard('admin')->user()->id  }} profile-userpic me-3">
            @if ( Auth::guard('admin')->user()->photo!="")
              <img class="rounded-circle" src="{{ asset('storage/' . Auth::guard('admin')->user()->photo) }}" class="profilepic" alt="User Image">
            @else
              <img class="rounded-circle" src="{{ asset('default_images/default.jpg') }}"class="default_profilepic" alt="User Image">
            @endif
          </div>
        @else
          <a id="login" class="button me-5" href="{{ url('/login') }}"> Log in </a>
        @endif
      </header>
      <main>
        <section id="content">
          @yield('content')
        </section>
      </main>
        <footer class="py-3 my-4">
          <ul class="nav justify-content-center border-bottom pb-3 mb-3">
            <li class="nav-item"><a class="nav-link px-2 text-muted" href="{{ url('/about') }}">About Us</a></li>
            <li class="nav-item"><a class="nav-link px-2 text-muted" href="{{ url('/contact-us') }}">Contact Us</a></li>
            <li class="nav-item"><a class="nav-link px-2 text-muted" href="{{ url('/FAQ') }}">FAQ</a></li>
          </ul>
          <p class="text-center text-muted">&copy; 2022 - Melody Auctions</p>
        </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
  
      </body>
</html>
