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
        z-index: 9999;
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

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="{{ $metaKeyWords }}" >
<meta name="description" content="{{ $metaDescription }}" >
<meta name="author" content="Harry de Boer" >
<meta http-equiv="content-type" content="text/html; charset=utf-8" >
<meta name="theme-color" content="#336699">
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="apple-touch-icon" sizes="180x180" href="{{ fileUrl('/img/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" href="{{ fileUrl('/img/favicon-32x32.png') }}" sizes="32x32">
<link rel="icon" type="image/png" href="{{ fileUrl('/img/favicon-16x16.png') }}" sizes="16x16">
<link rel="manifest" href="{{ fileUrl('/img/manifest.json') }}">
<link rel="mask-icon" href="{{ fileUrl('/img/safari-pinned-tab.svg') }}">
<link rel="stylesheet" href="{{ fileUrl('/css/' . $controller . '.css') }}">

@if ($controller === 'modelpage')
    <style>
        .fa-star-form {
            color: lightgrey;
            float: right;
            border-color: #ddd;
            font-size: 30px;
            margin-right: 7px;
        }

        .radioStar {
            position: absolute;
            opacity: 0;
            z-index: 0;
            float: right;
            width: 1px;
            margin: 0;
            white-space: nowrap;
        }

        .label:hover {
            color: blue;
        }


        @foreach ($aspects as $key => $aspect)
        {{ '.radio' . $key . ':checked ~ .label' . $key }} {
            color: gold;
        }
        {{ '.label' . $key . ':hover, .label' . $key . ':hover ~ .label' . $key }} {
            color: blue !important;
        }
        @endforeach
    </style>
@endif

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
            if ({{ $lazyLoad === true ? 'true' : 'false'}}) {
                setTimeout(lazyload, 3000);
                document.addEventListener("scroll", lazyload);
                window.addEventListener("resize", lazyload);
                window.addEventListener("orientationChange", lazyload);
                document.addEventListener("visibilitychange", lazyload);
            }
        } else {
            lazyload();
        }
    });
</script>
