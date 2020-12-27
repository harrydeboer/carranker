@extends('layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-md-offset-2">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (isset($status))
                {{ $status }}
            @endif
            <form method="post" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="email" name="email" placeholder="email">
                <input type="password" name="password" placeholder="password">
                <input type="password" name="password_confirmation" placeholder="password confirmation">
                <input type="submit" value="Send">
            </form>
        </div>
    </div>
@endsection
