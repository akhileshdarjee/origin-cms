@extends('app')

@section('title', ucwords($title) . ' - ' . env('BRAND_NAME', 'Origin CMS'))
@section('search', ucwords($title))

@push('styles')
	<link type="text/css" rel="stylesheet" href="{{ url(elixir('css/origin/app-report.css')) }}">
@endpush

@section('body')
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">
				<i class="fa fa-list"></i> {{ ucwords($title) }}
			</h3>
			<div class="box-tools">
				<ul class="no-margin pull-right">
					<a class="btn btn-default btn-sm" id="download_report" name="download_report" 
						data-toggle="tooltip" data-placement="bottom" data-container="body" title="Download Report in Excel format">
						<i class="fa fa-download"></i> Download
					</a>
					@if (count($filters))
						<a class="btn btn-primary btn-sm" id="refresh_report" name="refresh_report"
							data-toggle="tooltip" data-placement="bottom" data-container="body" title="Filter Report">
							<i class="fa fa-filter"></i> Filter
						</a>
					@endif
				</ul>
			</div>
		</div>
		@if (count($filters))
			<div class="box-header with-border">
				{!! $filters !!}
			</div>
		@endif
		<!-- /.box-header -->
		<div class="box-body table-responsive">
			<table class="table table-bordered" id="report-table" data-report-name="{{ $title }}">
				<thead class="panel-heading text-small">
					<tr>
						<th>#</th>
						@if (isset($columns) && $columns)
							@foreach ($columns as $column)
								@var $col_head = str_replace("Id", "ID", awesome_case($column))
								<th name="{{ $column }}">{{ $col_head }}</th>
							@endforeach
						@endif
					</tr>
				</thead>
				<tbody>
					@if (isset($rows) && $rows && (count($rows) > 0))
						@var $counter = 0
						@foreach ($rows as $row)
							<tr>
								<td>{{ $counter += 1 }}</td>
								@foreach ($columns as $column)
									<td data-field-name="{{ $column }}" data-toggle="tooltip" data-placement="bottom" data-container="body"
										title="{{ (isset($row->$column) && $row->$column) ? $row->$column : "" }}">
										@if (isset($module) && $module
											&& isset($link_field) && $link_field
											&& isset($record_identifier) && $record_identifier
											&& $column == $record_identifier)
												<a href="{{ url('/form') }}/{{ $module }}/{{ $row->$link_field }}">{{ (isset($row->$column) && $row->$column) ? $row->$column : "" }}</a>
										@elseif (filter_var($row->$column, FILTER_VALIDATE_URL))
											<a href="{{ $row->$column }}" target="_blank">{{ $row->$column }}</a>
										@else
											{{ (isset($row->$column) && $row->$column) ? $row->$column : "" }}
										@endif
									</td>
								@endforeach
							</tr>
						@endforeach
					@endif
				</tbody>
			</table>
		</div>
		<!-- /.box-body -->
		<div class="box-footer clearfix">
			<strong>
				Total : <span class="badge" id="item-count">{{ $count }}</span>
				{{ (isset($module) && $module) ? awesome_case($module) : 'item' }}(s)
			</strong>
		</div>
	</div>
@endsection

@push('scripts')
	<script type="text/javascript" src="{{ url(elixir('js/origin/app-report.js')) }}"></script>
@endpush