<div class="col">
    <div class="card m-5"  id="auction-{{$auction->id}}" >
        <p class="countdown card-text ms-4 mt-4 p-2 h5"> </p>
        <p hidden class="enddate">{{$auction->enddate}}</p>
        <div class="text-center">
        @if(trim($auction->photo)!=="")
            <img height=200px width=250px class="img-fluid auction_search_img" src={{ asset('storage/' . $auction->photo)}}></img>
        @else
            <img height=200px width=250px class="img-fluid auction_search_img" src={{ asset('default_images/auction_default.svg' )}}></img>
        @endif
        </div>
        <div class="card-body">
            <h3 class="card-title text-center">{{$auction->productname}}</h3>
            @if($auction->active===1)
                <p class="card-text font-weight-bold h4">Current Bid</p>
            @else 
                <p class="card-text font-weight-bold h4">Sold for</p>
            @endif
            <p class="card-text h5">{{$auction->minbid}}€</p>
        </div>
    </div>
</div>



