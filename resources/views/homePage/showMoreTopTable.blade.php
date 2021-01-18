<?php declare(strict_types=1) ?>
@foreach ($trims as $index => $trim)
    <tr class="top-row row">
        <td class="col-md-2"><span class="num-link-top">{{ ($offset + $index + 1) }}. </span></td>
        <td class="col-md-8">
            <div class="link-style">
                <a href="{{ $trim->getUrl() }}" class="link-top">{{ $trim->getFullName() }}</a>
            </div>
        </td>
        <td class="col-md-2">
            <span class="rating-link-top">
                {{ number_format($trim->getRating(), 1) }} <span class="fa fa-star"></span>
            </span>
        </td>
    </tr>
@endforeach
