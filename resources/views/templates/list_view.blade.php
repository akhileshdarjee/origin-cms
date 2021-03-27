@extends('layouts.app')

@section('title', $module['display_name'] . ' List - ' . config('app.brand.name'))
@section('search', $module['display_name'] . ' List')

@section('breadcrumb')
    <ol class="breadcrumb app-breadcrumb">
        <li>
            <a href="{{ route('show.app.modules') }}"><strong>Home</strong></a>
        </li>
        <li class="active">
            {{ $module['display_name'] }}
        </li>
    </ol>
@endsection

@section('title_section')
    <div id="sticky-anchor"></div>
    <section class="content-header title-section" id="sticky">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-7">
                <div class="form-name">
                    <i class="fa fa-list"></i> {{ $module['display_name'] }} List
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-5 text-right">
                @if ($can_create)
                    <button class="btn btn-info btn-sm" id="import-from-csv" data-toggle="tooltip" 
                        data-placement="bottom" data-container="body" title="Import {{ $module['display_name'] }}" data-module="{{ $module['name'] }}">
                        <span class="hidden-xs">Import</span>
                        <span class="visible-xs"><i class="fa fa-upload"></i></span>
                    </button>
                @endif

                <button class="btn btn-success btn-sm refresh-list-view" data-toggle="tooltip" 
                    data-placement="bottom" data-container="body" title="Refresh">
                    <span class="hidden-xs">Refresh</span>
                    <span class="visible-xs"><i class="fa fa-refresh"></i></span>
                </button>

                @if ($can_create)
                    <a href="{{ route('new.doc', $module['slug']) }}" class="btn btn-primary btn-sm new-form"
                        data-toggle="tooltip" data-placement="bottom" data-container="body" title="New {{ $module['display_name'] }}">
                        <span class="hidden-xs">New</span>
                        <span class="visible-xs"><i class="fa fa-plus"></i></span>
                    </a>
                @endif

                @if ($can_delete)
                    <button class="btn btn-danger btn-sm delete-selected" style="display: none;"
                        data-toggle="tooltip" data-placement="bottom" data-container="body" title="Delete selected records">
                        <span class="hidden-xs">Delete</span>
                        <span class="visible-xs"><i class="fa fa-trash"></i></span>
                    </button>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('body')
    <div class="box n-m-b">
        <div class="box-header list-actions">
            <div class="row">
                <div class="col-md-9 col-sm-6 col-xs-6">
                    <button class="btn btn-sm" id="add-filter">Search</button>
                    <div class="list-active-filters" style="display: none;"></div>
                </div>
                <div class="col-md-3 col-sm-6 col-xs-6 text-right sorting-fields" data-action="{{ route('update.list.sorting') }}">
                    <div class="dropdown list-dropdown-field">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear">
                                <span class="text-muted text-xs block fw-600" id="sort-field" data-value="{{ $module['sort_field'] }}">
                                    {{ str_replace("Id", "ID", awesome_case($module['sort_field'])) }}
                                </span>
                            </span>
                        </a>
                        <ul class="dropdown-menu list-column-dropdown">
                            @foreach($table_columns as $column_name => $column_type)
                                @if (!in_array($column_name, ['avatar', 'password', 'remember_token']))
                                    <li>
                                        <a class="sort-list-by-name" data-value="{{ $column_name }}">
                                            {{ str_replace("Id", "ID", awesome_case($column_name)) }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <button class="btn btn-sm" id="sort-list-order" data-value="{{ $module['sort_order'] }}">
                        @if ($module['sort_order'] == "asc")
                            <i class="fa fa-arrow-up"></i>
                        @else
                            <i class="fa fa-arrow-down"></i>
                        @endif
                    </button>
                </div>
            </div>
        </div>
        <div class="box-header brd-top list-column-filters" data-filter-no="1" style="display: none;">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control" name="column_name">
                            @foreach($table_columns as $column_name => $column_type)
                                @if (!in_array($column_name, ['avatar', 'password', 'remember_token']))
                                    <option value="{{ $column_name }}" data-type="{{ $column_type }}">
                                        {{ str_replace("Id", "ID", awesome_case($column_name)) }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control" name="column_operator">
                            <option value="=">Equals</option>
                            <option value="!=">Not Equals</option>
                            <option value="like">Like</option>
                            <option value="in">In</option>
                            <option value="notin">Not In</option>
                            <option value=">">></option>
                            <option value="<"><</option>
                            <option value=">=">>=</option>
                            <option value="<="><=</option>
                            <option value="between">Between</option>
                            <option value="notbetween">Not Between</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group column-value-container">
                        <input type="text" name="column_value" class="form-control" autocomplete="off">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <button class="btn btn-success btn-sm apply-column-filters">
                            Apply
                        </button>
                        <button class="btn btn-danger btn-sm remove-column-filters">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body no-padding table-responsive list-content">
            <div class="record-selected-count" style="display: none;"></div>
            <table class="table table-hover list-view" data-module="{{ $module['name'] }}">
                <thead>
                    <tr class="list-header">
                        @if ($can_delete)
                            <th width="10%" data-field-name="row_check" class="list-checkbox" valign="middle">
                                <input type="checkbox" id="check-all">
                            </th>
                        @else
                            <th class="text-center" valign="middle">#</th>
                        @endif
                        @foreach ($columns as $column)
                            <th name="{{ $column }}" valign="middle">
                                {{ awesome_case($column) }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="list-view-items"></tbody>
            </table>
        </div>
        <div class="box-footer clearfix list-actions">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-5">
                    Page :
                    <strong><span class="list-page-no"></span></strong> • 
                    <span class="item-from"></span> -
                    <span class="item-to"></span> of 
                    <strong>
                        <span class="badge item-count"></span>
                    </strong>
                    records
                </div>
                <div class="col-md-6 col-sm-6 col-xs-7">
                    <div class="origin-pagination-content text-right"></div>
                </div>
            </div>
        </div>
        <div class="data-loader" style="display: none;">Loading...</div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset(mix('js/origin/list_view.js')) }}"></script>
@endpush
