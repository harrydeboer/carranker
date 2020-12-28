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
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <h3>Provide an email address to get a password reset link</h3>
            <form method="post" action="{{ route('password.email') }}">
                @csrf
                <input type="email" name="email" placeholder="email">
                <input type="submit" value="Send">
            </form>
        </div>
    </div>
@endsection
