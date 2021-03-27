<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                @if (auth()->user()->avatar)
                    <img alt="{{ auth()->user()->full_name }}" class="img-circle" src="{{ getImage(auth()->user()->avatar, 45, 45) }}" title="{{ auth()->user()->full_name }}" />
                @else
                    <span class="default-avatar">
                        <i class="fa fa-user fa-lg"></i>
                    </span>
                @endif
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->full_name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <div class="sidebar-form">
            <div class="input-group">
                <input type="text" name="top-search" id="top-search" class="form-control" placeholder="Search @yield('search')..." autocomplete="off">
                <span class="input-group-btn">
                    <button type="button" name="search" id="search-btn" class="btn btn-flat">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            <li class="treeview" title="Modules">
                <a href="{{ route('show.app.modules') }}">
                    <i class="fa fa-diamond"></i>
                    <span>Modules</span>
                </a>
            </li>
            <li class="treeview" title="Reports">
                <a href="{{ route('show.app.reports') }}">
                    <i class="fa fa-sitemap"></i>
                    <span>Reports</span>
                </a>
            </li>
            <li class="treeview" title="Activities">
                <a href="{{ route('show.app.activities') }}">
                    <i class="fa fa-bell"></i>
                    <span>Activities</span>
                </a>
            </li>
            @if (auth()->user()->role == "Administrator" && auth()->user()->username == "admin")
                <li class="treeview" title="Backups">
                    <a href="{{ route('show.app.backups') }}">
                        <i class="fa fa-hdd-o"></i>
                        <span>Backups</span>
                    </a>
                </li>
            @endif
            <li class="treeview" title="Settings">
                <a href="{{ route('show.app.settings') }}">
                    <i class="fa fa-cogs"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </section>
</aside>
