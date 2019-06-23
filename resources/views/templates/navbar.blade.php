<header class="main-header">
    <a href="{{ route('home') }}" class="logo">
        <span class="logo-mini"><b>{{ config('app.brand.abbr') }}</b></span>
        <span class="logo-lg">
            <b style="font-weight: 400;">{{ config('app.brand.name') }}</b>
        </span>
    </a>
    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        @yield('breadcrumb')
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        @if (auth()->user()->avatar)
                            <img alt="{{ auth()->user()->full_name }}" class="user-image" src="{{ getImage(auth()->user()->avatar, 25, 25) }}" title="{{ auth()->user()->full_name }}" />
                        @else
                            <img class="user-image default" />
                        @endif
                        <span class="hidden-xs">{{ auth()->user()->full_name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            @if (auth()->user()->avatar)
                                <img alt="{{ auth()->user()->full_name }}" class="img-circle" src="{{ getImage(auth()->user()->avatar, 90, 90) }}" title="{{ auth()->user()->full_name }}" />
                            @else
                                <img class="img-circle default" />
                            @endif
                            <p>
                                {{ auth()->user()->full_name }} - {{ auth()->user()->role }}
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <a href="{{ route('show.doc', ['slug' => 'user', 'id' => auth()->user()->id]) }}" class="btn btn-default">
                                        Profile
                                    </a>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <a href="{{ route('show.app.settings') }}" class="btn btn-default">
                                        Settings
                                    </a>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <a href="{{ route('logout') }}" class="btn btn-default">
                                        Logout
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
