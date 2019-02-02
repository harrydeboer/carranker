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
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" href="{{ fileUrl('/css/' . $controller . '.css') }}">
