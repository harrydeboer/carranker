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
                            $keyAspect, 'id' => 'RatingForm-star-' . $keyAspect . $i, ' required']) !!}
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
            {!! Form::select('trimId', ['' => 'Trim'], null, ['class' => 'form-control', 'id' => 'rating_form_trim']) !!}
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