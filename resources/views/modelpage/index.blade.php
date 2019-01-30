@extends('layout')

@section('content')
    <div class="row">
        <section class="col-md-8">
            <div class="page-header">
                <h3 id="titleCarModel">{{ $model->getMakename() . ' ' . $model->getName() }}</h3>
            </div>
            <div class="col-md-12">
                <img src="{{ fileUrl($model->getImage()) }}"
                     onerror="this.style.display='none';"
                     id="carModelImg"
                     alt="{{ $model->getMakename() . ' ' . $model->getName() }}"
                     class="pull-right img-thumbnail">
                @if (!is_null($model->getContent()))
                    {!! $model->getContent() !!}
                    <div id="reference">
                        <a href="https://en.wikipedia.org/wiki/{{ $model->getWikiCarModel() }}">Source Wikipedia</a>
                    </div>
                @endif
            </div>
        </section>
        @if (count($trims) > 0)
            <div class="col-md-4 panel panel-default">
                <div class="panel-heading text-center">
                    <div id="ratingHeading">
                        {{ is_null($model->getRating()) ? '-' : number_format($model->getRating(), 1) }}
                        <span class="fa fa-star fa-star-shadow"></span>
                        {{ ' with ' . $model->getVotes() . ' votes' }}
                    </div>
                </div>
                <BR>
                <div>
                    <table id="ratingTable" class="col-md-9">
                        @foreach ($aspects as $key => $aspect)
                            <tr class="row rowRating">
                                <td class="col-md-7">{{ ucfirst($aspect) }}:</td>
                                <td class="col-md-5">{{ is_null($model->getAspect($aspect)) ? '-':
                                number_format($model->getAspect($aspect), 1) }} <span class="fa fa-star fa-star-shadow"></span>
                                </td>
                            </tr>
                        @endforeach
                        <tr class="row rowRating">
                            <td class="col-md-7">Price: </td>
                            <td class="col-md-5">{{ is_null($model->getPrice($FXRate)) ? 'N/A' :
                            round($model->getPrice($FXRate), -3) . ' $'}}</td>
                        </tr>
                    </table>
                    <BR>
                </div>
                @if ($isLoggedIn === true)
                    <div class="text-center">
                        <a href="#dialog" data-toggle="modal" id="showModelDialog" class="btn btn-primary">Rate car!</a>
                        <BR>
                        <BR>
                        <a href="#dialog" data-toggle="modal" id="showReviewDialog" class="btn btn-primary">Write review!</a>
                    </div>
                @else
                    <div class="text-center">
                        <a href="/login" class="btn btn-primary" id="loginLink">Login to rate this car!</a>
                    </div>
                @endif
                <BR>
                @include('modelpage.modelversions')
            </div>
        @endif
    </div>
    <div id="dialog" class="modal fade">
        <div class="modal-dialog" id="rateForm">
            <div class="modal-content">
                @include('modelpage.ratingform')
            </div>
        </div>
    </div>
    @foreach ($trims as $keyTrim => $trim)
        <div class="modal fade typeInfo" id="typeInfo{{ $trim->getId() }}">
            <div class="modal-dialog typeInfoContent">
                <div class="modal-content">
                    @include('modelpage.indexTrim',
                    ['rating' => $ratings[$trim->getId()] ?? null, 'trim' => $trim, 'id' => $trim->getId()])
                </div>
            </div>
        </div>
    @endforeach
    <div class="modal {{ $isThankYou === true ? 'showThankYou' : ''}}" id="thankYou">
        <div class="modal-dialog" id="thankYouDialog">
            <div class="modal-content">
                <div class="modal-header" id="thankYouHeader">
                    <h3 id="thankYouHeading">Thank you for your rating</h3>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @include('modelpage.reviews')
@endsection