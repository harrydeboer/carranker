<form method="post" action="{{ route('rateCar') }}" id="ratingForm">
    @csrf
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
                                <input type="radio" name="star[{{ $aspect }}]" class="radioStar"
                                       id="ratingFormStar{{ $keyAspect . $i }}" value="{{ $i }}" required>
                                <label class="fa fa-star fa-star-form"
                                       for="ratingFormStar{{ $keyAspect . $i }}"> </label>
                            @endfor
                        </td>
                    </tr>
                    <tr class="spaceUnder">
                        <td></td>
                    </tr>
                @endforeach
                <tr>
                    <td class="warningContainer">
                        <span id="starsWarning" class="starsWarning"></span>
                    </td>
                </tr>
            </table>
        </div>
        <div id="dialogContainer">
            <div id="reviewWarning"></div>
            <div class="form-group text-center">
                <select class="form-control" id="ratingFormGeneration" required>
                    <option value="">Generation</option>
                    @foreach ($generationsSeriesTrims as $generationName => $generation)
                        <option value="{{ $generationName }}">{{ $generationName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <select class="form-control" id="ratingFormSeries" required>
                    <option value="">Series</option>
                    @foreach ($generationsSeriesTrims as $generationName => $generation)
                        @foreach ($generation as $seriesName => $series)
                            <option value="{{ $generationName . ';' . $seriesName }}">{{ $seriesName }}</option>
                        @endforeach
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <select name="trimId" class="form-control" id="ratingFormTrim" required>
                    <option value="">Trim</option>
                    @foreach ($generationsSeriesTrims as $generationName => $generation)
                        @foreach ($generation as $seriesName => $series)
                            @foreach ($series as $trimName => $trimId)
                                <option value="{{ $generationName . ';' . $seriesName . ';' . $trimId }}">{{ $trimName }}</option>
                            @endforeach
                        @endforeach
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="trimId" id="ratingFormTrimId">
            <div class="form-group" id="divArea">
                <textarea id="ratingFormContent" name="content" class="form-control" cols="42" rows="10"></textarea>
            </div>
        </div>
    </div>
    <div class="row justify-content-end">
        <div class="col-md-6 text-center">
            <input type="image" src="{{ fileUrl('/img/Rate.png') }}" id="ratingFormSubmit" alt="Rate!">
        </div>
        <div class="col-md-3">
            <button class="btn btn-danger" data-dismiss="modal" id="closeRatingModal">Close</button>
        </div>
    </div>
    <input type="hidden" name="reCaptchaToken" id="reCaptchaToken">
</form>
<input type="hidden" value="{{ $reCaptchaKey }}" id="reCaptchaKey">
<input type="hidden" value="{{ $profanities }}" id="profanities">
