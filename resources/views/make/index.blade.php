@extends('layout')

@section('content')
    <div class="row">
        <section id="makeText" class="col-md-9">
            <h3 id="titleMake">{{ $make->getName() }}</h3>
            <img src="{{ fileUrl($make->getImage()) }}" id="makeImg" alt="{{ $make->getName() }}" class="img-thumbnail pull-right">
            @if (!is_null($make->getContent()))
                {!! $make->getContent() !!}
            @endif
            <div id="reference">
                <a href="https://en.wikipedia.org/wiki/{{ $make->getWikiCarMake() }}">Source Wikipedia</a>
            </div>
        </section>
        <div id="asideMake" class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title text-center"><h3>Models</h3></div>
                </div>
                <div class="panel-body text-center">
                    @foreach ($models as $model)
                        <div><a href = "{{ '/model/' . urlencode($make->getName()) . '/' .
                            urlencode($model->getName()) }}" class="asideLink" >{{ $model->getName() }}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection