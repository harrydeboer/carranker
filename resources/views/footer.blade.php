<div class="navbar navbar-default text-center">
    <ul id="navmenuFooter" class="nav navbar-nav">
        @foreach ($menuFooter as $page)
        <li class="nav-item navText"><a href="/{{ strtolower($page->getName()) }}" class="nav-link">{{ $page->getTitle() }}&nbsp;&nbsp;</a></li>
        @endforeach
    </ul>
</div>
<div class="container text-center">This site is protected by reCAPTCHA and the Google
    <a href="https://policies.google.com/privacy">Privacy Policy</a> and
    <a href="https://policies.google.com/terms">Terms of Service</a> apply.
</div>
<BR>
<img data-src="{{ fileUrl('/img/No123.jpg') }}" src="{{ $lazyLoad === true ? fileUrl('/img/favicon-16x16.png') :
fileUrl('/img/No123.jpg') }}" alt="Cars No 1, 2 and 3" id="numbercar" class="lazy img-thumbnail col-md-12">
<BR>
<script src="{{ fileUrl('/js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ fileUrl('/js/popper.min.js') }}"></script>
<script src="{{ fileUrl('/js/bootstrap.min.js') }}"></script>
<script src="{{ fileUrl('/js/main.min.js') }}"></script>
@if ($controller !== 'cms')
<script src="{{ fileUrl('/js/' . $controller . '.min.js') }}"></script>
@endif
<script>
    var controller = "{{ $controller }}";
</script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-92295454-1"></script>