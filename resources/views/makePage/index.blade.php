@extends('layout')

@section('content')
    <div class="row">
        <section id="make-text" class="col-md-9">
            <h3 id="title-make">{{ $make->getName() }}</h3>
            @if ($make->getImage() !== '')
                <img src="{{ fileUrl($make->getImage()) }}" id="make-img"
                     alt="{{ $make->getName() }}" class="img-thumbnail pull-right">
            @endif
            @if (!is_null($make->getContent()))
                {!! $make->getContent() !!}
                <div id="reference">
                    <br>
                    <a href="https://en.wikipedia.org/wiki/{{ $make->getWikiCarMake() }}">Source Wikipedia</a>
                </div>
            @endif
        </section>
        <div id="aside-make" class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title text-center"><h3>Models</h3></div>
                </div>
                <div class="panel-body text-center">
                    @foreach ($models as $model)
                        <div>
                            <a href="{{ $model->getUrl() }}" class="aside-link" >{{ $model->getName() }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
