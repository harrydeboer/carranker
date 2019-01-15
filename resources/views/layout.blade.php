<!doctype html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <style>
        @font-face {
            font-family: OpenSans;
            font-display: swap;
            src: url({{ fileUrl('/fonts/OpenSans.ttf') }});
        }

        @font-face {
            font-family: 'FontAwesome';
            font-display: swap;
            src: url({{ fileUrl('/fontawesome/fonts/fontawesome-webfont.eot') }});
            src: url({{ fileUrl('/fontawesome/fonts/fontawesome-webfont.eot') }})
            format('embedded-opentype'),
            url({{ fileUrl('/fontawesome/fonts/fontawesome-webfont.woff2') }})
            format('woff2'),
            url({{ fileUrl('/fontawesome/fonts/fontawesome-webfont.woff') }})
            format('woff'),
            url({{ fileUrl('/fontawesome/fonts/fontawesome-webfont.ttf') }})
            format('truetype'),
            url({{ fileUrl('/fontawesome/fonts/fontawesome-webfont.svg') }})
            format('svg');
            font-weight: normal;
            font-style: normal;
        }

        #hideAll {
            display: none;
            position: fixed;
            z-index: 10;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba( 255, 255, 255, .8 );
            background-repeat: no-repeat;
            background-position: center;
            background-image: url({{ fileUrl('/img/Loader.gif') }});
        }
    </style>
    <meta name="keywords" content="{{ $metaKeyWords }}" >
    <meta name="description" content="{{ $metaDescription }}" >
    <meta name="author" content="Harry de Boer" >
    <meta http-equiv="content-type" content="text/html; charset=utf-8" >
    <link rel="apple-touch-icon" sizes="180x180" href="{{ fileUrl('/img/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" href="{{ fileUrl('/img/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ fileUrl('/img/favicon-16x16.png') }}" sizes="16x16">
    <link rel="manifest" href="{{ fileUrl('/img/manifest.json') }}">
    <link rel="mask-icon" href="{{ fileUrl('/img/safari-pinned-tab.svg') }}">
    <link rel="stylesheet" href="{{ fileUrl('/css/' . $controller . '.css') }}">
    <script>
        var reCaptchaKey = "{{ $reCaptchaKey }}";
        var lazyloadThrottleTimeout;
        controller = "{{ $controller }}";

        function lazyload()
        {
            if (lazyloadThrottleTimeout) {
                clearTimeout(lazyloadThrottleTimeout);
            }

            lazyloadThrottleTimeout = setTimeout(function()
            {
                var scrollTop = window.pageYOffset;
                lazyloadImages.forEach(function(img)
                {
                    if (img.offsetTop < (window.innerHeight + scrollTop)) {
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                    }
                });
                if (lazyloadImages.length === 0) {
                    document.removeEventListener("scroll", lazyload);
                    window.removeEventListener("resize", lazyload);
                    window.removeEventListener("orientationChange", lazyload);
                }
            }, 20);
        }

        document.addEventListener("DOMContentLoaded", function () {
            lazyloadImages = document.querySelectorAll("img.lazy");
            if ( controller === 'homepage') {
                setTimeout(lazyload, 3000);

                document.addEventListener("scroll", lazyload);
                window.addEventListener("resize", lazyload);
                window.addEventListener("orientationChange", lazyload);
                document.addEventListener("visibilitychange", lazyload);
            } else {
                lazyload();
            }
        });
    </script>
    <meta name="theme-color" content="#336699">
    @if ($controller === 'modelpage')
        @include('modelpage.StarsStyling')
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<div id="hideAll" class="collapse"></div>
<header class="container">
    <img src="{{ fileUrl('/img/HeaderChrome.jpg') }}" alt="Chrome wheels" id="headerImg" class="img-thumbnail hidden-xs">
    <div class="navbar navbar-toggleable-md navbar-light bg-faded">
        <button class="navbar-toggler navbar-toggler-right"
                type="button"
                data-toggle="collapse"
                data-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-header hidden-xs hidden-sm">
            <img src="{{ fileUrl('/img/CarRanker.png') }}" alt="Car Ranker" id="carrankerLogo" class="img-thumbnail">
        </div>
        <div class="navbar-collapse collapse" id="navbarCollapse">
            {!! Form::model($navform, ['route' => ['base.navigate'], 'class' => 'nav navbar-nav ml-auto navbar-right', 'id' => 'nav-form']) !!}
            <ul id="navmenuHeader" class="nav navbar-nav">
                @foreach ($menuHeader as $page)
                    <li class="nav-item navText"><a href="/{{ $page->getName() === 'home' ? '' :
                    strtolower($page->getName()) }}" class="nav-link">{{ $page->getTitle() }}</a></li>
                @endforeach
                @if ( $isLoggedIn )
                    <li class="nav-item navText"><a href="{{ route('logout') }}" class="nav-link">Logout</a></li>
                @else
                    <li class="nav-item navText"><a href="{{ route('login') }}" class="nav-link">Login</a></li>
                @endif
                <li class="nav-item">{!! Form::select('make', $makenames, null, ['class' => 'form-control', 'id' => 'nav_form_make']) !!}</li>
                <li class="nav-item">{!! Form::select('model', ['' => 'Model'], null, ['class' => 'form-control', 'id' => 'nav_form_model']) !!}</li>
                <li class="nav-item">{!! Form::text('search', null, ['class' => 'form-control', 'id' => 'nav_form_search', 'placeholder' => 'Search car...']) !!}</li>
                <li class="nav-item">{!! Form::submit('Go', ['class' => 'btn btn-primary', 'id' => 'nav_form_submit']) !!}</li>
            </ul>
            <input type="hidden" name="reCaptchaTokenNavbar" id="reCaptchaTokenNavbar">
            {!! Form::close() !!}
        </div>
    </div>
</header>
<div class="container">
    @yield('content')
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
    <img data-src="{{ fileUrl('/img/No123.jpg') }}" alt="Cars No 1, 2 and 3" id="numbercar" class="lazy img-thumbnail col-md-12">
    <BR>
    <script src="{{ fileUrl('/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ fileUrl('/js/popper.min.js') }}"></script>
    <script src="{{ fileUrl('/js/bootstrap.min.js') }}"></script>
    <script src="{{ fileUrl('/js/main.min.js') }}"></script>
    @if ($controller !== 'cms')
        <script src="{{ fileUrl('/js/' . $controller . '.min.js') }}"></script>
@endif
<!--All pages need to know the carbrands, carmodels and whether the page is in development mode.-->
    <script>
        var controller = "{{ $controller }}";
    </script>

    <!-- Global Site Tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-92295454-1"></script>
</footer>
</body>
</html>