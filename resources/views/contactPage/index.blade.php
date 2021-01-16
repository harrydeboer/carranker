@extends('layout')

@section('content')
    <div class="row justify-content-center" id="contacts-article">
        <div class="col-md-8">
            {!! $content !!}
            @include('errors.errors')
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form method="post" action="{{ route('contact.sendMail') }}" id="contact-form">
                @csrf
                <div class="form-group">
                    <input type="email" id="contact-form-email" name="email"
                           class="form-control" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <input type="text" id="contact-form-name" name="name"
                           class="form-control" placeholder="Enter your name" required>
                </div>
                <div class="form-group">
                    <input type="text" id="contact-form-subject" name="subject"
                           class="form-control" placeholder="Enter subject" required>
                </div>
                <div class="form-group">
                <textarea id="contact-form-message" name="message" cols="60" rows="15"
                          class="form-control" placeholder="Enter message" required></textarea>
                </div>
                <div class="form-group">
                    <input type="submit" value="Send" class="btn btn-success" id="contact-form-submit">
                </div>
                <input type="hidden" name="re-captcha-token" id="re-captcha-token">
            </form>
            <input type="hidden" value="{{ $reCAPTCHAKey }}" id="re-captcha-key">
            <input type="hidden" value="{{ $profanities }}" id="profanities">
        </div>
    </div>
@endsection
