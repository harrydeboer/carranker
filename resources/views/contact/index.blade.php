@extends('layout')

@section('content')
    <div class="row justify-content-center" id="contactsArticle">
        <div class="col-md-8">
            {!! $page->getContent() ?? '' !!}
            @if (isset($success))
                <span id="success">{{ $success }}</span>
            @endif
            @if (isset($error))
                <span id="error">{{ $error }}</span>
            @endif
            {!! Form::model($form, ['route' => ['contact.view'], 'id' => 'contact-form']) !!}
            <div class="form-group">{!! Form::email('email', old('email'), ['class'=>'form-control',
                'placeholder'=>'Enter your email', 'id' => 'contactform-email', 'required']) !!}</div>
            <div class="form-group">{!! Form::text('name', old('name'), ['class'=>'form-control',
                'placeholder'=>'Enter your name', 'id' => 'contactform-name', 'required']) !!}</div>
            <div class="form-group">{!! Form::text('subject', old('subject'), ['class'=>'form-control',
                'placeholder'=>'Enter subject', 'id' => 'contactform-subject', 'required']) !!}</div>
            <div class="form-group">
                {!! Form::textarea('message', old('message'), ['class'=>'form-control', 'cols' => 60, 'rows' => 15,
                'placeholder' => 'Enter message', 'id' => 'contactform-message', 'required']) !!}
            </div>
            <div class="form-group">
                <input type="submit" value="Send" class="btn btn-success" id="filter_top_form_submit">
            </div>
            <input type="hidden" name="reCaptchaToken" id="reCaptchaToken">
            {!! Form::close() !!}
            <input type="hidden" value="{{ $reCaptchaKey }}" id="reCaptchaKey">
            <input type="hidden" value="{{ $profanities }}" id="profanities">
        </div>
    </div>
@endsection