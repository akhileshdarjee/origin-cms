<header id="header" class="navbar">
	<ul class="nav navbar-nav navbar-avatar pull-right">
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">            
				<span class="hidden-xs-only">{{ Session::get('user') }}</span>
				@if (Session::get('avatar') != "")
					<span class="thumb-small avatar inline">
						<img src="{{ Session::get('avatar') }}" alt="{{ Session::get('user') }}" class="img-circle">
					</span>
				@else
					<span class="btn btn-default btn-circle btn-xs">
						<i class="fa fa-user fa-3x text-muted text-center"></i>
					</span>
				@endif
				<b class="caret hidden-xs-only"></b>
			</a>
			<ul class="dropdown-menu pull-right">
				<li>
					<a href="/form/user/{{ Session::get('login_id') }}">
						<i class="fa fa-user"></i> Profile
					</a>
				</li>
				<li class="divider"></li>
				<li>
					<a href="/logout">
						<i class="fa fa-power-off"></i> Logout
					</a>
				</li>
			</ul>
		</li>
	</ul>
	<a class="navbar-brand" href="/app" style="font-size: 22px;">Web App</a>
	<button type="button" class="btn btn-link pull-left nav-toggle visible-xs" data-toggle="class:slide-nav slide-nav-left" data-target="body">
		<i class="fa fa-bars fa-lg text-default"></i>
	</button>
	<ul class="nav navbar-nav hidden-xs">
	</ul>
</header>