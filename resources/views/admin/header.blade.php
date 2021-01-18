<?php declare(strict_types=1) ?>

<div class="navbar navbar-toggleable-md navbar-light bg-faded navbar-expand-lg">
    <button class="navbar-toggler navbar-toggler-right"
            type="button"
            data-toggle="collapse"
            data-target="#navbar-collapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse" id="navbar-collapse">
        <ul class="nav navbar-nav" id="navigation-admin">
            <li><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li><a href="{{ route('admin.reviews') }}">Reviews</a></li>
            <li><a href="{{ route('admin.mail.users') }}">Mail Users</a></li>
        </ul>
    </div>
</div>
