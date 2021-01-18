<?php declare(strict_types=1) ?>

@if (count($topTrims) !== 0)
<table id="table-top" class="table table-striped table-hover">
    @foreach ($topTrims as $index => $trim)
    <tr class="top-row row">
        <td class="col-2">
            <span class="num-link-top">{{ ($index + 1) }}. </span>
        </td>
        <td class="col-8"><div class="link-style">
                <a href="{{ $trim->getUrl() }}" class="link-top">{{ $trim->getFullName() }}</a>
            </div>
        </td>
        <td class="col-2">
            <span class="rating-link-top">{{ number_format($trim->getRatingFiltering(), 1) }} <span class="fa fa-star"></span>
            </span>
        </td>
    </tr>
    @endforeach
</table>
@else
<div class="text-center"><br><br><h3>No cars meet your criteria.</h3><br><br></div>
@endif
