<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- Sidebar user panel -->
		<div class="user-panel">
			<div class="pull-left image">
				@if (Session::get('avatar'))
					<img alt="{{ Session::get('user') }}" class="img-circle" src="{{ url(Session::get('avatar')) }}" title="{{ Session::get('user') }}" />
				@else
					<span class="default-avatar">
						<i class="fa fa-user fa-lg"></i>
					</span>
				@endif
			</div>
			<div class="pull-left info">
				<p>{{ Session::get('user') }}</p>
				<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
			</div>
		</div>
		<!-- Search form -->
		<div class="sidebar-form">
			<div class="input-group">
				<input type="text" name="top-search" id="top-search" class="form-control" placeholder="Search @yield('search')..." autocomplete="off">
				<span class="input-group-btn">
					<button type="button" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
					</button>
				</span>
			</div>
		</div>
		<!-- sidebar menu: : style can be found in sidebar.less -->
		<ul class="sidebar-menu">
			<li class="header">MAIN NAVIGATION</li>
			<li class="treeview" title="Modules">
				<a href="{{ url('/app/modules') }}">
					<i class="fa fa-diamond"></i>
					<span>Modules</span>
				</a>
			</li>
			<li class="treeview" title="Reports">
				<a href="{{ url('/app/reports') }}">
					<i class="fa fa-sitemap"></i>
					<span>Reports</span>
				</a>
			</li>
			@if (Session::get('role') == "Administrator")
				<li class="treeview" title="Activities">
					<a href="{{ url('/app/activities') }}">
						<i class="fa fa-bell"></i>
						<span>Activities</span>
					</a>
				</li>
			@endif
			<li class="treeview" title="Settings">
				<a href="{{ url('/app/settings') }}">
					<i class="fa fa-cogs"></i>
					<span>Settings</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- /.sidebar -->
</aside>