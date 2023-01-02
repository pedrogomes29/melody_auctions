@extends('layouts.app')

@section('title', 'Auctions')
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/generic_search_bar.js') }}" defer> </script>
    <script type="text/javascript" src="{{ asset('js/countdowns.js') }}" defer> </script> 
    <script type="text/javascript" src="{{ asset('js/auctions.js') }}" defer> </script>
@endsection

@section('styles')
    <link href="{{ asset('css/auctions.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id='auctions' class='flex-grow-1'>
        @if(count($auctions)===1)
            <h1 id="nrAuctions">{{count($auctions)}} auction owned </h1>
        @else
            <h1 id="nrAuctions">{{count($auctions)}} auctions owned </h1>
        @endif
        @include('partials.auctions', ['auctions' => $auctions])
    </div>
@endsection