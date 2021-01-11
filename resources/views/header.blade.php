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
        <form method="get" action="{{ route('search.view') }}" id="search-form"
              class="nav navbar-nav ml-auto navbar-right">
            <ul id="navmenuHeader" class="nav navbar-nav">
                @if (!is_null($menuHeader))
                    @foreach ($menuHeader as $page)
                        <li class="nav-item navText"><a href="/{{ $page->getName() === 'home' ? '' :
                    strtolower($page->getName()) }}" class="nav-link">{{ $page->getTitle() }}</a></li>
                    @endforeach
                @endif
                <li><a href="{{ route('login') }}"><i class="fa fa-user-o fa-lg"></i></a></li>
                <li class="nav-item">
                    <select id="navSelectMake" class="form-control">
                        <option value="">Make</option>
                        @foreach ($makeNames as $makeName)
                            @if (isset($makeNameRoute) && $makeName === $makeNameRoute)
                                <option value="{{ $makeName }}" selected>{{ $makeName }}</option>
                            @else
                                <option value="{{ $makeName }}">{{ $makeName }}</option>
                            @endif
                        @endforeach
                    </select>
                </li>
                <li class="nav-item">
                    <select class="form-control" id="navSelectModel">
                        <option value="">Model</option>
                        @if (isset($modelNames))
                            @foreach ($modelNames as $modelName)
                                @if (isset($modelNameRoute) && $modelName === $modelNameRoute)
                                    <option value="{{ $modelName }}" selected>{{ $modelName }}</option>
                                @else
                                    <option value="{{ $modelName }}">{{ $modelName }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </li>
                <li class="nav-item">
                    <input type="text" name="query" class="form-control"
                           id="searchFormText" placeholder="Search car...">
                </li>
                <li class="nav-item">
                    <input type="submit" class="btn btn-primary" value="Go" id="searchFormSubmit">
                </li>
            </ul>
        </form>
    </div>
</div>
