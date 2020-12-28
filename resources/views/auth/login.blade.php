@extends('layout')

@section('content')
    @if ( $isLoggedIn === true )
        <div class="text-center">
            <a href="{{ route('logout') }}">Logout</a>
        </div>
    @else
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
                {!! Form::open(['route' => 'login', 'id' => 'login-form']) !!}
                {!! Form::label('user_email', 'Email', ['class' => 'control-label']) !!}
                {!! Form::email('user_email', null, ['class' => 'form-control', 'required']) !!}
                {!! Form::label('password', 'Password', ['class' => 'control-label']) !!}
                {!! Form::password('password', ['class' => 'form-control', 'required']) !!}
                <div class="form-group">
                    <input type="submit" class="btn btn-success" value="Login">
                </div>
                Not registered yet? Go to <a href="{{ route('register') }}">this</a> link. <br>
                Forgot password? Go to <a href="{{ route('forgot-password') }}">this</a> link
                {!! Form::close() !!}
            </div>
        </div>
    @endif
@endsection
