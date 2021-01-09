<div class="text-center">
    <div id="titleTypes">Trims</div>
    Generation:
    <select id="generationSelect">
        @foreach ($generationsSeriesTrims as $keyGen => $generation)
            <option value="{{ $keyGen }}" {{ $selectedGeneration === $keyGen ? 'selected' : '' }}>{{ $keyGen }}</option>
        @endforeach
    </select>
</div>
@foreach ($generationsSeriesTrims as $keyGen => $generation)
    <div id="generation{{ $keyGen }}" class="generations text-center {{ $selectedGeneration === $keyGen ? '' : 'collapse' }}">
        @foreach ($generation as $keySer => $series)
            @if (count($series) > 1)
                <div>{{ $keySer }}</div>
                @foreach ($series as $nameTrim => $valueTrim)
                    <div>
                        <a href="#typeInfo{{ $valueTrim }}"
                            data-toggle="modal"
                            id="linkType{{ $valueTrim }}"
                            class="linkType trimType">{{ $nameTrim ?? $model->getName() }}
                        </a>
                    </div>
                @endforeach
            @else
                <div>
                    <a href="#typeInfo{{ $series[0] }}" data-toggle="modal"
                       id="linkType{{ $series[0] }}" class="linkType">{{ $keySer }}</a>
                </div>
            @endif
        @endforeach
    </div>
@endforeach
