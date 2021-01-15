@if (count($reviews) > 0)
    {!! $links !!}
    <section>
        <h3 class="col-md-12 row justify-content-center" id="review-heading">
            @if (count($reviews) > 1)
                Reviews:
            @else
                Review:
            @endif
        </h3>
    </section>
    <div class="row justify-content-center">
        @foreach ($reviews as $key => $review)
            <article class="review-article col-md-7">
                <h4>
                    {{ $review->getUser()->getName() . ' on ' . $review->getTrim()->getYearBegin() .
                    '-' . $review->getTrim()->getYearEnd() . ' ' . $review->getTrim()->getFramework() . ' ' .
                    $review->getTrim()->getName() . ' with ' . number_format($review->getRating(), 1) }}
                    <span class="fa fa-star"></span> at {{ $review->getDate() }}
                </h4>
                {{ $review->getContent() }}
            </article>
        @endforeach
    </div>
@elseif (count($trims) > 0)
    <section class="col-md-12 row justify-content-center text-center">
        <h3 class="col-md-6" id="review-heading">Be the first to write a review!</h3>
    </section>
@endif
