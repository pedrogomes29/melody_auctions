@extends('layouts.app')


@section('content')
  <article class='auction'>
    <header class="border-bottom pb-4">
      <div class="container">
        <h1> {{ $auction->name }}</h1>

        <div class="owner_info">
          <img src="{{URL('/images/default-profile.png')}}" class="rounded-circle">
          {{ $auction->owner->firstname }}  {{ $auction->owner->lastname }} 
        </div>
      </div>
    </header>

    <main >

      <section id="auction_information" class="container">
        <section id="details">
          <img id="auction_img" class=".img-fluid mx-auto d-block" src="{{URL('/images/guitar.png')}}">

          <h2>Description</h2>
          <p>
          {{ $auction->description }}
          </p>

        </section>

        <section id = "bid_auction">
          <?php $last_bidder = $auction->getLastBidder();?>

          <p>End Date: {{ $auction->enddate }}</p>
          <p>Current Price: {{ $auction->currentprice }}</p>
          <p>Last Bidder: 
            @if ($last_bidder)
             <a href="{{url('/user/'.$last_bidder->id)}}">{{$last_bidder->firstname . ' '. $last_bidder->lastname }}</a>
            @else
              No one has bid yet.
            @endif
            </p>
          <form action="{{ url('api/auction/'.$auction->id.'/bid') }}" class="mb-1" method="post" >
            <div class="input-group mb-3">
              <span class="input-group-text">€</span>
              <input type="number" step="0.01" value ="{{$auction->currentprice + $auction->minbidsdif}}" class="form-control" name="value" placeholder="{{$auction->currentprice + $auction->minbidsdif}} or up" aria-label="Euro amount (with dot and two decimal places)">
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

            <input type="submit" value="Bid">
          </form>
            
          <section id="bidding_section">

            <a class="btn "  data-bs-toggle="collapse" href="#bid_list" role="button" aria-expanded="false" aria-controls="bid_list">
              Show Bidding List
            </a>



            <div class="collapse" id ="bid_list">
    
              <div id="bidding_history" class="list-group mt-1">
                <h3>Bidding History</h3>
                @include('partials.bids', ['bids' => $auction->bids_offset(0)])
                
                
              </div>
              <div id="loading" class="spinner-border " style="display:none" role="status">
                <span class="visually-hidden">Loading...</span> 
              </div>
              <button id="load_bids" data-auction-id="{{$auction->id}}" data-offset="1" type="button" class="btn btn-outline-dark btn-sm p-1 mt-3 h-auto">Load More</button>

            </div>
          </section>
        </section>
      </section>

    </main>

  </article>
@endsection
