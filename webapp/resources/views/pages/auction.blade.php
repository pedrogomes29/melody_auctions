<?php
use App\Models\Manufactor;
?>
@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/auction.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/generic_search_bar.js') }}" defer> </script>
    <script type="text/javascript" src="{{ asset('js/auction.js') }}" defer> </script>
    <script type="text/javascript" src="{{ asset('js/follow.js') }}" defer> </script>
    <script src="https://js.pusher.com/7.2/pusher.min.js" defer></script>
    <script type="text/javascript" src="{{ asset('js/ws_bid.js') }}" defer> </script>
    <script type="text/javascript" src="{{ asset('js/auction_compilled.js') }}" defer> </script>

    
    <script>
      window.Auction = {
          id: {{$auction->id}}
      }
    </script>


@endsection

@section('content')
  <article class='auction'>
    <header class="border-bottom pb-4">
      <div class="container">
        <h1> {{ $auction->name }}</h1>

        @if ($auction->cancelled)
          <h3 style="color: red">Cancelled</h3>
        @elseif ($auction->isOpen())
          <h3 style="color: green">Open</h3>
        @elseif ($auction->isClosed())
          <h3 style="color: red">Closed</h3>
        @else
          <h3 style="color: #da9465;">Not started yet</h3>
          @if (!$auction->cancelled && Auth::check() && Auth::User()->id === $auction->owner_id)
            <a  class="btn btn-warning" role="button" data-bs-toggle="modal" data-bs-target="#edit_popup">Edit Auction</a>
          @endif
        @endif
        @if (Auth::guard('admin')->user())  
          <a  class="btn btn-warning" role="button" data-bs-toggle="modal" data-bs-target="#edit_popup">Edit Auction</a>
        @endif
        

        <div class="owner_info">
          <a href="{{URL('user/'.$auction->owner->username)}}" class="link-dark text-decoration-none">
            @if ($auction->owner->photo)
              <img src="{{ asset($auction->owner->photo) }}" alt="Profile picture" class="rounded-circle" width="50" height="50">
            @else
              <img src="{{ asset('default_images/default.jpg') }}" alt="Profile picture" class="rounded-circle" width="50" height="50">
            @endif
            &#64{{ $auction->owner->username }}
          </a>
        </div>
      </div>
    </header>
    <img onclick="openChat()" class="messageIcon"src="{{ asset('default_images/message-icon.svg') }}" alt="message" class="message-icon">
    <main class="auctionExposition">


      <section id="auction_information" class="w-100">
        <div class="container">
          <img id="auction_img" class=".img-fluid mx-auto d-block border border-dark" src="{{ empty(trim($auction->photo)) ? URL('/images/default_auction.jpg') : asset($auction->photo) }}">
        </div>
        <section id="details">

          <section id="bid_auction">
            <?php $last_bidder = $auction->getLastBidder();?>
          
          
            @if (!$auction->cancelled)
              @if ($auction->isOpen())
                <p><strong>Auctions ends in </strong> <span date-date="{{ $auction->enddate }}" id="auction_countdown"></span></p>
              @elseif ($auction->isClosed())
                <p><strong>Auction ended </strong> {{ $auction->enddate }}</p>
              @else
                <p><strong>Auction starts in </strong> <span date-date="{{ $auction->startdate }}" id="auction_countdown"></span></p>
              @endif
            @endif
  
  
            <p><strong>Current Price:</strong> <span id="current_price"> {{ $auction->currentprice ?? $auction->startprice }} €</span></p>
            <p><strong>Last Bidder:</strong> 
              <span id="last_bidder">
              @if ($last_bidder)
               <a href="{{url('/user/'.$last_bidder->username)}}">{{$last_bidder->firstname . ' '. $last_bidder->lastname }}</a>
              @else
                No one has bid yet.
              @endif
              </span>
              </p>
            <form action="{{ url('api/auction/'.$auction->id.'/bid') }}" class="mb-1" method="post" >
              <div class="input-group mb-3">
                <span class="input-group-text">€</span>
                <input type="number" step="0.01" value ="{{ empty($auction->currentprice) ? $auction->startprice : $auction->currentprice + $auction->minbidsdif}}" class="form-control" name="value" placeholder="{{ empty($auction->currentprice) ? $auction->startprice : $auction->currentprice + $auction->minbidsdif}} or up" aria-label="Euro amount (with dot and two decimal places)">
                {{ csrf_field() }}
  
              </div>
              @if ($errors->has('bid_error'))
                <div class="alert alert-danger" role="alert">
                  {{ $errors->first('bid_error') }}
                </div>
              @elseif ($errors->has('bid_success'))
                <div class="alert alert-success" role="alert">
                  {{ $errors->first('bid_success') }}
                </div>
  
              @endif
              <?php
                error_log(Auth::User());
                error_log( $auction);
              ?>
              
              <input @if (!$auction->isOpen() || !Auth::check() || (Auth::check() && Auth::User()->id === $auction->owner_id)) disabled  @endif type="submit" value="Bid">
              
            </form>
            <div id="followed-bool" style="display: none">
              @if(Auth::check())
                {{$followed}}
              @endif
            </div>
            <div id="auction-id" style="display: none">
              {{$auction->id}}
            </div>
            <form id="follow-form" method="post" action ="{{ route('follow.store', ['id' => $auction->id]) }}" style="display: none">
                 {{ csrf_field() }}
                 <input type="hidden" name="auction_id" value="{{ $auction->id }}">
                 <input @if (!Auth::check()) disabled  @endif type="submit" value="Follow">
            </form>
            <form id="unfollow-form" method="post" action ="{{ route('follow.destroy', ['id' => $auction->id]) }}" style="display: none">
                 {{ csrf_field() }}
                 {{ method_field('DELETE') }}
                 <input type="hidden" name="auction_id" value="{{ $auction->id }}">
                 <input @if (!Auth::check()) disabled  @endif type="submit" value="Unfollow">
            </form>
            <section id="bidding_section">
  
              <a class="btn "  data-bs-toggle="collapse" href="#bid_list" role="button" aria-expanded="false" aria-controls="bid_list">
                Show Bidding List
              </a>
  
  
  
              <div class="collapse" id ="bid_list">
      
                <div  class="list-group mt-1">
                  <h3 class="text-center">Bidding History</h3>
                  <div id="bidding_history" >
                    @include('partials.bids', ['bids' => $auction->bids_offset(0)])
                  </div>
                  
                </div>
                <div id="loading" class="spinner-border " style="display:none" role="status">
                  <span class="visually-hidden">Loading...</span> 
                </div>
                <button id="load_bids" data-auction-id="{{$auction->id}}" data-offset="1" type="button" class="btn btn-outline-dark btn-sm p-1 mt-3 h-auto">Load More</button>
  
              </div>
            </section>
          </section>
        </section>
      </section>
      @include('partials.messages', ['messages' => $messages])
      @extends('partials.popup', ['POPUP_ID' => "edit_popup", 'POPUP_TITLE_ID' => "edit_popup_title", 'POPUP_TITLE' => "Edit Auction"])
      
      @section('popup-body')
        @include('partials.auction_edit', ['auction' => $auction, 'admin' => Auth::guard('admin')->user()])
      @endsection

      @section('popup-footer')
        @if (Auth::guard('admin')->user())
        <button data-csrf="{{csrf_token()}}" data-auction="/auction/{{$auction->id}}/admin" onclick="adminDeleteAuction(this)" class="btn btn-danger">Delete</button>   @else 
        <button data-csrf="{{csrf_token()}}" data-auction="/auction/{{$auction->id}}" onclick="deleteAuction(this)" class="btn btn-danger">Delete</button>
        @endif 

        <div class="text-center">
          @if (Auth::guard('admin')->user())
            <button type="submit" onclick="adminUpdateAuction(this)" class="btn btn-warning">Update</button>
          @else
            <button type="submit" onclick="updateAuction(this)" class="btn btn-warning">Update</button>
          @endif
        </div>
      @endsection
      

    </main>

    <section class="auctionInfo container">
      <h2>Manufactor</h2>
        <p>{{ Manufactor::find($auction->manufactor_id)->name }} </p>
      <h2>Description</h2>
        <p>{{ $auction->description }} </p>
    </section>


  </article>
@endsection
