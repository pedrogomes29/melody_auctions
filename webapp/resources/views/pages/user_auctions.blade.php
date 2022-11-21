@extends('layouts.app')

@section('title', 'Auctions')
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/generic_search_bar.js') }}" defer> </script>
    <script type="text/javascript" src="{{ asset('js/countdowns.js') }}" defer> </script> 
@endsection

@section('styles')
    <link href="{{ asset('css/auctions.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div id='auctions' class='flex-grow-1'>
        <h1 id="nrAuctions">{{count($auctions)}} auctions owned </h1>
        @include('partials.auctions', ['auctions' => $auctions])
    </div>
@endsection