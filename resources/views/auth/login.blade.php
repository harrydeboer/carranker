<?php declare(strict_types=1) ?>
@extends('layout')

@section('content')
    @include('errors.errors')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    @if (session('verified'))
    <div class="alert alert-success">
        You email address has been verified!
    </div>
    @endif
    @if ( $isLoggedIn === true && $isEmailVerified === true)
        <div class="text-center">
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <input type="submit" value="logout">
            </form>
        </div>
    @elseif ($isEmailVerified === false)
        <div class="text-center">
            <a href="{{ route('verification.notice.with.mail') }}">You should verify your email before rating cars.</a>
        </div>
        <div class="text-center">
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <input type="submit" value="logout">
            </form>
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-md-8 col-md-offset-2">
                {!! $content !!}
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label
                        text-md-right">{{ __('E-Mail Address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="current-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </div>
                </form>
                Not registered yet? Go to <a href="{{ route('register') }}">this</a> link. <br>
                Forgot password? Go to <a href="{{ route('password.request') }}">this</a> link
            </div>
        </div>
    @endif
@endsection
