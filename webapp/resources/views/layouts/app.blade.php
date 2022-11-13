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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css" rel="stylesheet">
    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    <script type="text/javascript" src={{ asset('js/app.js') }} defer>
</script>
  </head>
    <body>
      <header>
        <img
          src= {{ asset('images/auction.svg')}}
          height="30"
          alt="Melody auctions Logo"
        />

        <a href="{{ url('/') }}">Home</a>

        <a href="{{ url('/auction/create') }}">Sell</a>

        <a href="{{ url('/auction') }}">Buy</a>

        <div>
              <input id="search_bar" class="form-control border-end-0 border rounded-pill" type="search">
              <span class="input-group-append">
                  <button class="btn btn-outline-secondary bg-white border-bottom-0 border rounded-pill ms-n5" type="button">
                    <i class="fa fa-search" aria-hidden="true"></i>
                  </button>
              </span>
              <div id="select">
                <p id="selected">Auctions</p>
                <ul id="hidden_dropdown" class="hide">
                  <li class="options">Auctions</li>
                  <li class="options">Users</li>
                </ul>
              </select>
              <img id="arrow" src={{ asset('images/arrow.svg')}} /  height="30">
              <div id="search_results" class="hidden">
              <div>
        </div>
        @if (Auth::check())
          <a class="button" href="{{ url('/logout') }}"> Logout </a> <span>{{ Auth::user()->name }}</span>
        @else
          <a class="button" href="{{ url('/login') }}"> Log in </a>
        @endif
      </header>
      <main>
        <section id="content">
          @yield('content')
        </section>
      </main>
      <footer>
        <img
          src= {{ asset('images/auction.svg')}}
          height="30"
          alt="Melody auctions Logo"
        />

        <a href="{{ url('/about') }}">About Us</a>

        <a href="{{ url('/contact-us') }}">Contact Us</a>

        <a href="{{ url('/FAQ') }}">FAQ</a>
        <p>Ⓒ 2022 - Melody Auctions</p>
      </footer>
    </body>
</html>
