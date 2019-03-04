@extends('layout')

@section('content')
    @if ( $isLoggedIn === true )
        <div class="text-center">
            <a href="{{ route('logout') }}">Logout</a>
        </div>
    @else
        @include('auth.login')
    @endif
@endsection