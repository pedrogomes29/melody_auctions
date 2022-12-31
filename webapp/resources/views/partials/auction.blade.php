

<article class="auction-card card " id="auction-{{$auction->id}}" >
    @if(trim($auction->photo)!=="")
        <img class="image" src="{{ asset($auction->photo)}}"/>
    @else
        <img class="image" src="{{ asset('default_images/auction_default.svg' )}}"/>
    @endif

    <section class="auction-card-text">
        <h1 class=" title ">{{$auction->productname}}</h1>

        <p class="text-secondary price-text">Current Bid:</p>
        <p class="price"><span class='price-value'>{{$auction->minbid}}</span> €</p>
        <p class="text-secondary date">
            
        @if(($auction->uninitiated===1||$auction->active===1) && !$auction->cancelled)
            @if($auction->uninitiated===1) 
                Starting in 
            @elseif($auction->active===1) 
                Ending in
            @endif

            
            <span class="countdown "> </span>
            <span hidden class="enddate">{{$auction->date}}</span>

        @elseif($auction->cancelled)
            Auction cancelled
            
        @else
            Auction ended<p class="local-date"> {{$auction->date}}</p>
        @endif
    <section>
</article>


