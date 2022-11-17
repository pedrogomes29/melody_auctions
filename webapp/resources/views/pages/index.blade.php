@extends('layouts.app')

@section('title', "Melody Auctions")

@section('scripts')
    <script type="text/javascript" src={{ asset('js/generic_search_bar.js') }} defer> </script>
    <script type="text/javascript" src={{ asset('js/index.js') }} defer> </script>
@endsection

@section('styles')
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id='categories' class="m-4">
        <h2 class="ml-1">Categories</h2>
        <div class="d-flex flex-row justify-content-around align-items-center">
        @foreach($categories as $category)
            <button id="category-{{$category->id}}"  type="button" class="category btn-lg m-5 btn btn-primary btn-light">{{$category->name}}</button>
        @endforeach
        </div>
    </div>
    <div id='active-auctions' class="m-4">
        <h2>Ongoing auctions</h2>
        <div class="ml-1">  
                @include('partials.auctions', ['auctions' => $active_auctions])
        </div>
        <button id="ongoing-button" type="button" class="ml-5 btn btn-dark btn-rounded">See All Ongoing Auctions</button>
    </div>
    <div id='closed-auctions' class="m-4">
        <h2 class="ml-1">Closed auctions</h2>
        <div class="ml-1">  
            @include('partials.auctions', ['auctions' => $closed_auctions])
        </div>
        <button id="closed-button" type="button" class="ml-5 btn btn-dark btn-rounded">See All Recent Auctions</button>
    </div>
@endsection