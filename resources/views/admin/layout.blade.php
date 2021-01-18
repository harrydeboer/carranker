<?php declare(strict_types=1) ?>
<!doctype html>
<html lang="en">
<head>
    <title>{{ $title }}</title>
    @include('admin.head')
</head>
<body>
<header class="container">
    @include('admin.header')
</header>
<div class="container">
    @yield('content')
</div>
<footer class="container">
    @include('admin.footer')
</footer>
</body>
</html>
