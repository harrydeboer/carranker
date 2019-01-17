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
                @if ($model->getContent())
                    {!! $model->getContent() !!}
                    <div id="reference">
                        <a href="https://en.wikipedia.org/wiki/{{ $model->getWikiCarModel() }}">Source Wikipedia</a>
                    </div>
                @endif
            </div>
        </section>
        @if ($trims)
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        <div id="ratingHeading">
                            {{ number_format($model->getRating(), 1) }}
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
                                    <td class="col-md-5">{{ number_format($model->getAspect($aspect), 1) }} <span
                                                class="fa fa-star fa-star-shadow"></span></td>
                                </tr>
                            @endforeach
                            <tr class="row rowRating">
                                <td class="col-md-7">Price: </td>
                                <td class="col-md-5">{{ round($model->getPrice($FXRate), -3) }} $</td>
                            </tr>
                        </table>
                        <BR>
                    </div>
                    <div id="dialog" class="modal fade">
                        <div class="modal-dialog" id="rateForm">
                            <div class="modal-content">
                                @include('modelpage.ratingform')
                            </div>
                        </div>
                    </div>
                    @if ($isLoggedIn)
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

                    @foreach ($trims as $keyTrim => $trim)
                        <div class="modal fade typeInfo" id="typeInfo{{ $trim->getId() }}">
                            <div class="modal-dialog typeInfoContent">
                                <div class="modal-content">
                                    @include('modelpage.indexTrim',
                                    ['rating' => $ratings[$trim->getId()] ?? 0, 'trim' => $trim, 'id' => $trim->getId()])
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <BR>
                    @include('modelpage.modelversions')
                </div>
            </div>
        @endif
    </div>
    <div class="modal" id="thankyou">
        <div class="modal-dialog" id="thankyouDialog">
            <div class="modal-content">
                <div class="modal-header" id="thankyouHeader">
                    <h3 id="thankyouHeading">Thank you for your rating</h3>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @if (count($reviews))
        @include('modelpage.reviews')
    @else
        <section class="col-md-12 row justify-content-center text-center">
            <h3 class="col-md-6" id="reviewHeading">Be the first to write a review!</h3>
        </section>
    @endif
    <script>
        var profanities = {!! json_encode($profanities) !!};
        var hasTrimTypes = {{ $hasTrimTypes }};
        var isThankYou = {{ $isThankYou }};
        var makename = {!! json_encode($model->getMakename()) !!};
        var modelname = {!! json_encode($model->getName()) !!};
        var generationsSeriesTrims = {!! json_encode($generationsSeriesTrims) !!};
    </script>
@endsection