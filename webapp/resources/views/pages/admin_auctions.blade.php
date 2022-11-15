@extends('layouts.app')


@section('content')
    @foreach ($auctions as $auction)
        <article class="card">
            <article class="card-body">
                <h2 class="card-title">{{ $auction->name }}</h2>
                <p class="card-text">{{ $auction->description }}</p>
                <p class="card-text">End: {{ $auction->enddate }}</p>
                <a href="/admin/{{$adminId}}/auctions/{{$auction->id}}"> Edit </a>
            </article>
        </article>
    @endforeach
@endsection