<!doctype html>
<html lang="en">
<head>
    <title>{{ $title }}</title>
    @include('head')
</head>
<body>
<div id="hideAll" class="text-center collapse">
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
zzzzzzz
</body>
</html>