<!DOCTYPE html>
<html lang="en">
	<head>
		<title>{{ ucwords($title) }} List - Web App</title>
		@include('templates.headers')
	</head>
	<body class="fixed-sidebar">
		<div id="wrapper">
			@include('templates.vertical_nav')
			<div id="page-wrapper" class="gray-bg">
				@include('templates.navbar')
				<div class="row wrapper border-bottom white-bg page-heading app-breadcrumb">
					<div class="col-sm-10">
						<ol class="breadcrumb">
							<li>
								<a href="/app">Home</a>
							</li>
							<li class="active">
								<strong>{{ ucwords($title) }}</strong>
							</li>
						</ol>
					</div>
				</div>
				<div class="wrapper wrapper-content animated fadeInRight">
					<div class="row">
						<div class="col-sm-12">
							<div class="ibox">
								<div class="ibox-title floatbox-title">
									<div class="form-name">
										<i class="fa fa-list"></i> {{ ucwords($title) }} List
									</div>
									<div class="ibox-tools">
										<button class="btn btn-primary btn-sm" id="action-button" data-action="new"
											data-toggle="tooltip" data-placement="left" data-container="body" title="New {{ ucwords($title) }}">New</button>
									</div>
								</div>
								<div class="ibox-content">
									<div class="m-b-lg">
										<div class="col-sm-3 input-group pull-right">
											@var $item = str_replace("Id", "ID", awesome_case($search_via))
											<input type="text" class="input-sm form-control autocomplete" id="search_text" 
												data-target-field="{{ $search_via }}" data-target-module="{{ $module }}" placeholder="Search {{ $item }}" autocomplete="off">
											<span class="input-group-btn">
												<button class="btn btn-sm btn-primary" id="search" type="button">
													<i class="fa fa-search"></i>
												</button>
											</span>
										</div>
										<strong>Total {{ ucwords($title) }}(s)</strong> : <span class="label label-primary" id="row-count">{{ $count }}</span>
									</div>
									<div class="table-responsive">
										<table class="table table-hover list-view" data-module="{{ $module }}">
											<thead class="panel-heading text-small">
												<tr class="list-header">
													<th data-field-name="row_check" class="list-checkbox" valign="middle">
														<div class="checkbox">
															<input type="checkbox" id="check-all">
															<label for="check-all"></label>
														</div>
													</th>
													@foreach ($columns as $column)
														<th name="{{ $column }}" id ="{{ $column }}" valign="middle">{{ $column }}</th>
													@endforeach
												</tr>
											</thead>
											<tbody>
												@if (count($rows) > 0)
													@var $counter = 1
													@foreach ($rows as $row)
														<tr class="clickable_row" data-href="/form/{{ snake_case($module) }}/{{ $row->$link_field }}">
															<td data-field-name="row_check" class="list-checkbox">
																<div class="checkbox">
																	<input type="checkbox" name="post[]" value="{{ $counter += 1 }}">
																	<label for="check-all"></label>
																</div>
															</td>
															@foreach ($columns as $column)
																@var $tooltip = str_replace("Id", "ID", awesome_case($column))
																@if (isset($record_identifier) && ($column == $link_field || $column == $record_identifier))
																	<td data-field-name="{{ $column }}" class="link-field" data-toggle="tooltip" data-placement="bottom" data-container="body"
																		title="{{ $tooltip }} : {{ $row->$column }}">
																		<a href="/form/{{ $module }}/{{ $row->$link_field }}">{{ $row->$column }}</a><br />
																	</td>
																@else
																	<td data-field-name="{{ $column }}" data-toggle="tooltip" data-placement="bottom" data-container="body"
																		title="{{ $tooltip }} : {{ $row->$column }}">{{ $row->$column }}</td>
																@endif
															@endforeach
														</tr>
													@endforeach
												@endif
											</tbody>
										</table>
									</div>
								</div>
								<div class="ibox-content">
									<div class="row">
										<div class="col-sm-5 text-right pull-right">
											{!! $rows->render() !!}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="footer">
					<div>
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
		<script type="text/javascript" src="/js/web_app/list_view.js"></script>
	</body>
</html>