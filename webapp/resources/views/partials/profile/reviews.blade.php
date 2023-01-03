<section class="user-reviews" id="user-reviews">
        @foreach ($reviews as $review)
            <article class="review">
                <header>
                    <h2 class="user"><a href="{{URL('user/'.($review->owner?$review->owner->username:'DELETE USER'))}}">
                        @if ($review->owner && $review->owner->photo)
                        <img src="{{ asset($review->owner->photo) }}" alt="Profile picture" class="rounded-circle" width="50" height="50">
                        @else
                        <img src="{{ asset('default_images/default.jpg') }}" alt="Profile picture" class="rounded-circle" width="50" height="50">
                        @endif
                        &#64;{{ ($review->owner?$review->owner->username:'DELETE USER') }}
                        </a>
                    </h2>
                    <h2 class="score">Score: {{ $review->rating }}/5</h2>
                    <h2 class="date">Date: {{ $review->reviewsdate }}</h2>
                </header>
                <p>{{ $review->comment }}</p>
            </article>
        @endforeach

</section>
