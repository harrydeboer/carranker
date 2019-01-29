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
