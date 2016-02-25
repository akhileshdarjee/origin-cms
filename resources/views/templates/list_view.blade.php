<!DOCTYPE html>
<html lang="en">
	<head>
		<title>{{ ucwords($title) }} List</title>
		@include('templates.headers')
	</head>
	<body class="navbar-fixed">
		@include('templates.navbar')
		@include('templates.vertical_nav')
		<section id="content" class="content-sidebar bg-white">
			<section class="main">
				<div class="row">
					<div class="col-lg-12">
						<section class="bg-white">
							<header class="panel-heading">
								<div class="row">
									<div class="col-md-6">
										<div class="h4">
											<span><i class="fa fa-list"></i> {{ ucwords($title) }} List</span>
										</div>
									</div>
									<div class="col-md-6 col-md-push-5">
										<div style="line-height: 39px;">
											<button class="btn btn-primary btn-sm" id="action-button" data-action="new"
												data-toggle="tooltip" data-placement="left" data-container="body" title="New {{ ucwords($title) }}">New
											</button>
										</div>
									</div>
								</div>
							</header>
							<div class="panel-body">
								<div class="row text-small">
									<div class="col-sm-4" style="line-height:30px;">
										<strong>Total {{ ucwords($title) }}(s)</strong> : <span class="badge bg-primary" id="row-count">{{ $count }}</span>
									</div>
									<div class="col-sm-4 col-md-push-4">
										<div class="input-group">
											@var $item = str_replace("Id", "ID", awesome_case($search_via))
											<input type="text" class="input-sm form-control autocomplete" id="search_text" 
												data-target-field="{{ $search_via }}" data-target-module="{{ $module }}" placeholder="Search {{ $item }}" autocomplete="off">
											<span class="input-group-btn">
												<button class="btn btn-sm btn-primary" id="search" type="button">
													<i class="fa fa-search"></i>
												</button>
											</span>
										</div>
									</div>
								</div>
							</div>
							<div class="table-responsive b-t">
								<table class="table list-view" data-module="{{ $module }}">
									<thead class="panel-heading text-small">
										<tr>
											<th width="20" data-field-name="row_check">
												<input type="checkbox" id="check-all">
											</th>
											@foreach ($columns as $column)
												<th name="{{ $column }}" id ="{{ $column }}">{{ $column }}</th>
											@endforeach
										</tr>
									</thead>
									<tbody>
										@if (count($rows) > 0)
											@var $counter = 1
											@foreach ($rows as $row)
												<tr class="clickable_row" data-href="/form/{{ snake_case($module) }}/{{ $row->$link_field }}">
													<td data-field-name="row_check"><input type="checkbox" name="post[]" value="{{ $counter += 1 }}"></td>
													@foreach ($columns as $column)
														@var $tooltip = str_replace("Id", "ID", awesome_case($column))
														<td data-field-name="{{ $column }}" data-toggle="tooltip" data-placement="bottom" data-container="body"
															title="{{ $tooltip }} : {{ $row->$column }}">{{ $row->$column }}</td>
													@endforeach
												</tr>
											@endforeach
										@endif
									</tbody>
								</table>
							</div>
							<footer class="panel-footer">
								<div class="row">
									<div class="col-sm-5 text-right text-center-sm pull-right">
										{!! $rows->render() !!}
									</div>
								</div>
							</footer>
						</section>
					</div>
				</div>
			</section>
			<aside class="sidebar bg-white text-small"></aside>
		</section>
		@include('templates.msgbox')
		@if (Session::has('msg'))
			<script type="text/javascript">
				msgbox("{{ Session::get('msg') }}");
			</script>
		@endif
		<script src="/js/web_app/list_view.js"></script>
	</body>
</html>