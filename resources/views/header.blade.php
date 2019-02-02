<img src="{{ fileUrl('/img/HeaderChrome.jpg') }}" alt="Chrome wheels" id="headerImg" class="img-thumbnail hidden-xs">
<div class="navbar navbar-toggleable-md navbar-light bg-faded navbar-expand-lg">
    <button class="navbar-toggler navbar-toggler-right"
            type="button"
            data-toggle="collapse"
            data-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-header hidden-xs hidden-sm">
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
            @if ( $isLoggedIn === true )
                <li class="nav-item navText"><a href="{{ route('logout') }}" class="nav-link">Logout</a></li>
            @else
                <li class="nav-item navText"><a href="{{ route('login') }}" class="nav-link">Login</a></li>
            @endif
            <li class="nav-item">
                <select id="nav_select_make" class="form-control">
                    <option value="">Make</option>
                    @foreach ($makenames as $makename)
                        @if ($makename === $makenameSession)
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
                            @if ($modelname === $modelnameSession)
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