@if (count($topTrims) !== 0)
<table id="tableTop" class="table table-striped table-hover">
    @foreach ($topTrims as $index => $trim)
    <tr class="topRow row"><td class="col-md-2"><span class="numLinkTop">{{ ($index + 1) }}. </span></td>
        <td class="col-md-8"><div class="linkStyle"><a href="{{ $trim->getUrl() }}" class="linkTop">{{ $trim->getFullName() }}</a></div></td>
        <td class="col-md-2"><span class="ratingLinkTop">{{ number_format($trim->getRating(), 1) }} <span class="fa fa-star"></span></span></td></tr>
    @endforeach
</table>
@else
<div class="text-center"><br><br><h3>No cars meet your criteria.</h3><br><br></div>
@endif