<?php declare(strict_types=1) ?>

<div class="modal-header text-center">
    <h3 class="title">
        @if (is_null($trim->getName()))
            {{ $trim->getYearBegin() . '-' . $trim->getYearEnd() . ' ' . $trim->getFramework() }}
        @else
            {{ $trim->getYearBegin() . '-' . $trim->getYearEnd() . ' ' . $trim->getFramework() . ' ' . $trim->getName() }}
        @endif
    </h3>
</div>
<div class="modal-body">
    <table class="rating-trim-table">
        <tr>
            <td></td>
            <td colspan="2">Peoples({{ $trim->getVotes() }})&nbsp;</td>
            <td colspan="2">Yours</td>
        </tr>
        <tr>
            <td>{{ 'Rating' }}:</td>
            <td>{{ is_null($trim->getRating()) ? '-' : number_format($trim->getRating(), 1) }}</td>
            <td><span class="fa fa-star fa-star-shadow"></span></td>
            <td>{{ is_null($rating) ?  '-' : $rating->getRating() }}</td>
            <td><span class="fa fa-star fa-star-shadow"></span></td>
        </tr>
        <tr>
            <td colspan="5" class="space-under"></td>
        </tr>
        @foreach ($aspects as $keyAspect => $aspect)
        <tr><td>{{ ucfirst($aspect) }}:</td>
            <td>{{ is_null($trim->getAspect($aspect)) ? '-' : number_format($trim->getAspect($aspect), 1) }}</td>
            <td><span class="fa fa-star fa-star-shadow"></span></td>
            <td>{{ is_null($rating) ? '-' : $rating->getAspect($aspect)}}</td>
            <td><span class="fa fa-star fa-star-shadow"></span></td>
        </tr>
        @endforeach
        <tr>
            <td><span class="nowrap">Price:</span></td>
            <td colspan="4">{{ is_null($trim->getPrice($FXRate)) ? 'N/A' : round($trim->getPrice($FXRate), -3) . ' $'}}</td>
        </tr>
    </table>
    @if ($isLoggedIn === true)
        <div class="text-center">
            <button class="to-rate-trim btn btn-primary" data-toggle="modal" data-target="#dialog"
               data-generation="{{ $trim->getYearBegin() . '-' . $trim->getYearEnd() }}"
               data-series="{{ $trim->getFramework() ?? 'N/A' }}" data-id-trim="{{ $id }}">Rate this car!</button>
        </div>
    @endif
    <table class="specs-trim">
        <tr class="row">
            <td class="col-5"><strong>Generation: </strong></td>
            <td class="col-7">{{ $trim->getYearBegin() . '-' . $trim->getYearEnd() }}</td>
        </tr>
        <tr class="row">
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr class="row">
            <td class="col-5"></td>
            <td class="col-7">&nbsp;<strong>Body type: </strong></td>
        </tr>
        <tr class="row">
            <td class="col-5"><img src="{{ fileUrl($trim->getFrameworkImage()) }}" alt="framework"></td>
            <td class="col-7">&nbsp;{{ $trim->getFramework() ?? 'N/A' }}</td>
        </tr>
        <tr class="row">
            <td class="col-5"></td>
            <td class="col-7">&nbsp;<strong>Fuel: </strong></td>
        </tr>
        <tr class="row">
            <td class="col-5">
                @if (fileUrl($trim->getFuelImage()) !== "")
                    <img src="{{ fileUrl($trim->getFuelImage()) }}" alt="fuel">
                @endif</td>
            <td class="col-7">&nbsp;{{ $trim->getFuel() ?? 'N/A' }}</td>
        </tr>
        <tr class="row">
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr class="row {{ $specsChoice['number_of_doors']['show'] === true ? '' : 'collapse-specs' }}">
            <td class="col-5">{{ $specsChoice['number_of_doors']['display'] }}</td>
            <td class="col-7">&nbsp;{{ $trim->getNumberOfDoors() ?? 'N/A' }}</td>
        </tr>
        <tr class="row {{ $specsChoice['number_of_seats']['show'] === true ? '' : 'collapse-specs' }}">
            <td class="col-5">{{ $specsChoice['number_of_seats']['display'] }}</td>
            <td class="col-7">&nbsp;{{ $trim->getNumberOfSeats() ?? 'N/A' }}</td>
        </tr>
        <tr class="row {{ $specsChoice['number_of_gears']['show'] === true ? '' : 'collapse-specs' }}">
            <td class="col-5">{{ $specsChoice['number_of_gears']['display'] }}</td>
            <td class="col-7">&nbsp;{{ $trim->getNumberOfGears() ?? 'N/A' }}</td>
        </tr>
        <tr class="row {{ $specsChoice['gearbox_type']['show'] === true ? '' : 'collapse-specs' }}">
            <td class="col-5">{{ $specsChoice['gearbox_type']['display'] }}</td>
            <td class="col-7">&nbsp;{{ $trim->getTransmission() ?? 'N/A' }}</td>
        </tr>
        <tr class="row {{ $specsRange['max_trunk_capacity']['show'] === true ? '' : 'collapse-specs' }}">
            <td class="col-5">{{ $specsRange['max_trunk_capacity']['display'] }}</td>
            <td class="col-7">&nbsp;{{ is_null($trim->getMaxTrunkCapacity()) ? 'N/A' : $trim->getMaxTrunkCapacity() .
            ' ' . $specsRange['max_trunk_capacity']['unit'] }}</td>
        </tr>
        <tr class="row {{ $specsRange['engine_capacity']['show'] === true ? '' : 'collapse-specs' }}">
            <td class="col-5">{{ $specsRange['engine_capacity']['display'] }}</td>
            <td class="col-7">&nbsp;{{ is_null($trim->getEngineCapacity()) ? 'N/A' : $trim->getEngineCapacity() .
            ' ' . $specsRange['engine_capacity']['unit'] }}</td>
        </tr>
        <tr class="row {{ $specsRange['fueltank_capacity']['show'] === true ? '' : 'collapse-specs' }}">
            <td class="col-5">{{ $specsRange['fueltank_capacity']['display'] }}</td>
            <td class="col-7">&nbsp;{{ is_null($trim->getFueltankCapacity()) ? 'N/A' : $trim->getFueltankCapacity() .
            ' ' . $specsRange['fueltank_capacity']['unit'] }}</td>
        </tr>
        <tr class="row {{ $specsRange['max_speed']['show'] === true ? '' : 'collapse-specs' }}">
            <td class="col-5">{{ $specsRange['max_speed']['display'] }}</td>
            <td class="col-7">&nbsp;{{ is_null($trim->getMaxSpeed()) ? 'N/A' : $trim->getMaxSpeed() .
            ' ' . $specsRange['max_speed']['unit'] }}</td>
        </tr>
        <tr class="row {{ $specsRange['full_weight']['show'] === true ? '' : 'collapse-specs' }}">
            <td class="col-5">{{ $specsRange['full_weight']['display'] }}</td>
            <td class="col-7">&nbsp;{{ is_null($trim->getFullWeight()) ? 'N/A' : $trim->getFullWeight() .
            ' ' . $specsRange['full_weight']['unit'] }}</td>
        </tr>
        <tr class="row {{ $specsRange['engine_power']['show'] === true ? '' : 'collapse-specs' }}">
            <td class="col-5">{{ $specsRange['engine_power']['display'] }}</td>
            <td class="col-7">&nbsp;{{ is_null($trim->getEnginePower()) ? 'N/A' : $trim->getEnginePower() .
            ' ' . $specsRange['engine_power']['unit'] }}</td>
        </tr>
        <tr class="row {{ $specsRange['acceleration']['show'] === true ? '' : 'collapse-specs' }}">
            <td class="col-5">{{ $specsRange['acceleration']['display'] }}</td>
            <td class="col-7">&nbsp;{{ is_null($trim->getAcceleration()) ? 'N/A' : $trim->getAcceleration() .
            ' ' . $specsRange['acceleration']['unit'] }}</td>
        </tr>
        <tr class="row {{ $specsRange['fuel_consumption']['show'] === true ? '' : 'collapse-specs' }}">
            <td class="col-5">{{ $specsRange['fuel_consumption']['display'] }}</td>
            <td class="col-7">&nbsp;{{ is_null($trim->getFuelConsumption()) ? 'N/A' : $trim->getFuelConsumption() .
            ' ' . $specsRange['fuel_consumption']['unit'] }}</td>
        </tr>
    </table>
</div>
<div class="modal-footer">
    <button class="show-all-specs btn btn-primary" id="show-all-specs{{ $id }}">Show all specs</button>
    <button class="btn btn-danger" data-dismiss="modal">Close</button>
</div>
