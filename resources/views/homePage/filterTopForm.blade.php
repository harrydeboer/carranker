<form method="get" action="{{ route('filterTop') }}" id="filterTopForm">
    <label for="minNumVotes" class="collapseChoice control-label">Minimum number of votes:</label>
    <input type="number" name="minNumVotes" class="collapseChoice form-control"
           step="1" value="{{ $minNumVotes }}" id="minNumVotes" required>
    <div class="row mx-auto col-md-12">
        <div id="choices" class="btn-group text-center">
            @foreach ($specsChoice as $specName => $spec)
                <div class="dropdown col-xl-6 col-lg-12 button-inline {{ $spec['show'] === true ? '' : 'collapseChoice' }}">
                    <button class="btn btn-primary specsChoice"
                            data-toggle="dropdown" id="filterTopForm{{ $specName }}">{{ $spec['display'] }}</button>
                    <table class="dropdown-menu">
                        <tr class="row">
                            <td class="col-md-8 col-md-offset-1">
                                <label for="specsChoice[checkAll{{ $specName }}]">Select all/none</label>
                            </td>
                            <td class="col-md-2">
                                <input type="checkbox"
                                       id="specsChoice[checkAll{{ $specName }}]"
                                       name="specsChoice[checkAll{{ $specName }}]"
                                       class="{{ $specName }} checkAll"
                                       data-specname="{{ $specName }}"
                                        {{ isset($formData['specsChoice']['checkAll' . $specName]) || $formData === [] ? 'checked' : '' }}>
                            </td>
                        </tr>
                        @foreach ($spec['choices'] as $index => $choice)
                            <tr class="row">
                                <td class="col-md-8 col-md-offset-1"><label
                                            for="specsChoice[{{ $specName . $index }}]">{{ $choice }}</label></td>
                                <td class="col-md-2">
                                    <input type="checkbox"
                                           id="specsChoice[{{ $specName . $index }}]"
                                           name="specsChoice[{{ $specName . $index }}]"
                                           class="{{ $specName }}"
                                            {{ isset($formData['specsChoice'][$specName . $index]) || $formData === [] ? 'checked' : '' }}
                                    ></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endforeach
        </div>
        <table class="col-xl-8 col-lg-12 collapseAspects" id="aspectsTable">
            @foreach ($aspects as $aspect)
                <tr class="row aspectFilter">
                    <td class="col-xl-3 col-lg-4 aspectName">
                        <label for="filterTopForm{{ $aspect }}">{{ $aspect }}</label>
                    </td>
                    <td class="col-xl-1 col-lg-2 aspectMin">0</td>
                    <td class="col-xl-6 col-lg-4 aspectRange">
                        <input value="{{ $formData['aspects'][$aspect] ?? '1' }}"
                               name="aspects[{{ $aspect }}]"
                               id="filterTopForm{{ $aspect }}"
                               type="range"
                               class="form-control aspectElement"
                               min="0"
                               max="5"
                               step="1">
                    </td>
                    <td class="col-xl-1 col-lg-2 aspectMax">5</td>
                </tr>
            @endforeach
        </table>
    </div>
    <table class="table" id="specsRangeTable">
        @foreach ($specsRange as $specName => $spec)
            <tr class="row rangeRow {{ $spec['show'] === true ? '' : 'collapseRange' }}">
                <td class="col-md-4 col-sm-6">{{ $spec['display'] }}</td>
                <td class="col-md-3 col-sm-3">
                    <select name="specsRange[{{ $specName }}min]" class="specsRange form-control">
                        @foreach($spec['minRange'] as $name => $value)
                            @if (isset($formData['specsRange'][$specName . 'min'])
                                 && $formData['specsRange'][$specName . 'min'] === $value)
                                <option value="{{ $value }}" selected>{{ $name }}</option>
                            @else
                                <option value="{{ $value }}">{{ $name }}</option>
                            @endif
                        @endforeach
                    </select>
                </td>
                <td class="col-md-3 col-sm-3">
                    <select name="specsRange[{{ $specName }}max]" class="specsRange form-control">
                        @foreach($spec['maxRange'] as $name => $value)
                            @if (isset($formData['specsRange'][$specName . 'max'])
                                 && $formData['specsRange'][$specName . 'max'] === $value)
                                <option value="{{ $value }}" selected>{{ $name }}</option>
                            @else
                                <option value="{{ $value }}">{{ $name }}</option>
                            @endif
                        @endforeach
                    </select>
                </td>
                <td class="col-md-2 d-none d-md-block">{{ $spec['unit'] }}</td>
            </tr>
        @endforeach
    </table>
    <div class="row justify-content-center" id="buttonsShowFilterReset">
        <button class="btn btn-primary" id="filterTopFormShowAll">Show all options</button>
        <button class="btn btn-success" id="filterTopFormSubmit">Filter the top!</button>
        <button class="btn btn-danger" id="filterTopFormReset">Reset to default</button>
    </div>
</form>