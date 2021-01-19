<?php declare(strict_types=1) ?>
<!doctype html>
<html lang="en">
<head>
  <title>{{ $title }}</title>
  @include('head')
</head>
<body>
<div id="hide-all" class="text-center collapse">
  <img src="{{ fileUrl('/img/Loader.gif') }}" alt="loadImg">
</div>
<header class="container">
  @include('header')
</header>
<div class="container">
  @yield('content')
</div>
<footer class="container">
  @include('footer')
</footer>
</body>
</html>
