<div id="{{$notification->auction->id}}"class = "sec {{$notification->beenread?'':'new'}}">
    <div class = "notificationPhoto">
    @if($notification->auction->photo!=="")
        <img class="auctionPhoto" src="{{ asset($notification->auction->photo)}}"></img>
    @else
        <img class="auctionPhoto"src="{{ asset('default_images/auction_default.svg' )}}"></img>
    @endif
    </div>
    @switch($notification->type)
        @case('AuctionCancelled')
            <div class = "notificationContent">Auction {{$notification->auction->name}} was cancelled</div>
            @break

        @case('AuctionEnded')
            @if($notification->winner)
                <div class = "notificationContent">{{$notification->winner}} has won {{$notification->auction->name}}</div>
            @else
                <div class = "notificationContent">Auction {{$notification->auction->name}} has ended with no bids</div>
            @endif
            @break

        @case('AuctionEnding')
            <div class = "notificationContent">Auction {{$notification->auction->name}} is ending in less than 30 minutes</div>
            @break

        @case('Bid')
            <div class = "notificationContent">{{$notification->bidder->username}} has bid on {{$notification->auction->name}}</div>
            @break
    @endswitch
    <div class = "notificationContent sub">{{$notification->timeSince}}</div>
    <div hidden class = "notificationDate">{{$notification->date}}</div>
</div>