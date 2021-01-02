@foreach ($trims as $index => $trim)
    <tr class="topRow row">
        <td class="col-md-2"><span class="numLinkTop">{{ ($offset + $index + 1) }}. </span></td>
        <td class="col-md-8">
            <div class="linkStyle">
                <a href="{{ $trim->getUrl() }}" class="linkTop">{{ $trim->getFullName() }}</a>
            </div>
        </td>
        <td class="col-md-2">
            <span class="ratingLinkTop">
                {{ number_format($trim->getRating(), 1) }} <span class="fa fa-star"></span>
            </span>
        </td>
    </tr>
@endforeach