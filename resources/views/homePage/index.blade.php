@extends('layout')

@section('content')
    <div class="row">
        <section class="col-lg-9 col-md-12 text-center">
            {!! $content !!}
        </section>
        <div id="slideshow" class="col-lg-3 d-none d-lg-block">
            @include('homePage.slideshow')
        </div>
    </div>
    <div id="topContainer" class="col-md-12">
        <div class="row justify-content-center">
            <div id="topCars" class="col-md-8">
                <h1 id="topWithPref" class="text-center">Top
                    <span id="topOrLessNumber">
                        {{ count($topTrims) }}
                    </span>
                </h1>
                <h2 id="atLeastVotes" class="text-center">
                    <em>with at least {{ $minNumVotes }} votes</em>
                </h2>
                <div id="fillableTable">
                    @include('homePage.tableTop')
                </div>
                <div class="row justify-content-center col-md-12">
                    <div class="col-md-3 text-center">
                        <a href="{{ route('showMoreTopTable') }}" id="showLess" class="btn-lg btn-primary">Show less</a>
                    </div>
                    <div class="col-md-3 text-center">
                        <a href="{{ route('showMoreTopTable') }}" id="showMore" class="btn-lg btn-primary">Show more</a>
                    </div>
                </div>
                <input type="hidden" value="{{ $numShowMoreLess }}" id="numShowMoreLess">
                <BR><BR>
                <div class="row justify-content-center col-md-12">
                    <div class="text-center">
                        <button id="choosePreferences" class="btn-lg btn-primary">Choose car<BR>preferences</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="preferencesDialog" class="col-md-8 collapse mx-auto">
        <input type="hidden" value="{{ $minNumVotesDefault }}" id="minNumVotesDefault">
        @include('homePage.filterTopForm',
                      ['minNumVotes' => $minNumVotes, 'aspects' => $aspects, 'filterForm' => $filterForm,
                       'specsChoice' => $specsChoice, 'specsRange' => $specsRange])
    </div>
    <BR>
    <div class="row justify-content-center text-center">
        @if (count($reviews) === 0)
            <h2 id="recentReviews" class="col-md-7">No reviews at the moment: </h2>
        @else
            <h2 id="recentReviews" class="col-md-7">Recent Reviews:</h2>
            @foreach ($reviews as $key => $review)
                <div class="reviewItem justify-content-center col-md-7">
                    <h3>{{ $review->getUser()->getName() }} on
                        <a href="{{ $review->getTrim()->getUrl() }}">
                            {{ $review->getTrim()->getFullName() }}</a> with {{ number_format($review->getRating(), 1) }}
                        <span class="fa fa-star"></span> on {{ $review->getDate() }}
                    </h3>
                    <BR>
                    <div class="reviewBody">
                        <img src="{{ fileUrl('/img/favicon-16x16.png') }}"
                             data-src="{{ fileUrl($review->getTrim()->getImage()) }}"
                             class="lazy reviewImage pull-left" alt="review{{ ($key + 1) }}">
                        <div class="reviewContent">{!! $review->getContent() !!}</div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
