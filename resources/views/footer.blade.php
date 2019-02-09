</div>
<footer class="container">
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
    <img data-src="{{ fileUrl('/img/No123.jpg') }}" src="{{ isset($lazyLoad) && $lazyLoad === true ? fileUrl('/img/favicon-16x16.png') :
    fileUrl('/img/No123.jpg') }}" alt="Cars No 1, 2 and 3" id="numbercar"
         class="{{ isset($lazyLoad) && $lazyLoad === true ? 'lazy' : '' }} img-thumbnail col-md-12">
    <BR>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
    <script src="{{ fileUrl('/js/fallbackAndLazyLoad.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
            integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
            integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
            crossorigin="anonymous"></script>
    <script src="{{ fileUrl('/js/main.min.js') }}"></script>
    @if ($controller !== 'cms')
        <script src="{{ fileUrl('/js/' . $controller . '.min.js') }}"></script>
    @endif
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-92295454-1"></script>
</footer>
</body>
</html>