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
    <link href="{{ asset('css/auctions.css') }}" rel="stylesheet">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    @yield('styles')

    <!-- Scripts -->

    <script type="text/javascript">
        // Fix for Firefox autofocus CSS bug
        // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
    </script>
    <script type="text/javascript" src="{{ asset('js/default.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('js/app.js') }}" defer></script>
    @if($loggedIn && !$isAdmin)
      <script>
        window.User = {
            id: {{$userId}}
        }
      </script>
    @endif
    @yield('scripts')
  </head>

    <body>

      <header class="navbar navbar-expand-lg bg-light rounded" >
        <div class="container-fluid navbar-container">
        <img class="navbar-brand"
            src= "{{ asset('default_images/logo_high.svg') }}"
            id="logo"
            height="60"
            width="150"
            class="m-3"
            alt="Melody auctions Logo"
          />
          
          <div class="fixed-options" >

            @if ($loggedIn)
              @if(!$isAdmin)
                  @include('partials.notifications', ['notifications' => $notifications])
              @endif
                  <div id= "{{ $isAdmin?'admin':'user' }}-profile" class="{{$identificator}} profile-userpic me-3">
                    <img class="rounded-circle h6 " src="{{ asset($profilePic) }}" class="profilepic" alt="User Image">
                  </div>
            @else
                  
                <a id="login" class="button me-5" href="{{ url('/login') }}"> Log in </a>
            @endif
            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
          
          </div>

          <div class="navbar-collapse collapse" id="navbar" >
            <ul class="navbar-nav justify-content-between align-items-center w-100 mb-2 mb-lg-0">
              <li class="nav-item">
                <a id="home" class="nav-link h6 px-5 text-dark" href="{{ url('/') }}">Home</a>
              </li>
              <li class="nav-item">
                <a id="sell" class="nav-link h6 px-5 text-dark" href="{{ url('/auction/create') }}">Sell</a>
              </li>
              <li class="nav-item">
                <a id="buy" class="nav-link h6 px-5 text-dark" href="{{ url('/auction') }}">Buy</a>
              </li>

              <li class="nav-item w-100 flex-grow-1">
                <div class="d-flex flex-row flex-grow-1 m-top">
                  <div class="d-flex flex-row flex-grow-1">
                    <div class="flex-grow-1 m-auto">
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
                    <button class="btn dropdown-toggle" type="button" id="auctionsOrUsersButton" data-bs-toggle="dropdown" aria-expanded="false">Auctions</button>
                    <ul class="dropdown-menu" aria-labelledby="auctionsOrUsersButton">
                      <li><p class="dropdown-item chosen">Auctions</p></li>
                      <li><p class="dropdown-item">Users</p></li>
                    </ul>
                  </div>
                </div>
              </li>

              
            </ul>
          </div>

          

        </div>
        
      </header>

      <main>
        <section id="content">
          @yield('content')
        </section>
        <div aria-live="polite" aria-atomic="true" class="position-relative">
          <div id="toast-container" class="toast-container top-0 end-0 p-3"></div>
        </div>
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
