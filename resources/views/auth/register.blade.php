@extends('layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-2">
            {!! $page->getContent() ?? '' !!}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            {!! Form::open(['route' => 'register', 'id' => 'register-form']) !!}
            {!! Form::label('user_login', 'Name', ['class' => 'control-label']) !!}
            {!! Form::text('user_login', null, ['class' => 'form-control', 'required']) !!}
            {!! Form::label('user_email', 'Email', ['class' => 'control-label']) !!}
            {!! Form::email('user_email', null, ['class' => 'form-control', 'required']) !!}
            {!! Form::label('password', 'Password', ['class' => 'control-label']) !!}
            {!! Form::password('password', ['class' => 'form-control', 'required']) !!}
            {!! Form::label('password_confirmation', 'Repeat Password', ['class' => 'control-label']) !!}
            {!! Form::password('password_confirmation', ['class' => 'form-control', 'required']) !!}
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="Register">
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
