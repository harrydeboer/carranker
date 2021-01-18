<?php declare(strict_types=1) ?>

@extends('layout')

@section('content')
    <div class="row">
        <section class="col-md-8">
            <div class="page-header">
                <h3 id="title-car-model">{{ $model->getMakeName() . ' ' . $model->getName() }}</h3>
            </div>
            <div class="col-md-12">
                @if ($model->getImage() !== '')
                    <img src="{{ fileUrl($model->getImage()) }}"
                         id="car-model-img"
                         alt="{{ $model->getMakeName() . ' ' . $model->getName() }}"
                         class="pull-right img-thumbnail">
                @endif
                @if (!is_null($model->getContent()))
                    {!! $model->getContent() !!}
                    <div id="reference">
                        <br>
                        <a href="https://en.wikipedia.org/wiki/{{ $model->getWikiCarModel() }}">Source Wikipedia</a>
                    </div>
                @endif
            </div>
        </section>
        @if (count($trims) > 0)
            <div class="col-md-4 panel panel-default">
                <div class="panel-heading text-center">
                    <div id="rating-heading">
                        {{ is_null($model->getRating()) ? '-' : number_format($model->getRating(), 1) }}
                        <span class="fa fa-star fa-star-shadow"></span>
                        {{ ' with ' . $model->getVotes() . ' votes' }}
                    </div>
                </div>
                <BR>
                <div>
                    <table id="rating-table" class="col-md-9">
                        @foreach ($aspects as $key => $aspect)
                            <tr class="row row-rating">
                                <td class="col-md-7">{{ ucfirst($aspect) }}:</td>
                                <td class="col-md-5">{{ is_null($model->getAspect($aspect)) ? '-':
                                number_format($model->getAspect($aspect), 1) }} <span class="fa fa-star fa-star-shadow"></span>
                                </td>
                            </tr>
                        @endforeach
                        <tr class="row row-rating">
                            <td class="col-md-7">Price: </td>
                            <td class="col-md-5">{{ is_null($model->getPrice($FXRate)) ? 'N/A' :
                            round($model->getPrice($FXRate), -3) . ' $'}}</td>
                        </tr>
                    </table>
                    <BR>
                </div>
                @if ($isVerified === true)
                    <div class="text-center">
                        <button data-toggle="modal" id="show-model-dialog" data-target="#dialog"
                           class="btn btn-primary">Rate car!</button>
                        <BR>
                        <BR>
                        <button data-toggle="modal" id="show-review-dialog" data-target="#dialog"
                           class="btn btn-primary">Write review!</button>
                    </div>
                @elseif ($isLoggedIn === true)
                    <div class="text-center">
                        <a href="{{ route('verification.notice.with.mail') }}"
                           class="btn btn-primary" id="login-link">Verify your email to rate this car!</a>
                    </div>
                @else
                    <div class="text-center">
                        <a href="{{ route('login') }}" class="btn btn-primary"
                           id="loginLink">Login to rate this car!</a>
                    </div>
                @endif
                <BR>
                @include('modelPage.trims')
            </div>
        @endif
    </div>
    <div id="dialog" class="modal fade">
        <div class="modal-dialog" id="rate-form">
            <div class="modal-content">
                @include('modelPage.ratingForm')
            </div>
        </div>
    </div>
    @foreach ($trims as $keyTrim => $trim)
        <div class="modal fade type-info" id="type-info{{ $trim->getId() }}">
            <div class="modal-dialog type-info-content">
                <div class="modal-content">
                    @include('modelPage.indexTrim',
                    ['rating' => $ratings[$trim->getId()] ?? null, 'trim' => $trim, 'id' => $trim->getId()])
                </div>
            </div>
        </div>
    @endforeach
    <div class="modal" id="thank-you">
        <div class="modal-dialog" id="thank-you-dialog">
            <div class="modal-content">
                <div class="modal-header" id="thank-you-header">
                    <h3 id="thank-you-heading">Thank you for your rating!</h3>
                </div>
                When you wrote a review it is now pending approval.
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @include('modelPage.reviews')
@endsection
