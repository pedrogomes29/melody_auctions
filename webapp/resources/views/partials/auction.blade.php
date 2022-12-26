
<div class="card m-5"  id="auction-{{$auction->id}}" >
    @if(($auction->uninitiated===1||$auction->active===1) && !$auction->cancelled)
        @if($auction->uninitiated===1)
            <h4 class="ms-4 mt-3">Starting in</h4>
        @elseif($auction->active===1)
            <h4 class="ms-4 mt-3"> Ending in </h4>
        @endif
        <p class="countdown card-text ms-4 p-2 h5"> </p>
        <p hidden class="enddate">{{$auction->date}}</p>
    @elseif($auction->cancelled)
        <h4 class="ms-4 mt-3"> Auction cancelled </h4>
        <br>
        <br>
    @else
        <br>
        <br>
        <br>
        <br>
    @endif
    <div class="text-center">
    @if(trim($auction->photo)!=="")
        <img height=200px width=250px class="img-fluid auction_search_img" src="{{ asset($auction->photo)}}"></img>
    @else
        <img height=200px width=250px class="img-fluid auction_search_img" src="{{ asset('default_images/auction_default.svg' )}}"></img>
    @endif
    </div>
    <div class="card-body">
        <h3 class="card-title text-center">{{$auction->productname}}</h3>
        @if($auction->uninitiated===1)
            <p class="card-text font-weight-bold h4">Starting price</p>
        @elseif($auction->active===1)
            <p class="card-text font-weight-bold h4">Current price</p>
        @else
            <p class="card-text font-weight-bold h4">Sold for</p>
        @endif
        <p class="ms-2 card-text h5">{{$auction->minbid}}€</p>
    </div>
</div>



