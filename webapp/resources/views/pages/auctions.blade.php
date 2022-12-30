@extends('layouts.app')

@section('title', 'Auctions')
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/auctions.js') }}" defer> </script>
@endsection

@section('styles')
    <link href="{{ asset('css/auctions.css') }}" rel="stylesheet">
@endsection

@section('searchContent',$search)

@section('content')
    <div class="d-flex flex-row container-fluid">
        <div class="d-flex flex-column flex-shrink-0 p-3 px-5 me-5 border-right border-dark position-sticky">
            <div id="category"class="dropdown my-3">
                <h1>Category</h1>
                <button class="btn btn-secondary dropdown-toggle" type="button" id="categoryButton" data-bs-toggle="dropdown" aria-expanded="false">{{$categoryName}}</button>
                <ul class="dropdown-menu" aria-labelledby="categoryButton">
                    @foreach($categories as $category)
                        <li><p class="dropdown-item {{$categoryId==$category->id?'chosen':''}}" id="category-{{$category->id}}">{{$category->name}}</p></li>
                    @endforeach
                </ul>
            </div>
            <div id="type" class="dropdown my-3">
                <h1 >Type</h1>
                <button class="btn btn-secondary dropdown-toggle" type="button" id="typeButton" data-bs-toggle="dropdown" aria-expanded="false">{{$typeText}}</button>
                <ul class="dropdown-menu" aria-labelledby="typeButton">
                    <li><p class="dropdown-item {{$type==='active'?'chosen':''}}">Active</p></li>
                    <li><p class="dropdown-item {{$type==='uninitiated'?'chosen':''}}">Uninitiated</p></li>
                    <li><p class="dropdown-item {{$type==='closed'?'chosen':''}}">Closed</p></li>
                </ul>
            </div>

            <div id="price_range"class="dropdown my-3">
                <h1>Price Range</h1>
                <section class = "classification">
                    <section class = "slider">
                        <div class="min-value numberVal">
                            <span class="number" id="minPriceValue"  disabled>0</span>
                        </div>   
                        <?php
                            use App\Models\Auction;
                            $maxPrice = Auction::maxPrice();
                        ?>
                        <div class="range-slider">
                            <div class="progress"></div>
                            <input type="range" id="minPrice" class="range-min" min="0" max="{{$maxPrice }}" value="0" step="0.1">
                            <input type="range" id="maxPrice" class="range-max" min="0" max="{{$maxPrice }}" value="{{$maxPrice}}" step="0.1">
                        </div>
                        <div class="max-value numberVal">
                            <span class="number" id="maxPriceValue" disabled >5</span>
                        </div>
                    </section>
                </section>
            </div>

        </div>
        <div id='auctions' class='container-fluid flex-grow-1'>
            <h1 id="nrAuctions">{{$nrAuctions}} results </h1>
            @include('partials.auctions', ['auctions' => $auctions,'nrAuctions'=>$nrAuctions,'offset'=>$offset])
        </div>
    </div>
@endsection
