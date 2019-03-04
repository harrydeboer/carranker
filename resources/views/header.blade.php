<div id="headerImg" class="img-thumbnail"></div>
<div class="navbar navbar-toggleable-md navbar-light bg-faded navbar-expand-lg">
    <button class="navbar-toggler navbar-toggler-right"
            type="button"
            data-toggle="collapse"
            data-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-header d-none d-md-block">
        <img src="{{ fileUrl('/img/CarRanker.png') }}" alt="Car Ranker" id="carrankerLogo" class="img-thumbnail">
    </div>
    <div class="navbar-collapse collapse" id="navbarCollapse">
        {!! Form::model($navform, [
        'route' => ['base.search'],
        'method' => 'get',
        'class' => 'nav navbar-nav ml-auto navbar-right',
        'id' => 'search-form'
        ]) !!}
        <ul id="navmenuHeader" class="nav navbar-nav">
            @foreach ($menuHeader as $page)
                <li class="nav-item navText"><a href="/{{ $page->getName() === 'home' ? '' :
                    strtolower($page->getName()) }}" class="nav-link">{{ $page->getTitle() }}</a></li>
            @endforeach
            <li><a href="{{ route('auth') }}"><i class="fa fa-user fa-2x"></i></a></li>
            <li class="nav-item">
                <select id="nav_select_make" class="form-control">
                    <option value="">Make</option>
                    @foreach ($makenames as $makename)
                        @if (isset($makenameRoute) && $makename === $makenameRoute)
                            <option value="{{ $makename }}" selected>{{ $makename }}</option>
                        @else
                            <option value="{{ $makename }}">{{ $makename }}</option>
                        @endif
                    @endforeach
                </select>
            </li>
            <li class="nav-item">
                <select class="form-control" id="nav_select_model">
                    <option value="">Model</option>
                    @if (isset($modelnames))
                        @foreach ($modelnames as $modelname)
                            @if (isset($modelnameRoute) && $modelname === $modelnameRoute)
                                <option value="{{ $modelname }}" selected>{{ $modelname }}</option>
                            @else
                                <option value="{{ $modelname }}">{{ $modelname }}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </li>
            <li class="nav-item">{!! Form::text('query', null, ['class' => 'form-control', 'id' => 'search_form_text',
            'placeholder' => 'Search car...']) !!}</li>
            <li class="nav-item">{!! Form::submit('Go', ['class' => 'btn btn-primary',
            'id' => 'search_form_submit']) !!}</li>
        </ul>
        {!! Form::close() !!}
    </div>
</div>