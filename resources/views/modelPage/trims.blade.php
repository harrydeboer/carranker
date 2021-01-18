<?php declare(strict_types=1) ?>

<div class="text-center">
    <div id="title-types">Trims</div>
    Generation:
    <select id="generation-select">
        @foreach ($generationsSeriesTrims as $keyGen => $generation)
            <option value="{{ $keyGen }}" {{ $selectedGeneration === $keyGen ? 'selected' : '' }}>{{ $keyGen }}</option>
        @endforeach
    </select>
</div>
@foreach ($generationsSeriesTrims as $keyGen => $generation)
    <div id="generation{{ $keyGen }}"
         class="generations text-center {{ $selectedGeneration === $keyGen ? '' : 'collapse' }}">
        @foreach ($generation as $keySer => $series)
            @if (count($series) > 1)
                <div>{{ $keySer }}</div>
                @foreach ($series as $nameTrim => $valueTrim)
                    <div>
                        <a href="#type-info{{ $valueTrim }}"
                           data-toggle="modal"
                           id="link-type{{ $valueTrim }}"
                           class="link-type trim-type">{{ $nameTrim ?? $model->getName() }}
                        </a>
                    </div>
                @endforeach
            @else
                <div>
                    <a href="#type-info{{ $series[0] }}" data-toggle="modal"
                       id="link-type{{ $series[0] }}" class="link-type">{{ $keySer }}</a>
                </div>
            @endif
        @endforeach
    </div>
@endforeach
