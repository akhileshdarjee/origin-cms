<header class="main-header">
	<!-- Logo -->
	<a href="{{ url('/app') }}" class="logo">
		<!-- mini logo for sidebar mini 50x50 pixels -->
		<span class="logo-mini"><b>{{ env('BRAND_ABBR', 'OC') }}</b></span>
		<!-- logo for regular state and mobile devices -->
		<span class="logo-lg"><b>{{ env('BRAND_NAME', 'Origin CMS') }}</b></span>
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
						@if (Session::get('avatar'))
							<img alt="{{ Session::get('user') }}" class="user-image" src="{{ url(Session::get('avatar')) }}" title="{{ Session::get('user') }}" />
						@else
							<img alt="{{ Session::get('user') }}" class="user-image" src="" title="{{ Session::get('user') }}" />
						@endif
						<span class="hidden-xs">{{ Session::get('user') }}</span>
					</a>
					<ul class="dropdown-menu">
						<!-- User image -->
						<li class="user-header">
							@if (Session::get('avatar'))
								<img alt="{{ Session::get('user') }}" class="img-circle" src="{{ url(Session::get('avatar')) }}" title="{{ Session::get('user') }}" />
							@else
								<img alt="{{ Session::get('user') }}" class="img-circle" src="" title="{{ Session::get('user') }}" />
							@endif
							<p>
								{{ Session::get('user') }} - {{ Session::get('role') }}
							</p>
						</li>
						<!-- Menu Footer-->
						<li class="user-footer">
							<div class="row">
								<div class="col-md-4">
									<a href="{{ url('/form/user') }}/{{ Session::get('user_id') }}" class="btn btn-default">Profile</a>
								</div>
								<div class="col-md-4">
									<a href="{{ url('/app/settings') }}" class="btn btn-default">Settings</a>
								</div>
								<div class="col-md-4">
									<a href="{{ url('/logout') }}" class="btn btn-default">Sign out</a>
								</div>
							</div>
						</li>
					</ul>
				</li>
				<li>
					<a href="{{ url('/app/settings') }}">
						<i class="fa fa-gears"></i>
					</a>
				</li>
			</ul>
		</div>
	</nav>
</header>