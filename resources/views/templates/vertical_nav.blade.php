<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
		<!-- Sidebar user panel -->
		<div class="user-panel">
			<div class="pull-left image">
				<img alt="{{ Session::get('user') }}" class="img-circle profile_img" src="{{ Session::get('avatar') }}" title="{{ Session::get('user') }}" />
			</div>
			<div class="pull-left info">
				<p>{{ Session::get('user') }}</p>
				<a href="#"><i class="fa fa-circle text-success"></i> Online</a>
			</div>
		</div>
		<!-- sidebar menu: : style can be found in sidebar.less -->
		<ul class="sidebar-menu">
			<li class="header">MAIN NAVIGATION</li>
			<li class="treeview" title="Dashboard">
				<a href="/app/dashboard">
					<i class="fa fa-dashboard"></i>
					<span>Dashboard</span>
				</a>
			</li>
			<li class="treeview" title="Modules">
				<a href="/app/modules">
					<i class="fa fa-diamond"></i>
					<span>Modules</span>
				</a>
			</li>
			<li class="treeview" title="Reports">
				<a href="/app/reports">
					<i class="fa fa-sitemap fa-lg"></i>
					<span class="nav-label">Reports</span>
				</a>
			</li>
			@if (Session::get('role') == "Administrator")
				<li class="treeview" title="Activities">
					<a href="/app/activities">
						<i class="fa fa-bell fa-lg"></i>
						<span class="nav-label">Activities</span>
					</a>
				</li>
			@endif
			<li class="treeview" title="Settings">
				<a href="/app/settings">
					<i class="fa fa-cogs fa-lg"></i>
					<span class="nav-label">Settings</span>
				</a>
			</li>
		</ul>
	</section>
	<!-- /.sidebar -->
</aside>