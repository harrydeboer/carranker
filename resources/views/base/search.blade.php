<div class="text-center">
    <h2>Search results</h2>
    @if (count($trims) > 0)
        <h3>Modelversions</h3>
        @foreach ($trims as $trim)
            <div>
                <a href="{{ $trim->getUrl() }}" class="searchLink">
                    {{ $trim->getMakename() . ' ' . $trim->getModelname() . ' ' . $trim->getYearBegin() .
                    '-' . $trim->getYearEnd() . ' ' . $trim->getFramework() . ' ' . $trim->getName() }}
                </a>
            </div>
        @endforeach
    @endif
    @if (count($models) > 0)
        <h3>Models</h3>
        @foreach ($models as $model)
            <div>
                <a href="/model/{{ rawurlencode($model->getMakename()) }}/{{ rawurlencode($model->getName()) }}"
                   class="searchLink">{{ $model->getMakename() . ' ' . $model->getName() }}
                </a>
            </div>
        @endforeach
    @endif
    @if (count($makes) > 0)
        <h3>Makes</h3>
        @foreach ($makes as $make)
            <div>
                <a href="/make/{{ rawurlencode($make->getName()) }}" class="searchLink">{{ $make->getName() }}</a>
            </div>
        @endforeach
    @endif
</div>