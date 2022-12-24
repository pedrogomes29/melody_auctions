@extends('layouts.app')
@section('scripts')
    <script type="text/javascript" src="{{ asset('js/generic_search_bar.js') }}" defer> </script>
@endsection
@section('content')
<section class="user-reviews" id="user-reviews">
    <ul class="user-reviews__list">
        @foreach ($reviews as $review)
            <li class="user-reviews__item">
                <div class="user-reviews__item__container">
                    <div class="user-reviews__item__container__info">
                        <h3 class="reviewer">Reviewer: {{ $review->owner->username }}</h3>
                        <h3 class="review-date" style="margin-bottom: 0;">Date: {{ $review->reviewsdate }}</h3>
                        <p class="user-reviews__item__container__info__rating">Rating: {{ $review->rating }}</p>
                        <article class="user-reviews__item__container__info__review">
                            <p>{{ $review->comment }}</p>
                    </div>
                </div>
            </li>
        @endforeach
@endsection