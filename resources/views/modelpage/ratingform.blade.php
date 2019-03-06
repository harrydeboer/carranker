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
                            {!! Form::radio('star[' . $aspect . ']', $i, false, ['class' => 'radioStar', 'id' => 'RatingForm-star-' . $keyAspect . $i, ' required']) !!}
                            {!! Form::label('RatingForm-star-' . $keyAspect . $i, ' ',
                            ['class' => 'fa fa-star fa-star-form']) !!}
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
            <select name="generation" class="form-control" id="rating_form_generation" required>
                <option value="">Generation</option>
                @foreach ($generationsSeriesTrims as $generationName => $generation)
                    <option value="{{ $generationName }}">{{ $generationName }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <select name="serie" class="form-control" id="rating_form_serie" required>
                <option value="">Serie</option>
                @foreach ($generationsSeriesTrims as $generationName => $generation)
                    @foreach ($generation as $serieName => $serie)
                        <option value="{{ $generationName . ';' . $serieName }}">{{ $serieName }}</option>
                    @endforeach
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <select name="trimId" class="form-control" id="rating_form_trim" required>
                <option value="">Trim</option>
                @foreach ($generationsSeriesTrims as $generationName => $generation)
                    @foreach ($generation as $serieName => $serie)
                        @foreach ($serie as $trimName => $trimId)
                            <option value="{{ $generationName . ';' . $serieName . ';' . $trimId }}">{{ $trimName }}</option>
                        @endforeach
                    @endforeach
                @endforeach
            </select>
        </div>
        <div class="form-group" id="divArea">
            {!! Form::textarea('content', old('content'), ['class'=>'form-control',
            'cols' => 42, 'rows' => 10, 'id' => 'rating_form_content']) !!}
        </div>
    </div>
</div>
<div class="row justify-content-end">
    <div class="col-md-6 text-center">
        <input type="image" src="{{ fileUrl('/img/Rate.png') }}" id="rating_form_submit" alt="Rate!">
    </div>
    <div class="col-md-3">
        <button class="btn btn-danger" data-dismiss="modal" id="close-rating-modal">Close</button>
    </div>
</div>
<input type="hidden" name="reCaptchaToken" id="reCaptchaToken">
{!! Form::close() !!}
<input type="hidden" value="{{ $reCaptchaKey }}" id="reCaptchaKey">
<input type="hidden" value="{{ $profanities }}" id="profanities">
