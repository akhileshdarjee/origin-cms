<nav id="nav" class="nav-primary hidden-xs nav-vertical">
	<ul class="nav" data-spy="affix" data-offset-top="50">
		<li>
			<a href="/app/modules" data-toggle="tooltip" data-placement="right" title="Check to see all Module(s)">
				<i class="fa fa-th fa-lg"></i><span>Modules</span>
			</a>
		</li>
		<li>
			<a href="/app/reports" data-toggle="tooltip" data-placement="right" title="App Reports">
				<i class="fa fa-list fa-lg"></i><span>Reports</span>
			</a>
		</li>
		@if (Session::has('role') && Session::get('role') == "Administrator")
			<li>
				<a href="/app/settings" data-toggle="tooltip" data-placement="right" title="App Settings">
					<i class="fa fa-cogs fa-lg"></i><span>Settings</span>
				</a>
			</li>
		@endif
	</ul>
</nav>