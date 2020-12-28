@extends('layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-2">
            @if (session('resent'))
                <div class="alert alert-success">
                    A new link was sent to your email address.
                </div>
            @else
                <div class="alert alert-success">
                    You have received an email to verify your email address.
                </div>
            @endif
            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
                    {{ __('click here to request another') }}</button>.
            </form>
        </div>
    </div>
@endsection
