@if (session('app_settings') && isset(session('app_settings')['theme']) && session('app_settings')['theme'] == 'dark')
    <nav class="main-header navbar navbar-expand-md navbar-dark">
@else
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
@endif
    <div class="container">
        <a href="{{ route('home') }}" class="navbar-brand order-1">
            @if (file_exists('img/logo.svg'))
                <img src="{{ asset('img/logo.svg') }}" alt="{{ config('app.brand.name') }}" class="brand-image img-circle elevation-3" style="opacity: .8">
            @endif
            <span class="brand-text font-weight-light d-none d-sm-none d-md-inline-block">{{ config('app.brand.name') }}</span>
        </a>
        <div class="collapse navbar-collapse order-5 order-md-2" id="navbarCollapse">
            <ul class="navbar-nav">
                <li class="nav-item app-nav text-center">
                    <a class="nav-link modules-link" href="{{ route('show.app.modules') }}" title="{{ __('Modules') }}">
                        <i class="fas fa-gem fa-sm"></i> {{ __('Modules') }}
                    </a>
                </li>
                <li class="nav-item app-nav text-center">
                    <a class="nav-link" href="{{ route('show.app.reports') }}" title="{{ __('Reports') }}">
                        <i class="fas fa-sitemap fa-sm"></i> {{ __('Reports') }}
                    </a>
                </li>
                @if (auth()->user()->role == "Administrator" && auth()->user()->username == "admin")
                    <li class="nav-item app-nav text-center">
                        <a class="nav-link" href="{{ route('show.app.backups') }}" title="{{ __('Backups') }}">
                            <i class="fas fa-hdd fa-sm"></i> {{ __('Backups') }}
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <form class="form-inline order-2 order-md-3 univeral-search-form">
            <div class="form-group mb-0">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text pr-0" style="padding-right: 2px !important;">
                            <i class="fas fa-search fa-sm"></i>
                        </span>
                    </div>
                    <input type="text" name="top-search" id="top-search" class="form-control form-control-sm form-control-navbar autocomplete" data-ac-module="Universe" data-ac-field="label" placeholder="{{ __('Search') }} (Ctrl + G)" aria-label="Search" autocomplete="off">
                </div>
            </div>
        </form>
        <ul class="order-3 navbar-nav navbar-no-expand">
            <li class="nav-item dropdown notifications-menu">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-bell"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right" id="app-notifications" data-action="{{ route('latest.notifications') }}">
                    <div class="row vertical-center no-notifications" style="display: none;">
                        <div class="col-sm-12">
                            <svg width="80" height="80" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
                                <g>
                                    <path fill="#3498db" d="M44.1855507,34.4227982c-0.0830002-0.0155983-5.8740005-1.1034966-7.5683022-1.4207993l-3.7343979-6.4892998 c-0.1816025-0.3153992-0.5186005-0.5106983-0.8828011-0.5106983c-0.5898991,0-0.5898991,0-2.8671989,3.9579983l-1.75,3.0419998 l-7.5703011,1.4207993c-0.4814987,0.0909004-0.8311005,0.5108032-0.8311005,1.0010033 c0,0.4042969,0.0010014,0.4130974,5.5235004,6.1259995l-0.9316998,7.3428001c-0.039999,0.3125,0.0674,0.625,0.2901001,0.847599 c0.4969997,0.5,0.5009003,0.4971008,4.8456993-1.4550018l3.2910004-1.4785995l6.9990005,3.142601 c0.1338005,0.0606003,0.2764015,0.089901,0.4169998,0.089901c0.2860985,0,0.5663986-0.1211014,0.7636986-0.3456993 c0.2988014-0.3389015,0.305603-0.3457031-0.684597-8.1436005l5.2372971-5.4179993 c0.2607002-0.2695007,0.3516006-0.6611023,0.2373009-1.0175018C44.8554497,34.7568016,44.5537491,34.4911995,44.1855507,34.4227982 z M37.6904488,40.4852982c-0.214798,0.222702-0.3163986,0.5303001-0.277298,0.8360023c0,0,0.4715996,3.7178001,0.7645988,6.027298 l-5.7607002-2.5868988c-0.2657013-0.1190987-0.5683994-0.1190987-0.8339996,0c0,0-3.5771999,1.6064987-5.7607994,2.5868988 l0.7646999-6.027298c0.0391006-0.3057022-0.0625-0.6133003-0.277401-0.8360023c0,0-2.6152-2.704998-4.2635994-4.4090996 l6.1728001-1.1591988c0.2929993-0.0546989,0.5468998-0.2354012,0.6952991-0.4931984c0,0,1.9170017-3.3320007,3.0860004-5.3623009 l3.0858994,5.3623009c0.1485023,0.2577972,0.402401,0.4384995,0.6953011,0.4931984 c3.0694008,0.5761986,4.9794998,0.9346008,6.1729012,1.1581993L37.6904488,40.4852982z"/>
                                    <path fill="#212529" d="M60.0002518,5.0243001h-6.8694V2.0204999c0-0.5752-0.4658012-1.0409999-1.0410004-1.0409999 c-0.5752029,0-1.0410004,0.4658-1.0410004,1.0409999v3.0038002H39.6338501V2.0204999 c0-0.5752-0.4659004-1.0409999-1.0410004-1.0409999c-0.5751991,0-1.0410995,0.4658-1.0410995,1.0409999v3.0038002H26.1367493 V2.0204999c0-0.5752-0.4657993-1.0409999-1.0409985-1.0409999c-0.575201,0-1.0410004,0.4658-1.0410004,1.0409999v3.0038002 H12.6396503V2.0204999c0-0.5752-0.4658003-1.0409999-1.0410004-1.0409999s-1.0410004,0.4658-1.0410004,1.0409999v3.0038002 H3.9997499c-2.2090998,0-4,1.7908001-4,3.9999995v49.9962006c0,2.2090988,1.7909,4,4,4h56.0005035c2.209198,0,4-1.7909012,4-4 V9.0242996C64.0002518,6.8151002,62.2094498,5.0243001,60.0002518,5.0243001z M3.9997499,7.0243001h6.5578995v2.9962001 c0,0.5752001,0.4658003,1.0409994,1.0410004,1.0409994s1.0410004-0.4657993,1.0410004-1.0409994V7.0243001h11.4151001v2.9962001 c0,0.5752001,0.4657993,1.0409994,1.0410004,1.0409994c0.5751991,0,1.0409985-0.4657993,1.0409985-1.0409994V7.0243001h11.4150009 v2.9962001c0,0.5752001,0.4659004,1.0409994,1.0410995,1.0409994c0.5750999,0,1.0410004-0.4657993,1.0410004-1.0409994V7.0243001 H51.048851v2.9962001c0,0.5752001,0.4657974,1.0409994,1.0410004,1.0409994c0.5751991,0,1.0410004-0.4657993,1.0410004-1.0409994 V7.0243001h6.8694c1.1027985,0,2,0.8971,2,1.9999995v6.9962997l-0.0002022-0.0000992h-60l-0.0003,0.0000992V9.0242996 C1.99975,7.9214001,2.8970499,7.0243001,3.9997499,7.0243001z M60.0002518,61.0205002H3.9997499 c-1.1027,0-1.9999999-0.8972015-1.9999999-2V18.0203991l0.0003,0.0001011h60l0.0002022-0.0001011v41.0000992 C62.0002518,60.1232986,61.1030502,61.0205002,60.0002518,61.0205002z"/>
                                </g>
                            </svg>
                            <div class="text-muted text-sm font-weight-normal mt-2">
                                {{ __('No new notifications') }}
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('show.app.activity') }}" class="dropdown-item dropdown-footer activity-link">
                        {{ __('See All Activity') }}
                        <i class="fas fa-angle-right"></i>
                    </a>
                </div>
            </li>
            <li class="nav-item dropdown user-menu">
                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
                    @if (auth()->user()->avatar)
                        <img class="user-image img-circle elevation-2" src="{{ getImage(auth()->user()->avatar, 50, 50) }}" alt="{{ auth()->user()->full_name }}" />
                    @else
                        <div class="avatar-initials avatar-initials-xs avatar-initials-circle elevation-1" data-name="{{ auth()->user()->full_name }}"></div>
                    @endif
                </a>
                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu dropdown-menu-right border-0 shadow">
                    <li>
                        <a href="{{ route('show.doc', ['slug' => 'user', 'id' => auth()->user()->id]) }}" class="dropdown-item profile-link">
                            {{ __('Profile') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('show.app.settings') }}" class="dropdown-item settings-link">
                            {{ __('Settings') }}
                        </a>
                    </li>
                    <li>
                        <a href="#" class="dropdown-item show-keyboard-shortcuts">
                            {{ __('Keyboard Shortcuts') }}
                        </a>
                    </li>
                    <li class="dropdown-divider"></li>
                    <li>
                        <a href="#" class="dropdown-item change-theme">
                            @if (session('app_settings') && isset(session('app_settings')['theme']) && session('app_settings')['theme'] == 'dark')
                                <input type="checkbox" id="toggle-app-theme" name="toggle_app_theme" class="switch-input" data-action="{{ route('change.theme') }}" checked>
                            @else
                                <input type="checkbox" id="toggle-app-theme" name="toggle_app_theme" class="switch-input" data-action="{{ route('change.theme') }}">
                            @endif
                            <label for="toggle-app-theme" class="switch-label">
                                {{ __('Dark Mode') }}: 
                                <span class="toggle--on">{{ __('On') }}</span>
                                <span class="toggle--off">{{ __('Off') }}</span>
                            </label>
                        </a>
                    </li>
                    <li class="dropdown-divider"></li>
                    <li>
                        <a href="{{ route('logout') }}" class="dropdown-item">
                            {{ __('Logout') }}
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        <button class="navbar-toggler order-4" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>
