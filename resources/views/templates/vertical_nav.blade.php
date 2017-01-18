<div class="col-md-3 left_col">
	<div class="left_col scroll-view">
		<div class="navbar nav_title" style="border: 0;">
			<a href="/app" class="site_title">
				<i class="fa fa-eye"></i> <span>Web App</span>
			</a>
		</div>
		<div class="clearfix"></div>
		<!-- menu profile quick info -->
		<div class="profile">
			<div class="profile_pic">
				@if (Session::get('avatar'))
					<img alt="{{ Session::get('user') }}" class="img-circle profile_img" src="{{ Session::get('avatar') }}" title="{{ Session::get('user') }}" />
				@else
					<span class="default-avatar">
						<i class="fa fa-user fa-lg"></i>
					</span>
				@endif
			</div>
			<div class="profile_info">
				<span>Welcome,</span>
				<h2>{{ Session::get('user') }}</h2>
			</div>
		</div>
		<!-- /menu profile quick info -->
		<br />
		<!-- sidebar menu -->
		<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
			<div class="menu_section">
				<h3>&nbsp;</h3>
				<ul class="nav side-menu">
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
					@if (Session::get('role') == "Administrator")
						<li title="Activities">
							<a href="/app/activities">
								<i class="fa fa-bell fa-lg"></i>
								<span class="nav-label">Activities</span>
							</a>
						</li>
					@endif
					<li title="Settings">
						<a href="/app/settings">
							<i class="fa fa-cogs fa-lg"></i>
							<span class="nav-label">Settings</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<!-- /sidebar menu -->
		<!-- /menu footer buttons -->
		<div class="sidebar-footer hidden-small">
			<a data-toggle="tooltip" data-placement="top" title="Settings">
				<span class="fa fa-cogs" aria-hidden="true"></span>
			</a>
			<a data-toggle="tooltip" data-placement="top" title="FullScreen">
				<span class="fa fa-arrows-alt" aria-hidden="true"></span>
			</a>
			<a data-toggle="tooltip" data-placement="top" title="Lock">
				<span class="fa fa-lock" aria-hidden="true"></span>
			</a>
			<a data-toggle="tooltip" data-placement="top" title="Logout">
				<span class="fa fa-sign-out" aria-hidden="true"></span>
			</a>
		</div>
		<!-- /menu footer buttons -->
	</div>
</div>