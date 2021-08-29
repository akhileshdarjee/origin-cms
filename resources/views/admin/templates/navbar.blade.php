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
                    <input type="text" name="top-search" id="top-search" class="form-control form-control-sm form-control-navbar autocomplete" data-ac-module="Universe" data-ac-field="label" placeholder="{{ __('Search') }}" aria-label="Search" autocomplete="off">
                </div>
            </div>
        </form>
        <ul class="order-3 navbar-nav navbar-no-expand">
            @inject('activities', 'App\Http\Controllers\ActivityController')
            @var $latest_activities = $activities->getLatestActivities(5)
            <li class="nav-item dropdown notifications-menu">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-bell"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right">
                    @foreach($latest_activities as $idx => $act)
                        <a href="#" class="dropdown-item">
                            <i class="{{ $act['icon'] }} mr-1 activity-icon"></i>
                            {{ $act['description'] }}
                            <span class="float-right text-muted activity-time">
                                <i class="far fa-clock"></i> {{ $act['time_diff'] }}
                            </span>
                        </a>
                        <div class="dropdown-divider"></div>
                    @endforeach
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
