@extends('layout')

@section('content')
    <div class="row justify-content-center" id="contactsArticle">
        <div class="col-md-8">
            {!! $page->getContent() ?? '' !!}
            <span id="success"></span>
            <span id="error"></span>
            {!! Form::model($form, ['route' => ['contact.sendMail'], 'id' => 'contact-form']) !!}
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
            {!! Form::close() !!}
        </div>
    </div>
    <script>
        var profanities = {!! json_encode($profanities) !!};
    </script>
@endsection