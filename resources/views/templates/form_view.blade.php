<!DOCTYPE html>
<html lang="en">
	<head>
		<title>{{ ucwords($title) }} - Web App</title>
		<script type="text/javascript">
			window.doc = {
				data: <?php echo isset($form_data) ? json_encode($form_data) : "false" ?>,
				title: "{{ $title }}",
				module: "{{ $module }}",
				changed: false
			};
		</script>
		@include('templates.headers')
	</head>
	<body class="fixed-sidebar">
		<div id="wrapper">
			@include('templates.vertical_nav')
			<div id="page-wrapper" class="gray-bg printable">
				@include('templates.navbar', ['title' => isset($form_data['tab'.$module]['id']) ? $form_data['tab'.$module][$record_identifier] : $title])
				@if (!isset($module_type))
					<div class="row wrapper border-bottom white-bg page-heading app-breadcrumb non-printable">
						<div class="col-sm-10">
							<ol class="breadcrumb">
								<li>
									<a href="/app">Home</a>
								</li>
								<li>
									<a href="/list/{{ snake_case($module) }}">{{ $title }}</a>
								</li>
								<li class="active">
									<strong>{{ isset($form_data['tab'.$module]['id']) ? $form_data['tab'.$module][$record_identifier] : "New $title" }}</strong>
								</li>
							</ol>
						</div>
					</div>
				@endif
				<div class="wrapper wrapper-content">
					<div class="row">
						<div class="col-sm-12">
							<div class="ibox float-e-margins">
								<div class="ibox-title floatbox-title">
									<div class="form-name">
										@if (isset($module_type) && $module_type == "Single")
											<i class="{{ $icon }}"></i> {{ $title }}
										@else
											<i class="{{ $icon }}"></i> {{ isset($form_data['tab'.$module]['id']) ? $form_data['tab'.$module][$record_identifier] : "New $title" }}
										@endif
									</div>
									@if (isset($form_data['tab'.$module]['id']))
										<div class="form-status non-printable">
											<small>
												<span class="text-center" id="form-stats">
													<i class="fa fa-circle text-success"></i>
													<span id="form-status">Saved</span>
												</span>
											</small>
										</div>
										<div class="ibox-tools non-printable">
											<!-- Form action buttons -->
											<div class="btn-group">
												<button data-toggle="dropdown" class="btn btn-success dropdown-toggle">
													File <span class="caret"></span>
												</button>
												<ul class="dropdown-menu dropdown-left">
													<li>
														<a href="/form/{{ snake_case($module) }}/draft/{{ $form_data['tab'.$module][$link_field] }}">
															Duplicate
														</a>
													</li>
													<li>
														<a href="#" id="delete" name="delete">
															Delete
														</a>
													</li>
													<li>
														<a href="#" id="print-page">
															Print
														</a>
													</li>
													<li class="divider"></li>
													<li>
														<a href="/form/{{ snake_case($module) }}">
															New {{ $title }}
														</a>
													</li>
												</ul>
											</div>
										</div>
									@endif
								</div>
								<div class="ibox-content">
									@if (isset($module_type) && $module_type == "Single")
										@include($file)
									@else
										@var $action = "/form/" . snake_case($module)
										<form method="POST" action="{{ isset($form_data['tab'.$module]['id']) ? $action."/".$form_data['tab'.$module][$link_field] : $action }}" name="{{ snake_case($module) }}" id="{{ snake_case($module) }}" class="form-horizontal" enctype="multipart/form-data">
											{!! csrf_field() !!}
											<input type="hidden" name="id" id="id" class="form-control" data-mandatory="no" autocomplete="off" readonly>
											@if (view()->exists(str_replace('.', '/', $file)))
												@include($file)
											@else
												Please create '{{ str_replace('.', '/', $file) }}.blade.php' in views
											@endif
										</form>
									@endif
								</div>
								<div class="ibox-content non-printable">
									<div class="row">
										<div class="col-md-3">&nbsp;</div>
										<div class="col-md-8">
											<button type="reset" class="btn btn-white" id="reset_form">Reset</button>
											<button type="submit" class="btn btn-success disabled" id="save_form">
												<i class="fa fa-save"></i> Save Changes
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="footer non-printable">
					<div>
						<span class="pull-left">
							<strong>Copyright</strong> Achieveee &copy; 2011-{{ date('Y') }}
						</span>
						<span class="pull-right">
							Made with <i class="fa fa-heart fa-lg" style="color: #d90429;"></i> by 
							<strong>
								<a href="http://www.achieveee.com/" target="_blank" style="color: #676a6c;">Achieveee</a>
							</strong>
						</span>
					</div>
				</div>
			</div>
		</div>
		<a href="#" class="back-to-top">
			<i class="fa fa-chevron-up"></i>
		</a>
		@include('templates.msgbox')
		@if (Session::has('msg'))
			<script type="text/javascript">
				msgbox("{{ Session::get('msg') }}");
			</script>
		@endif
		<script type="text/javascript" src="/js/web_app/form.js"></script>
		<script type="text/javascript" src="/js/web_app/table.js"></script>
		@if (File::exists(public_path('/js/web_app/' . snake_case($module) . '.js')))
			<!-- Include client js file -->
			<script type="text/javascript" src="/js/web_app/{{ snake_case($module) }}.js"></script>
		@endif
	</body>
</html>