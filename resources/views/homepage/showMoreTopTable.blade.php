@foreach ($trims as $index => $car)
    <tr class="topRow row">
        <td class="col-md-2"><span class="numLinkTop">{{ ($offset + $index + 1) }}. </span></td>
        <td class="col-md-8">
            <div class="linkStyle">
                <a href="{{ $car->getUrl() }}" class="linkTop">{{ $car->getFullName() }}</a>
            </div>
        </td>
        <td class="col-md-2">
            <span class="ratingLinkTop">
                {{ number_format($car->getRating(), 1) }} <span class="fa fa-star"></span>
            </span>
        </td>
    </tr>
@endforeach