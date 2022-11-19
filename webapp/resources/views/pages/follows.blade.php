@extends('layouts.app')
@section('scripts')
    <script type="text/javascript" src={{ asset('js/generic_search_bar.js') }} defer> </script>
@endsection
@section('content')
    <section class="auctions-followed">
        <h1 class="title"> {{sizeof($auctions).' '}}Auctions followed</h1>
        <section class="auctions-list">
            @foreach ($auctions as $auction)
            <a href="/auction/{{ $auction->id }}" style="text-decoration: none">
                <article class="auction">
                    <div class="auction-image">
                        @if(($auction->photo) != '')
                            <img src={{ asset('storage/' . $auction->image) }} alt="auction image">
                        @else
                            <img src={{ asset('default_images/default_auction.jpg') }} alt="auction image" width="200px" height="150px">
                        @endif
                    </div>
                    <div class="auction-info">
                        <h2 class="auction-name">{{ $auction->name }}</h2>
                        <p class="auction-description">{{ $auction->description }}</p>
                        <p class="auction-price">{{ $auction->currentprice }}€</p>
                    </div>
                </article>
            </a>
                    
        </section>
            @endforeach
@endsection
