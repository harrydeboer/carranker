<?php declare(strict_types=1) ?>
<div id="carousel" class="carousel slide" data-ride="carousel" data-interval="3000">
    <h3>Top&nbsp;{{ $topLengthSlider }}</h3>
    <ol class="carousel-indicators">
        @for ($index = 0; $index < $topLengthSlider; $index++)
            <li data-target="#carousel" data-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
        @endfor
    </ol>
    <div class="carousel-inner" role="listbox">
        @for ($index = 0; $index < $topLengthSlider; $index++)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <a href="{{ $topTrims[$index]->getUrl() }}">
                    <img src="{{ $index !== 0 && !isset($lazyLoad) ? fileUrl('/img/favicon-16x16.png') :
                              fileUrl($topTrims[$index]->getImage()) }}"
                         data-src="{{ fileUrl($topTrims[$index]->getImage()) }}"
                         class="{{ $index !== 0 && !isset($lazyLoad) ? 'lazy' : '' }} car-carousel-img d-block img-fluid"
                         alt="{{ $topTrims[$index]->getMakeName() . ' ' . $topTrims[$index]->getModelName() }}"></a>
                <div class="carousel-caption {{ $index === 0 ? 'active' : '' }}">
                    {{ ($index + 1) . '. ' . $topTrims[$index]->getMakeName() . ' ' .
                    $topTrims[$index]->getModelName() . ' ' .
                    number_format($topTrims[$index]->getRatingFiltering(), 1) }} <span class="fa fa-star"></span>
                </div>
            </div>
        @endfor
    </div>
    <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carousel" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
