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
                    <li><p class="dropdown-item {{$categoryName=='Any category'?'chosen':''}}">Any category</p></li>
                    @foreach($categories as $category)
                        <li><p class="dropdown-item {{$categoryId==$category->id?'chosen':''}}" id="category-{{$category->id}}">{{$category->name}}</p></li>
                    @endforeach
                </ul>
            </div>
            <div id="type" class="dropdown my-3">
                <h1 >Type</h1>
                <button class="btn btn-secondary dropdown-toggle" type="button" id="typeButton" data-bs-toggle="dropdown" aria-expanded="false">{{$typeText}}</button>
                <ul class="dropdown-menu" aria-labelledby="typeButton">
                    <li><p class="dropdown-item {{$type===''?'chosen':''}}">Any type</p></li>
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
                            <input type="range" id="minPrice" class="range-min" min="0" max="{{$maxPrice }}" value="{{$lower_price}}" step="0.1">
                            <input type="range" id="maxPrice" class="range-max" min="0" max="{{$maxPrice }}" value="{{$highest_price}}" step="0.1">
                        </div>
                        <div class="max-value numberVal">
                            <span class="number" id="maxPriceValue" disabled >5</span>
                        </div>
                    </section>
                </section>
            </div>

        </div>
        <div id='auctions' class='container-fluid flex-grow-1'>
            <h1 id="nrAuctions" class="d-inline">{{$nrAuctions}} results </h1>

            <div id="sortAuctions" class="d-inline float-right ">
                <label>Order by: </label> 
                <select class="custom-select d-inline w-auto h-auto" id="auctionsOrder">
                    <option value="0" {{$order ==='0' ? 'selected':''}} >Relevance</option>
                    <option value="1" {{$order ==='1' ? 'selected':''}}>Price: Low to High</option>
                    <option value="2" {{$order ==='2' ? 'selected':''}}>Price: High to Low</option>
                    <option value="3" {{$order ==='3' ? 'selected':''}}>Date</option>
                    <option value="4" {{$order ==='4' ? 'selected':''}}>Owner Ratting</option>
                </select>
            </div>
            
            <div id = "auctions_lists">
            @include('partials.auctions', ['auctions' => $auctions,'nrAuctions'=>$nrAuctions,'offset'=>$offset])
            </div>
        </div>
    </div>
@endsection
