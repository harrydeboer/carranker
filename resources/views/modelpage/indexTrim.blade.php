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
            <td colspan="4">{{ $trim->getPrice($FXRate) ? round($trim->getPrice($FXRate), -3) . ' $' : 'N/A' }}</td>
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
            <td class="col-md-6">&nbsp;{{ $trim->getFramework() }}</td>
        </tr>
        <tr class="row">
            <td class="col-md-6"></td>
            <td class="col-md-6">&nbsp;<strong>Fuel: </strong></td>
        </tr>
        <tr class="row">
            <td class="col-md-6">
                @if (fileUrl($trim->getFuelImage()) !== "")
                    <img src="{{ fileUrl($trim->getFuelImage()) }}" alt="fuel">
                @endif</td>
            <td class="col-md-6">&nbsp;{{ $trim->getFuel() !== "" ? $trim->getFuel() : 'N/A' }}</td>
        </tr>
        <tr class="row">
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr class="row {{ $specsChoice['number_of_doors']['show'] ? '' : 'collapseSpecs' }}">
            <td class="col-md-6">{{ $specsChoice['number_of_doors']['display'] }}</td>
            <td class="col-md-6">&nbsp;{{ $trim->getNumberOfDoors() }}</td>
        </tr>
        <tr class="row {{ $specsChoice['number_of_seats']['show'] ? '' : 'collapseSpecs' }}">
            <td class="col-md-6">{{ $specsChoice['number_of_seats']['display'] }}</td>
            <td class="col-md-6">&nbsp;{{ $trim->getNumberOfSeats() }}</td>
        </tr>
        <tr class="row {{ $specsChoice['number_of_gears']['show'] ? '' : 'collapseSpecs' }}">
            <td class="col-md-6">{{ $specsChoice['number_of_gears']['display'] }}</td>
            <td class="col-md-6">&nbsp;{{ $trim->getNumberOfGears() }}</td>
        </tr>
        <tr class="row {{ $specsChoice['gearbox_type']['show'] ? '' : 'collapseSpecs' }}">
            <td class="col-md-6">{{ $specsChoice['gearbox_type']['display'] }}</td>
            <td class="col-md-6">&nbsp;{{ $trim->getTransmission() }}</td>
        </tr>
        <tr class="row {{ $specsRange['max_trunk_capacity']['show'] ? '' : 'collapseSpecs' }}">
            <td class="col-md-6">{{ $specsRange['max_trunk_capacity']['display'] }}</td>
            <td class="col-md-6">&nbsp;{{ $trim->getMaxTrunkCapacity() . ' ' . $specsRange['max_trunk_capacity']['unit'] }}</td>
        </tr>
        <tr class="row {{ $specsRange['engine_capacity']['show'] ? '' : 'collapseSpecs' }}">
            <td class="col-md-6">{{ $specsRange['engine_capacity']['display'] }}</td>
            <td class="col-md-6">&nbsp;{{ $trim->getEngineCapacity() . ' ' . $specsRange['engine_capacity']['unit'] }}</td>
        </tr>
        <tr class="row {{ $specsRange['fueltank_capacity']['show'] ? '' : 'collapseSpecs' }}">
            <td class="col-md-6">{{ $specsRange['fueltank_capacity']['display'] }}</td>
            <td class="col-md-6">&nbsp;{{ $trim->getFueltankCapacity() . ' ' . $specsRange['fueltank_capacity']['unit'] }}</td>
        </tr>
        <tr class="row {{ $specsRange['max_speed']['show'] ? '' : 'collapseSpecs' }}">
            <td class="col-md-6">{{ $specsRange['max_speed']['display'] }}</td>
            <td class="col-md-6">&nbsp;{{ $trim->getMaxSpeed() . ' ' . $specsRange['max_speed']['unit'] }}</td>
        </tr>
        <tr class="row {{ $specsRange['full_weight']['show'] ? '' : 'collapseSpecs' }}">
            <td class="col-md-6">{{ $specsRange['full_weight']['display'] }}</td>
            <td class="col-md-6">&nbsp;{{ $trim->getFullWeight() . ' ' . $specsRange['full_weight']['unit'] }}</td>
        </tr>
        <tr class="row {{ $specsRange['engine_power']['show'] ? '' : 'collapseSpecs' }}">
            <td class="col-md-6">{{ $specsRange['engine_power']['display'] }}</td>
            <td class="col-md-6">&nbsp;{{ $trim->getEnginePower() . ' ' . $specsRange['engine_power']['unit'] }}</td>
        </tr>
        <tr class="row {{ $specsRange['acceleration']['show'] ? '' : 'collapseSpecs' }}">
            <td class="col-md-6">{{ $specsRange['acceleration']['display'] }}</td>
            <td class="col-md-6">&nbsp;{{ $trim->getAcceleration() . ' ' . $specsRange['acceleration']['unit'] }}</td>
        </tr>
        <tr class="row {{ $specsRange['fuel_consumption']['show'] ? '' : 'collapseSpecs' }}">
            <td class="col-md-6">{{ $specsRange['fuel_consumption']['display'] }}</td>
            <td class="col-md-6">&nbsp;{{ $trim->getFuelConsumption() . ' ' . $specsRange['fuel_consumption']['unit'] }}</td>
        </tr>
    </table>
</div>
<div class="modal-footer">
    <button class="showAllSpecs btn btn-primary" id="showAllSpecs{{ $id }}">Show all specs</button>
    <button class="btn btn-danger" data-dismiss="modal">Close</button>
</div>