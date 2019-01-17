<div class="modal-header text-center">
    <h3 class="title">
        @if ($hasTrimTypes)
            {{ $trim->getYearBegin() . '-' . $trim->getYearEnd() . ' ' . $trim->getFramework() . ' ' . $trim->getName() }}
        @else
            {{ $trim->getYearBegin() . '-' . $trim->getYearEnd() . ' ' . $trim->getFramework() }}
        @endif
    </h3>
</div>
<div class="modal-body">
    <table class="ratingTrimTable">
        <tr>
            <td></td>
            <td colspan="2">Peoples({{ $trim->getVotes() }})&nbsp;</td>
            <td colspan="2">Yours</td>
        </tr>
        <tr>
            <td>{{ 'Rating' }}:</td>
            <td>{{ number_format($trim->getRating(), 1) }}</td>
            <td><span class="fa fa-star fa-star-shadow"></span></td>
            <td>{{ $rating ? $rating->getRating() : '-' }}</td>
            <td><span class="fa fa-star fa-star-shadow"></span></td>
        </tr>
        <tr>
            <td colspan="5" class="spaceUnder"></td>
        </tr>
        @foreach ($aspects as $keyAspect => $aspect)
        <tr><td>{{ ucfirst($aspect) }}:</td>
            <td>{{ number_format($trim->getAspect($aspect), 1) }}</td>
            <td><span class="fa fa-star fa-star-shadow"></span></td>
            <td>{{ $rating ? $rating->getAspect($aspect) : '-' }}</td>
            <td><span class="fa fa-star fa-star-shadow"></span></td>
        </tr>
        @endforeach
        <tr>
            <td><span class="nowrap">Price:</span></td>
            <td colspan="4">{{ round($trim->getPrice($FXRate), -3) }} $</td>
        </tr>
    </table>
    @if ($isLoggedIn)
        <div class="text-center">
            <a href="#dialog" class="toRateTrim btn btn-primary" data-toggle="modal"
               data-generation="{{ $trim->getYearBegin() . '-' . $trim->getYearEnd() }}"
               data-serie="{{ $trim->getFramework() }}" data-idtrim="{{ $id }}">Rate this car!</a>
        </div>
    @endif
    <table class="specsTrim">
        <tr class="row">
            <td class="col-md-6"><strong>Generation: </strong></td>
            <td class="col-md-6">{{ $trim->getYearBegin() . '-' . $trim->getYearEnd() }}</td>
        </tr>
        <tr class="row">
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr class="row">
            <td class="col-md-6"></td>
            <td class="col-md-6">&nbsp;<strong>Body type: </strong></td>
        </tr>
        <tr class="row">
            <td class="col-md-6"><img src="{{ fileUrl($trim->getFrameworkImage()) }}" alt="framework"></td>
            <td class="col-md-6">&nbsp;{{ $trim->getSpec('framework') }}</td>
        </tr>
        <tr class="row">
            <td class="col-md-6"></td>
            <td class="col-md-6">&nbsp;<strong>Fuel: </strong></td>
        </tr>
        <tr class="row">
            <td class="col-md-6"><img src="{{ fileUrl($trim->getFuelImage()) }}" alt="fuel"></td>
            <td class="col-md-6">&nbsp;{{ $trim->getSpec('fuel') }}</td>
        </tr>
        <tr class="row">
            <td colspan="2">&nbsp;</td>
        </tr>
        @foreach ($specsChoice as $specname => $spec)
            @if ($specname === 'framework' || $specname === 'fuel')
                @continue
            @endif
            <tr class="row {{ $spec['show'] ? '' : 'collapseSpecs' }}">
                <td class="col-md-6">{{ $spec['display'] }}</td>
                <td class="col-md-6">&nbsp;{{ $trim->getSpec($specname) }}</td>
            </tr>
        @endforeach
        @foreach ($specsRange as $specname => $spec)
            @if ($specname === 'generation' || $specname === 'price')
                @continue
            @endif
            <tr class="row {{ $spec['show'] ? '' : 'collapseSpecs' }}">
                <td class="col-md-6">{{ $spec['display'] }}</td>
                <td class="col-md-6">&nbsp;{{ $trim->getSpec($specname) . ' ' . $spec['unit'] }}</td>
            </tr>
        @endforeach
    </table>
</div>
<div class="modal-footer">
    <button class="showAllSpecs btn btn-primary" id="showAllSpecs{{ $id }}">Show all specs</button>
    <button class="btn btn-danger" data-dismiss="modal">Close</button>
</div>