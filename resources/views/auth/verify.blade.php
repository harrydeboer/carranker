@extends('layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-2">
            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
                    {{ __('click here to request another') }}</button>.
            </form>
        </div>
    </div>
@endsection
