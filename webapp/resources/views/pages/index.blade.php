@extends('layouts.app')

@section('title', "Melody Auctions")

@section('scripts')
    <script src="{{ asset('js/generic_search_bar.js') }}" defer> </script>
    <script src="{{ asset('js/index.js') }}" defer> </script>
    <script src="{{ asset('js/auctions.js') }}" defer> </script>
@endsection

@section('styles')
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id="categories" class="m-4">
        @include('partials.categories', ['categories' => $categories])
    </div>
    <div id='active-auctions' class="m-4">
        @if(count($active_auctions)>0)
            <h2>Ongoing auctions</h2>
            <div class="ms-1">  

                    @include('partials.auctions', ['auctions' => $active_auctions])
            </div>
            <button id="ongoing-button" type="button" class="ms-5 mt-5 btn btn-dark btn-rounded">See All Ongoing Auctions</button>
        @endif
    </div>
    <div id='uninitiated-auctions' class="m-4">
        @if(count($uninitiated_auctions)>0)
            <h2>Uninitiated auctions</h2>
            <div class="ms-1">  
                    @include('partials.auctions', ['auctions' => $uninitiated_auctions])
            </div>
            <button id="uninitiated-button" type="button" class="ms-5 mt-5 btn btn-dark btn-rounded">See All Uninitiated Auctions</button>
        @endif
    </div>
    <div id='closed-auctions' class="m-4">
        @if(count($closed_auctions)>0)
            <h2 class="ms-1">Closed auctions</h2>
            <div class="ms-1">  
                @include('partials.auctions', ['auctions' => $closed_auctions])
            </div>
            <button id="closed-button" type="button" class="ms-5 mt-5 btn btn-dark btn-rounded">See All Recent Auctions</button>
        @endif
    </div>
    
@endsection