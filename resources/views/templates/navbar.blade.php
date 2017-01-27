<header class="main-header">
	<!-- Logo -->
	<a href="/app" class="logo">
		<!-- mini logo for sidebar mini 50x50 pixels -->
		<span class="logo-mini"><b>O</b>C</span>
		<!-- logo for regular state and mobile devices -->
		<span class="logo-lg"><b>Origin</b>CMS</span>
	</a>
	<!-- Header Navbar: style can be found in header.less -->
	<nav class="navbar navbar-static-top">
		<!-- Sidebar toggle button-->
		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
			<span class="sr-only">Toggle navigation</span>
		</a>
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<!-- User Account: style can be found in dropdown.less -->
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<img alt="{{ Session::get('user') }}" class="user-image" src="{{ Session::get('avatar') }}" title="{{ Session::get('user') }}" />
						<span class="hidden-xs">{{ Session::get('user') }}</span>
					</a>
					<ul class="dropdown-menu">
						<!-- User image -->
						<li class="user-header">
							<img alt="{{ Session::get('user') }}" class="img-circle" src="{{ Session::get('avatar') }}" title="{{ Session::get('user') }}" />
							<p>
								{{ Session::get('user') }} - {{ Session::get('role') }}
							</p>
						</li>
						<!-- Menu Footer-->
						<li class="user-footer">
							<div class="row">
								<div class="col-md-4">
									<a href="/form/user/{{ Session::get('user_id') }}" class="btn btn-default btn-flat">Profile</a>
								</div>
								<div class="col-md-4">
									<a href="/app/settings" class="btn btn-default btn-flat">Settings</a>
								</div>
								<div class="col-md-4">
									<a href="/logout" class="btn btn-default btn-flat">Sign out</a>
								</div>
							</div>
						</li>
					</ul>
				</li>
				<li>
					<a href="/app/settings">
						<i class="fa fa-gears"></i>
					</a>
				</li>
			</ul>
		</div>
	</nav>
</header>