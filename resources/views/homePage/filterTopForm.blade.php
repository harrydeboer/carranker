<?php declare(strict_types=1) ?>

<form method="get" action="{{ route('filterTop') }}" id="filter-top-form">
    <label for="min-num-votes" class="collapse-choice control-label">Minimum number of votes:</label>
    <input type="number" name="min-num-votes" class="collapse-choice form-control"
           step="1" value="{{ $minNumVotes }}" id="min-num-votes" required>
    <div class="row mx-auto col-md-12">
        <div id="choices" class="btn-group text-center">
            @foreach ($specsChoice as $specName => $spec)
                <div class="dropdown col-xl-6 col-lg-12 button-inline {{ $spec['show'] === true ? '' : 'collapse-choice' }}">
                    <button class="btn btn-primary specs-choice"
                            data-toggle="dropdown" id="filter-top-form{{ $specName }}">{{ $spec['display'] }}</button>
                    <table class="dropdown-menu">
                        <tr class="row">
                            <td class="col-md-8 col-md-offset-1">
                                <label for="specs-choice[check-all{{ $specName }}]">Select all/none</label>
                            </td>
                            <td class="col-md-2">
                                <input type="checkbox"
                                       id="specs-choice[check-all{{ $specName }}]"
                                       name="specs-choice[checkAll{{ $specName }}]"
                                       class="{{ $specName }} check-all"
                                       data-spec-name="{{ $specName }}"
                                       checked>
                            </td>
                        </tr>
                        @foreach ($spec['choices'] as $index => $choice)
                            <tr class="row">
                                <td class="col-md-8 col-md-offset-1"><label
                                            for="specs-choice[{{ $specName . $index }}]">{{ $choice }}</label></td>
                                <td class="col-md-2">
                                    <input type="checkbox"
                                           id="specs-choice[{{ $specName . $index }}]"
                                           name="specs-choice[{{ $specName . $index }}]"
                                           class="{{ $specName }}"
                                           checked>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endforeach
        </div>
        <table class="col-xl-8 col-lg-12 collapse-aspects" id="aspects-table">
            @foreach ($aspects as $aspect)
                <tr class="row aspect-filter">
                    <td class="col-xl-3 col-lg-4 aspect-name">
                        <label for="filter-top-form{{ $aspect }}">{{ $aspect }}</label>
                    </td>
                    <td class="col-xl-1 col-lg-2 aspect-min">0</td>
                    <td class="col-xl-6 col-lg-4 aspect-range">
                        <input value="1"
                               name="aspects[{{ $aspect }}]"
                               id="filter-top-form{{ $aspect }}"
                               type="range"
                               class="form-control aspect-element"
                               min="0"
                               max="5"
                               step="1">
                    </td>
                    <td class="col-xl-1 col-lg-2 aspect-max">5</td>
                </tr>
            @endforeach
        </table>
    </div>
    <table class="table" id="specs-range-table">
        @foreach ($specsRange as $specName => $spec)
            <tr class="row range-row {{ $spec['show'] === true ? '' : 'collapse-range' }}">
                <td class="col-md-4 col-sm-6">{{ $spec['display'] }}</td>
                <td class="col-md-3 col-sm-3">
                    <select name="specs-range[{{ $specName }}Min]" class="specs-range form-control">
                        @foreach($spec['minRange'] as $name => $value)
                            <option value="{{ $value }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="col-md-3 col-sm-3">
                    <select name="specs-range[{{ $specName }}Max]" class="specs-range form-control">
                        @foreach($spec['maxRange'] as $name => $value)
                            <option value="{{ $value }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="col-md-2 d-none d-md-block">{{ $spec['unit'] }}</td>
            </tr>
        @endforeach
    </table>
    <div class="row justify-content-center" id="buttons-show-filter-reset">
        <button class="btn btn-primary" id="filter-top-form-show-all">Show all options</button>
        <button class="btn btn-success" id="filter-top-form-submit">Filter the top!</button>
        <button class="btn btn-danger" id="filter-top-form-reset">Reset to default</button>
    </div>
</form>
