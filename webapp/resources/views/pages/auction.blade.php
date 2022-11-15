@extends('layouts.app')


@section('content')
  <article class='auction'>
    <header>
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

          <p>
          {{ $auction->description }}
          </p>

        </section>

        <section id = "bid_auction">

          <p>Start Date: {{ $auction->startdate }}</p>
          <p>End Date: {{ $auction->enddate }}</p>
          <p>Current Price: {{ $auction->currentprice }}</p>
          <form action="{{ url('api/auction/'.$auction->id.'/bid') }}" method="post" >
            <div class="input-group mb-3">
              <span class="input-group-text">€</span>
              <input type="number" step="0.01" value ="{{$auction->currentprice + $auction->minbidsdif}}" class="form-control" name="value" placeholder="{{$auction->currentprice + $auction->minbidsdif}} or up" aria-label="Euro amount (with dot and two decimal places)">
              {{ csrf_field() }}

            </div>
            <input type="submit" value="Bid">
          <form>
        </section>
      </section>

    </main>

  </article>
@endsection
