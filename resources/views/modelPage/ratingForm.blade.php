<?php declare(strict_types=1) ?>

<form method="post" action="{{ route('rateCar') }}" id="rating-form">
    @csrf
    <div class="modal-body" id="body-modal-rate-form">
        <div class="stars pop-up" id="stars">
            <table class="aspect text-center">
                @foreach ($aspects as $keyAspect => $aspect)
                    <tr>
                        <td>{{ ucfirst($aspect) }}</td>
                    </tr>
                    <tr>
                        <td>
                            @for ($i = 10; $i > 0; $i--)
                                <input type="radio" name="star[{{ $aspect }}]" class="radio-star"
                                       id="rating-form-star{{ $keyAspect . $i }}" value="{{ $i }}" required>
                                <label class="fa fa-star fa-star-form"
                                       for="rating-form-star{{ $keyAspect . $i }}"> </label>
                            @endfor
                        </td>
                    </tr>
                    <tr class="space-under">
                        <td></td>
                    </tr>
                @endforeach
                <tr>
                    <td class="warning-container">
                        <span id="stars-warning" class="stars-warning"></span>
                    </td>
                </tr>
            </table>
        </div>
        <div id="dialog-container">
            <div id="review-warning"></div>
            <div class="form-group text-center">
                <select class="form-control" id="rating-form-generation" required>
                    <option value="">Generation</option>
                    @foreach ($generationsSeriesTrims as $generationName => $generation)
                        <option value="{{ $generationName }}">{{ $generationName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <select class="form-control" id="rating-form-series" required>
                    <option value="">Series</option>
                    @foreach ($generationsSeriesTrims as $generationName => $generation)
                        @foreach ($generation as $seriesName => $series)
                            <option value="{{ $generationName . ';' . $seriesName }}">{{ $seriesName }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <select class="form-control" id="rating-form-trim" required>
                    <option value="">Trim</option>
                    @foreach ($generationsSeriesTrims as $generationName => $generation)
                        @foreach ($generation as $seriesName => $series)
                            @foreach ($series as $trimName => $trimId)
                                <option value="{{ $generationName . ';' . $seriesName . ';' . $trimId }}">
                                    {{ $trimName }}
                                </option>
                            @endforeach
                        @endforeach
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="trim-id" id="rating-form-trim-id">
            <div class="form-group" id="div-area">
                <textarea id="rating-form-content"
                          name="content"
                          class="form-control"
                          cols="42"
                          rows="10"
                          maxlength="{{ $maxNumberCharactersReview }}"></textarea>
                <div>
                    <span id="no-html-allowed"></span>
                </div>
                <div>
                    <span id="characters-left">{{ $maxNumberCharactersReview }}</span> characters left.
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-end">
        <div class="col-md-6 text-center">
            <input type="image" src="{{ fileUrl('/img/Rate.png') }}" id="rating-form-submit" alt="Rate!">
        </div>
        <div class="col-md-3">
            <button class="btn btn-danger" data-dismiss="modal" id="close-rating-modal">Close</button>
        </div>
    </div>
    <input type="hidden" name="re-captcha-token" id="re-captcha-token">
</form>
<input type="hidden" value="{{ $reCAPTCHAKey }}" id="re-captcha-key">
<input type="hidden" value="{{ $profanities }}" id="profanities">
