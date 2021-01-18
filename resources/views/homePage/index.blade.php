<?php declare(strict_types=1) ?>

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
    <div id="top-container" class="col-md-12">
        <div class="row justify-content-center">
            <div id="top-cars" class="col-md-8">
                <h1 id="top-with-pref" class="text-center">Top
                    <span id="top-or-less-number">
                        {{ count($topTrims) }}
                    </span>
                </h1>
                <h2 id="at-least-votes" class="text-center">
                    <em>with at least {{ $minNumVotes }} votes</em>
                </h2>
                <div id="fillable-table">
                    @include('homePage.tableTop')
                </div>
                <div class="row justify-content-center col-md-12">
                    <div class="col-md-3 text-center">
                        <a href="{{ route('showMoreTopTable') }}" id="show-less" class="btn-lg btn-primary">Show less</a>
                    </div>
                    <div class="col-md-3 text-center">
                        <a href="{{ route('showMoreTopTable') }}" id="show-more" class="btn-lg btn-primary">Show more</a>
                    </div>
                </div>
                <input type="hidden" value="{{ $numShowMoreLess }}" id="num-show-more-less">
                <BR><BR>
                <div class="row justify-content-center col-md-12">
                    <div class="text-center">
                        <button id="choose-preferences" class="btn-lg btn-primary">Choose car<BR>preferences</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="preferences-dialog" class="col-md-8 collapse mx-auto">
        <input type="hidden" value="{{ $minNumVotesDefault }}" id="min-num-votes-default">
        @include('homePage.filterTopForm',
                      ['minNumVotes' => $minNumVotes, 'aspects' => $aspects,
                       'specsChoice' => $specsChoice, 'specsRange' => $specsRange])
    </div>
    <BR>
    <div class="row justify-content-center text-center">
        @if (count($reviews) === 0)
            <h2 id="recent-reviews" class="col-md-7">No reviews at the moment: </h2>
        @else
            <h2 id="recent-reviews" class="col-md-7">Recent Reviews:</h2>
            @foreach ($reviews as $key => $review)
                <div class="review-item justify-content-center col-md-7">
                    <h3>{{ $review->getUser()->getName() }} on
                        <a href="{{ $review->getTrim()->getUrl() }}">
                            {{ $review->getTrim()->getFullName() }}</a> with {{ number_format($review->getRating(), 1) }}
                        <span class="fa fa-star"></span> on {{ $review->getDate() }}
                    </h3>
                    <BR>
                    <div class="review-body">
                        <img src="{{ fileUrl('/img/favicon-16x16.png') }}"
                             data-src="{{ fileUrl($review->getTrim()->getImage()) }}"
                             class="lazy review-image pull-left" alt="review{{ ($key + 1) }}">
                        <div class="review-content">{{ $review->getContent() }}</div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection
