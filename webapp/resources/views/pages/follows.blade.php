@extends('layouts.app')
@section('scripts')
    <script type="text/javascript" src={{ asset('js/generic_search_bar.js') }} defer> </script>
    <script type="text/javascript" src={{ asset('js/auctions.js') }} defer> </script>
@endsection
@section('content')
    <section class="auctions-followed">
        <h1 class="title"> {{sizeof($auctions).' '}}Auctions followed</h1>
        <section class="auctions-list">
            @include('partials.auctions', ['auctions' => $auctions])   
        </section>
@endsection
