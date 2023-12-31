

<article class="auction-card " id="auction-{{$auction->id}}" >
    @if(trim($auction->photo)!=="")
            <?php 
                $auction_photo_path = 'default_images/auction_default.svg';
                if (!is_null($auction->photo) && !empty(trim($auction->photo)) && file_exists(public_path($auction->photo))) {
                    $auction_photo_path = $auction->photo;
                }

            ?>
        <img class="image" src="{{ asset($auction_photo_path)}}" alt="auction photo"/>
    @else
        <img class="image" src="{{ asset('default_images/auction_default.svg' )}}" alt="auction photo"/>
    @endif

    <section class="auction-card-text">
        <h1 class=" title ">{{$auction->productname}}</h1>

        <p class="text-secondary price-text">
        @if(($auction->uninitiated===1||$auction->active===1) && !$auction->cancelled)
            @if($auction->uninitiated===1) 
            Starting price 
            @elseif($auction->active===1) 
            Current Bid:
            @endif
        @elseif($auction->cancelled)
            
        @elseif(is_null($auction->currentprice))
            Not Sold
        @else
            Sold for
        @endif
        </p>
        <p class="price"><span class='price-value'> {{$auction->minbid ?? $auction}}</span> €</p>
        <p class="text-secondary date">
            
        @if(($auction->uninitiated===1||$auction->active===1) && !$auction->cancelled)
            @if($auction->uninitiated===1) 
                Starting in 
            @elseif($auction->active===1) 
                Ending in
            @endif

            <br>
            <span class="countdown "> </span>
            <span hidden class="enddate">{{$auction->date}}</span>

        @elseif($auction->cancelled)
            Auction cancelled
            
        @else
            Auction ended <p class="local-date">{{$auction->date}}</p>
        @endif
        </p>
    </section>
</article>


