@extends('layouts.app')
@section('scripts')
    <script type="text/javascript" src={{ asset('js/generic_search_bar.js') }} defer> </script>
@endsection
@section('content')
    <section class="auctions-followed">
        <h1 class="title">Auctions followed</h1>
        <ul class="auctions-list">
            @foreach ($follows as $follow)
                <li class="auction">
                    <a href="{{ route('auction.show', $follow->auction->id) }}">
                        <div class="auction-image">
                            <img src="{{ asset('storage/' . $follow->auction->photo) }}" alt="Auction Image">
                        </div>
                        <div class="auction-info">
                            <h2 class="auction-title">{{ $follow->auction->title }}</h2>
                            <p class="auction-description">{{ $follow->auction->description }}</p>
                            <p class="auction-price">{{ $follow->auction->price }}€</p>
                        </div>
                    </a>
                </li>
            @endforeach
@endsection
