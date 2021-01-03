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
        {!! Form::model($filterform, ['route' => ['filterTop'], 'id' => 'filterTopForm', 'method' => 'get']) !!}
        {!! Form::label('minNumVotes', 'Minimum number of votes:', ['class' => 'collapseChoice control-label']) !!}
        {!! Form::number('minNumVotes', old('minNumVotes'), ['class'=>'collapseChoice form-control',
        'step' => 1, 'id' => 'minNumVotes', 'required']) !!}
        <div class="row mx-auto col-md-12">
            <div id="choices" class="btn-group text-center">
                @foreach ($specsChoice as $specName => $spec)
                    <div class="dropdown col-xl-6 col-lg-12 button-inline {{ $spec['show'] === true ? '' : 'collapseChoice' }}">
                        <button class="btn btn-primary specsChoice"
                                data-toggle="dropdown" id="filterTopForm{{ $specName }}">{{ $spec['display'] }}</button>
                        <table class="dropdown-menu">
                            <tr class="row">
                                <td class="col-md-8 col-md-offset-1">{!! Form::label(
                                'specsChoice[checkAll' . $specName . ']', 'Select all/none') !!}</td>
                                <td class="col-md-2">{!! Form::checkbox('specsChoice[checkAll' . $specName . ']', 1,
                                null, ['class' => $specName . ' checkAll', 'data-specname' => $specName ]) !!}</td>
                            </tr>
                            @foreach ($spec['choices'] as $index => $choice)
                                <tr class="row">
                                    <td class="col-md-8 col-md-offset-1">{!!
                                    Form::label('specsChoice[' . $specName . $index . ']', $choice) !!}</td>
                                    <td class="col-md-2">{!! Form::checkbox('specsChoice[' . $specName . $index . ']',
                                    1, null, ['class' => $specName]) !!}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endforeach
            </div>
            <table class="col-xl-8 col-lg-12 collapseAspects" id="aspectsTable">
                @foreach ($aspects as $aspect)
                    <tr class="row aspectFilter">
                        <td class="col-xl-3 col-lg-4 aspectName">
                            <label for="filterTopForm{{ $aspect }}">{{ $aspect }}</label>
                        </td>
                        <td class="col-xl-1 col-lg-2 aspectMin">0</td>
                        <td class="col-xl-6 col-lg-4 aspectRange">
                            <input value="{{ $filterform->aspects[$aspect] }}"
                                   name="aspects[{{ $aspect }}]"
                                   id="filterTopForm{{ $aspect }}"
                                   type="range"
                                   class="form-control aspectElement"
                                   min="0"
                                   max="5"
                                   step="1">
                        </td>
                        <td class="col-xl-1 col-lg-2 aspectMax">5</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <table class="table" id="specsRangeTable">
            @foreach ($specsRange as $specName => $spec)
                <tr class="row rangeRow {{ $spec['show'] === true ? '' : 'collapseRange' }}">
                    <td class="col-md-4 col-sm-6">{{ $spec['display'] }}</td>
                    <td class="col-md-3 col-sm-3">
                        {!! Form::select('specsRange[' . $specName . 'min' . ']', $spec['minRange'],
                        null, ['class' => 'specsRange form-control']) !!}
                    </td>
                    <td class="col-md-3 col-sm-3">
                        {!! Form::select('specsRange[' . $specName . 'max' . ']', $spec['maxRange'],
                        null, ['class' => 'specsRange form-control']) !!}
                    </td>
                    <td class="col-md-2 d-none d-md-block">{{ $spec['unit'] }}</td>
                </tr>
            @endforeach
        </table>
        <div class="row justify-content-center" id="buttonsShowFilterReset">
            <button class="btn btn-primary" id="filterTopFormShowAll">Show all options</button>
            <button class="btn btn-success" id="filterTopFormSubmit">Filter the top!</button>
            <button class="btn btn-danger" id="filterTopFormReset">Reset to default</button>
        </div>
        {!! Form::close() !!}
    </div>
    <BR>
    <div class="row justify-content-center text-center">
        @if (count($reviews) === 0)
            <h2 id="recentReviews" class="col-md-7">No reviews at the moment: </h2>
        @else
            <h2 id="recentReviews" class="col-md-7">Recent Reviews:</h2>
            @foreach ($reviews as $key => $review)
                <div class="reviewItem justify-content-center col-md-7">
                    <h3>{{ $review->getUser()->getUsername() }} on
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
