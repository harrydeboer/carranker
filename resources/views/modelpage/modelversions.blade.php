<div class="text-center">
    <div id="titleTypes">Modelversions</div>
    Generation:
    <select id="generationSelect">
        @foreach ($generationsSeriesTrims as $keyGen => $generation)
            <option {{ $selectedGeneration === $keyGen ? 'selected' : '' }}>{{ $keyGen }}</option>
        @endforeach
    </select>
</div>
@foreach ($generationsSeriesTrims as $keyGen => $generation)
    <div id="generation{{ $keyGen }}" class="generations text-center {{ $selectedGeneration === $keyGen ? '' : 'collapse' }}">
        @foreach ($generation as $keySer => $serie)
            @if ($hasTrimTypes === true)
                <div>{{ $keySer }}</div>
                @foreach ($serie as $nameTrim => $valueTrim)
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
                    <a href="#typeInfo{{ $serie[0] }}" data-toggle="modal"
                       id="linkType{{ $serie[0] }}" class="linkType">{{ $keySer }}</a>
                </div>
            @endif
        @endforeach
    </div>
@endforeach