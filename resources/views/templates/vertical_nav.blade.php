<nav class="navbar-default navbar-static-side" role="navigation">
	<div class="sidebar-collapse">
		<ul class="nav metismenu" id="side-menu">
			<li class="app-name">
				<a href="/app" class="text-center" style="margin-left: -10px; color: #ffffff; font-size: 18px;">
					APP
				</a>
			</li>
			<li class="nav-header">
				<div class="dropdown profile-element">
					@if (Session::get('avatar'))
						<span class="user-avatar">
							<img alt="image" class="img-circle" src="{{ Session::get('avatar') }}" title="{{ Session::get('user') }}" />
						</span>
					@else
						<span class="default-avatar">
							<i class="fa fa-user fa-lg"></i>
						</span>
					@endif
					<a data-toggle="dropdown" class="dropdown-toggle" href="#">
						<span class="clear">
							<span class="block m-t-xs">
								<strong class="font-bold">{{ Session::get('user') }}</strong>
							</span>
							<span class="text-muted text-xs block">
								{{ Session::get('role') }} <b class="caret"></b>
							</span>
						</span>
					</a>
					<ul class="dropdown-menu m-t-xs">
						<li>
							<a href="/form/user/{{ Session::get('login_id') }}">
								<i class="fa fa-user"></i> Profile
							</a>
						</li>
						@if (Session::has('role') && Session::get('role') == "Administrator")
							<li>
								<a href="/app/settings">
									<i class="fa fa-cogs"></i> Settings
								</a>
							</li>
						@endif
						<li class="divider"></li>
						<li>
							<a href="/logout">
								<i class="fa fa-power-off"></i> Logout
							</a>
						</li>
					</ul>
				</div>
				<div class="logo-element">
					App
				</div>
			</li>
			<li title="Modules">
				<a href="/app/modules">
					<i class="fa fa-diamond fa-lg"></i>
					<span class="nav-label">Modules</span>
				</a>
			</li>
			<li title="Reports">
				<a href="/app/reports">
					<i class="fa fa-sitemap fa-lg"></i>
					<span class="nav-label">Reports</span>
				</a>
			</li>
			@if (Session::has('role') && Session::get('role') == "Administrator")
				<li title="Settings">
					<a href="/app/settings">
						<i class="fa fa-cogs fa-lg"></i>
						<span class="nav-label">Settings</span>
					</a>
				</li>
			@endif
		</ul>
	</div>
</nav>