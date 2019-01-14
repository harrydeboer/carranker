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
                                    <td class="col-md-5">{{ number_format($model->getAspect($aspect), 1) }} <span class="fa fa-star fa-star-shadow"></span></td>
                                </tr>
                            @endforeach
                            <tr class="row rowRating">
                                <td class="col-md-7">Price: </td>
                                <td class="col-md-5">{{ round($model->getPrice($FXRate), -3) }} $</td>
                            </tr>
                        </table>
                        <BR>
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
                    <div id="dialog" class="modal fade">
                        <div class="modal-dialog" id="rateForm">
                            <div class="modal-content">
                                {!! Form::model($ratingform, ['route' => ['make.model', 'make' => $model->getMakename(),
                                'model' => $model->getName()], 'id' => 'rating-form']) !!}
                                <div class="modal-body" id="bodyModalRateForm">
                                    <div class="stars pop-up" id="stars">
                                        <table class="aspect text-center">
                                            @foreach ($aspects as $keyAspect => $aspect)
                                                <tr>
                                                    <td>{{ ucfirst($aspect) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        @for ($i = 10; $i > 0; $i--)
                                                            {!! Form::radio('star[' . $aspect . ']', $i, false, ['class' => 'radioStar radio' .
                                                            $keyAspect, 'id' => 'RatingForm-star-' . $keyAspect . $i, 'required']) !!}
                                                            {!! Form::label('RatingForm-star-' . $keyAspect . $i, ' ',
                                                            ['class' => 'fa fa-star fa-star-form label' . $keyAspect]) !!}
                                                        @endfor
                                                    </td>
                                                </tr>
                                                <tr class="spaceUnder">
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td class="warningContainer"><span id="starsWarning" class="starsWarning"></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div id="dialogContainer">
                                        <div id="reviewWarning"></div>
                                        <div class="form-group text-center">
                                            {!! Form::select('generation', ['' => 'Generation'], null, ['class' => 'form-control', 'id' => 'rating_form_generation']) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::select('serie', ['' => 'Serie'], null, ['class' => 'form-control', 'id' => 'rating_form_serie']) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::select('trim', ['' => 'Trim'], null, ['class' => 'form-control', 'id' => 'rating_form_trim']) !!}
                                        </div>
                                        <div class="form-group" id="divArea">
                                            {!! Form::textarea('content', old('content'), ['class'=>'form-control',
                                            'cols' => 42, 'rows' => 10, 'id' => 'rating_form_content']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-end">
                                    <div class="col-md-6 text-center"><input type="image" src="{{ fileUrl('/img/Rate.png') }}" id="rating_form_submit" alt="Rate!"></div>
                                    <div class="col-md-3"><button class="btn btn-danger" data-dismiss="modal" id="close-rating-modal">Close</button></div>
                                </div>
                                <input type="hidden" name="reCaptchaToken" id="reCaptchaToken">
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    @if ($isLoggedIn)
                        <div class="text-center">
                            <a href="#dialog" data-toggle="modal" id="showModelDialog" class="btn btn-primary">Rate car!</a><BR><BR>
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
                                    @include('modelpage.indexTrim', ['rating' => $ratings[$trim->getId()] ?? 0, 'trim' => $trim, 'id' => $trim->getId()])
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <BR>
                    <div class="text-center">
                        <div id="titleTypes">Modelversions</div>
                        Generation:
                        <select id="generationSelect">
                            @foreach ($generationsSeriesTrims as $keyGen => $generation)
                                <option {{ $selectedGeneration === $keyGen ? 'selected' : '' }}>{{ $keyGen }}</option>
                            @endforeach
                        </select>
                    </div>
                    @foreach ($generationsSeriesTrims as $keyGen => $generation)
                        <div id="generation{{ $keyGen }}" class="generations text-center {{ $selectedGeneration === $keyGen ? '' : 'collapse' }}">
                            @foreach ($generation as $keySer => $serie)
                                @if ($hasTrimTypes)
                                    <div>{{ $keySer }}</div>
                                    @foreach ($serie as $nameTrim => $valueTrim)
                                        <div><a href="#typeInfo{{ $valueTrim }}"
                                                data-toggle="modal"
                                                id="linkType{{ $valueTrim }}"
                                                class="linkType">{{ $nameTrim ?? $model->getName() }}</a></div>
                                    @endforeach
                                @else
                                    <div><a href="#typeInfo{{ $serie[0] }}" data-toggle="modal" id="linkType{{ $serie[0] }}" class="linkType">{{ $keySer }}</a></div>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    @if (count($reviews))
        @if ($maxPages != 1)
            <ul class="pagination pagination-sm row justify-content-center">

                <?php $pageNumber = $thisPage - 1 < 1 ? 1 : $thisPage - 1; ?>
                <li {{ $thisPage == 1 ? 'class="disabled"' : '' }}>
                    <a href="/model/{{ $model->getMakename() . '/' . $model->getName() . '?page=' . $pageNumber }}">«&nbsp;</a>
                </li>

                @for ($i = 1; $i <= $maxPages; $i++)
                    <li {{ $thisPage == $i ? 'class="active"' : '' }}>
                        <a href="/model/{{ $model->getMakename() . '/' . $model->getName() . '?page=' . $i }}">&nbsp;{{ $i }}&nbsp;</a>
                    </li>
                @endfor

                <?php $pageNumber = $thisPage + 1 <= $maxPages ? $thisPage + 1 : $thisPage; ?>
                <li {{ $thisPage == $maxPages ? 'class="disabled"' : ''}}>
                    <a href="/model/{{ $model->getMakename() . '/' . $model->getName() . '?page=' . $pageNumber }}">&nbsp;»</a>
                </li>
            </ul>
        @endif
        <section>
            <h3 class="col-md-12 row justify-content-center" id="reviewHeading">
                @if (count($reviews) > 1)
                    Reviews:
                @else
                    Review:
                @endif
            </h3>
        </section>
        <div class="row justify-content-center">
            @foreach ($reviews as $key => $review)
                <article class="reviewArticle col-md-7">
                    <h4>{!! $review->getUser()->getUsername() . ' on ' . $review->getTrim()->getYearBegin() . '-' . $review->getTrim()->getYearEnd() .
                        ' ' . $review->getTrim()->getFramework() . ' ' . $review->getTrim()->getName() . ' with ' . number_format($review->getRating(), 1) !!}
                        <span class="fa fa-star"></span> at {{ $review->getDate() }}</h4>
                    {!! $review->getContent() !!}
                </article>
            @endforeach
        </div>
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