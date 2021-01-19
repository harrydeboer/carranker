<?php declare(strict_types=1) ?>

<div class="navbar navbar-default text-center">
  <ul id="nav-menu-footer" class="nav navbar-nav">
    @if (!is_null($menuFooter))
      @foreach ($menuFooter as $page)
        <li class="nav-item navText">
          <a href="/{{ strtolower($page->getName()) }}" class="nav-link">{{ $page->getTitle() }}&nbsp;&nbsp;</a>
        </li>
      @endforeach
    @endif
  </ul>
</div>
<div class="container text-center">This site is protected by reCAPTCHA and the Google
  <a href="https://policies.google.com/privacy">Privacy Policy</a> and
  <a href="https://policies.google.com/terms">Terms of Service</a> apply.
</div>
<BR>
<img data-src="{{ fileUrl('/img/No123.jpg') }}"
     src="{{ $controller === 'homepage' ? fileUrl('/img/favicon-16x16.png') : fileUrl('/img/No123.jpg') }}"
     alt="No 1 2 3"
     class="{{ $controller === 'homepage' ? 'lazy' : '' }} img-thumbnail col-md-12 d-none d-lg-block">
<BR>
<script src="{{ fileUrl('/js/app.min.js') }}"></script>
@if (env('APP_ENV') === 'local')
  <script src="{{ fileUrl('/js/lazyLoad.js') }}"></script>
@else
  <script src="{{ fileUrl('/js/lazyLoad.min.js') }}"></script>
@endif
@if (env('APP_ENV') === 'local')
  <script src="{{ fileUrl('/js/web.js') }}"></script>
  <script src="{{ fileUrl('/js/' . $controller . '.js') }}"></script>
@else
  <script src="{{ fileUrl('/js/web.min.js') }}"></script>
  <script src="{{ fileUrl('/js/' . $controller . '.min.js') }}"></script>
@endif
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-92295454-1"></script>
