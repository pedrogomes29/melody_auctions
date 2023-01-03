<section class="user-reviews" id="user-reviews">
        @foreach ($reviews as $review)
            <article class="review">
                <header>
                    <h2 class="user"><a href="{{URL('user/'.($review->owner?$review->owner->username:'DELETE USER'))}}">

                        <?php
                            $owner_photo = 'default_images/default.jpg';
                            if ($review->owner && !is_null($review->owner->photo) && !empty(trim($review->owner->photo)) && file_exists(public_path($review->owner->photo))) {
                                $owner_photo = $review->owner->photo;
                            }
                        ?>
                        @if ($review->owner && $review->owner->photo)
                        <img src="{{ asset($owner_photo) }}" alt="Profile picture" class="rounded-circle" width="50" height="50">
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
