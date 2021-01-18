<?php declare(strict_types=1) ?>
@extends('admin.layout')

@section('content')
    @include('errors.errors')
    <table>
        <tr class="mail-user-tr"><td colspan="3">
                <form method="post" action="{{ route('admin.mail.users.create') }}">
                    @csrf
                    <input type="text" name="domain" placeholder="domain" required>
                    <input type="password" name="password" placeholder="password" required>
                    <input type="email" name="email" placeholder="email" required>
                    <input type="email" name="forward" placeholder="forward">
                    <input type="submit" class="btn btn-primary" value="Create">
                </form>
            </td>
        </tr>
        @if (count($mailUsers) > 0)

            @foreach ($mailUsers as $mailUser)
                <tr class="mail-user-tr"><td>
                        <form method="post" action="{{ route('admin.mail.users.update') }}">
                            @csrf
                            <input type="text" name="domain" value="{{ $mailUser->getDomain() }}"
                                   placeholder="domain" required>
                            <input type="email" name="email" value="{{ $mailUser->getEmail() }}"
                                   placeholder="email" required>
                            <input type="email" name="forward" value="{{ $mailUser->getForward() }}"
                                   placeholder="forward">
                            <input type="hidden" name="id" value="{{ $mailUser->getId() }}">
                            <input type="submit" class="btn btn-warning" value="Update">
                        </form>
                    </td><td>
                        <form method="post" class="update-password-form"
                              action="{{ route('admin.mail.users.update.password') }}">
                            @csrf
                            <input type="password" class="updatePassword"
                                   name="password" placeholder="password" required>
                            <input type="hidden" name="id" value="{{ $mailUser->getId() }}">
                            <input type="submit" class="btn btn-warning" value="Reset">
                        </form>
                    </td><td>
                        <button class="btn btn-danger delete-mail-user-button delete-button"
                                data-action="{{ route('admin.mail.users.delete') }}"
                                data-id="{{ $mailUser->getId() }}">Delete</button>
                    </td></tr>
            @endforeach
            {!! $links !!}
        @else
    </table>
    <section class="col-md-12 row justify-content-center text-center">
        <h3 class="col-md-6" id="review-heading">No mail users.</h3>
    </section>
    @endif
@endsection
