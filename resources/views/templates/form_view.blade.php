<!DOCTYPE html>
<html lang="en">
	<head>
		<title>{{ ucwords($title) }} - Web App</title>
		@include('templates.headers')
		<script type="text/javascript">
			window.doc = {
				data: <?php echo isset($form_data) ? json_encode($form_data) : "false" ?>,
				title: "{{ $title }}",
				module: "{{ $module }}",
				changed: false
			};
		</script>
	</head>
	<body class="nav-md">
		<div class="container body">
			<div class="main_container">
				@include('templates.vertical_nav')
				@include('templates.navbar')
				<!-- page content -->
				<div class="right_col" role="main">
					<div class="">
						<div class="clearfix"></div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<div class="x_panel">
									<div class="x_title floatbox-title">
										<div class="form-name">
											@if (isset($module_type) && $module_type == "Single")
												<i class="{{ $icon }}"></i> {{ $title }}
											@else
												<i class="{{ $icon }}"></i> {{ isset($form_data[$table_name]['id']) ? $form_data[$table_name][$record_identifier] : "New $title" }}
											@endif
										</div>
										@if (isset($form_data[$table_name]['id']))
											<div class="form-status non-printable">
												<small>
													<span class="text-center" id="form-stats">
														<i class="fa fa-circle text-success"></i>
														<span id="form-status">Saved</span>
													</span>
												</small>
											</div>
											<div class="panel_toolbox non-printable">
												<!-- Form action buttons -->
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-success dropdown-toggle">
														File <span class="caret"></span>
													</button>
													<ul class="dropdown-menu dropdown-left">
														<li>
															<a href="/form/{{ snake_case($module) }}/draft/{{ $form_data[$table_name][$link_field] }}">
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
									<div class="x_content">
										@if (isset($module_type) && $module_type == "Single")
											@include($file)
										@else
											@var $action = "/form/" . snake_case($module)
											<form method="POST" action="{{ isset($form_data[$table_name]['id']) ? $action."/".$form_data[$table_name][$link_field] : $action }}" name="{{ snake_case($module) }}" id="{{ snake_case($module) }}" class="form-horizontal" enctype="multipart/form-data">
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
									<div class="x_content hidden-print">
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
				</div>
				<!-- /page content -->
				@include('templates.footer')
				<script type="text/javascript" src="/js/web_app/form.js"></script>
				<script type="text/javascript" src="/js/web_app/table.js"></script>
				@if (File::exists(public_path('/js/web_app/' . snake_case($module) . '.js')))
					<!-- Include client js file -->
					<script type="text/javascript" src="/js/web_app/{{ snake_case($module) }}.js"></script>
				@endif
			</div>
		</div>
	</body>
</html>