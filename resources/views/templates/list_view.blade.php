@extends('app')

@section('title', awesome_case($title) . ' List - ' . env('BRAND_NAME', 'Origin CMS'))
@section('search', awesome_case($title) . ' List')

@section('breadcrumb')
	<section class="content-header">
		<h1>&nbsp;</h1>
		<ol class="breadcrumb app-breadcrumb">
			<li>
				<a href="{{ url('/app/modules') }}">Home</a>
			</li>
			<li class="active">
				<strong>{{ ucwords($title) }}</strong>
			</li>
		</ol>
	</section>
@endsection

@section('body')
	<div class="box">
		<div class="box-header with-border">
			<h3 class="box-title">
				<i class="fa fa-list"></i> {{ awesome_case($title) }} List
			</h3>
			<div class="box-tools">
				<ul class="no-margin pull-right">
					<button class="btn btn-primary btn-sm" id="action-button" data-action="new"
						data-toggle="tooltip" data-placement="left" data-container="body" title="New {{ ucwords($title) }}">New</button>
				</ul>
			</div>
		</div>
		<div class="box-header with-border">
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
		<!-- /.box-header -->
		<div class="box-body no-padding table-responsive">
			<table class="table table-hover list-view" data-module="{{ $module }}">
				<thead class="panel-heading text-small">
					<tr class="list-header">
						<th data-field-name="row_check" class="list-checkbox" valign="middle">
							<input type="checkbox" id="check-all">
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
							<tr class="clickable_row" data-href="{{ url('/form') }}/{{ snake_case($module) }}/{{ $row->$link_field }}">
								<td data-field-name="row_check" class="list-checkbox">
									<input type="checkbox" name="post[]" value="{{ $counter += 1 }}" id="check-{{ $counter }}">
								</td>
								@foreach ($columns as $column)
									@var $tooltip = str_replace("Id", "ID", awesome_case($column))
									@if (isset($record_identifier) && ($column == $link_field || $column == $record_identifier))
										<td data-field-name="{{ $column }}" class="link-field" data-toggle="tooltip" data-placement="bottom" data-container="body"
											title="{{ $tooltip }} : {{ $row->$column }}">
											<a href="{{ url('/form') }}/{{ $module }}/{{ $row->$link_field }}">{{ $row->$column }}</a><br />
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
		<!-- /.box-body -->
		<div class="box-footer clearfix">
			<div class="row">
				<div class="col-sm-12 text-right pull-right">
					{!! $rows->render() !!}
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script type="text/javascript" src="{{ url('/js/web_app/list_view.js') }}"></script>
@endpush