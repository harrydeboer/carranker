<!doctype html>
<html lang="en">
<head>
    <title>{{ $title }}</title>
    @include('head')
</head>
<body>
<div id="hideAll" class="collapse"></div>
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