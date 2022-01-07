@extends('admin')

@section('title', __($module['display_name']) . ' ' . __('List') . ' - ' . config('app.brand.name'))

@section('title_section')
    <div id="sticky-anchor"></div>
    <div class="content-header" id="sticky">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-7 content-title">
                    <h1 class="m-0">
                        <small class="content-title-label">
                            <i class="{{ $module['icon'] }}"></i>
                            {{ __($module['display_name']) }} {{ __('List') }}
                        </small>
                    </h1>
                </div>
                <div class="col-sm-4 col-5 text-right list-btns">
                    @if ($can_create)
                        <button class="btn btn-default btn-sm elevation-2" id="import-from-csv" data-toggle="tooltip" data-placement="bottom" data-container="body" title="{{ __('Import') }} {{ __($module['display_name']) }}" data-module="{{ $module['name'] }}">
                            <span class="d-none d-sm-none d-md-inline-block">{{ __('Import') }}</span>
                            <span class="d-md-none d-lg-none d-xl-none"><i class="fas fa-upload"></i></span>
                        </button>
                    @endif

                    <button class="btn btn-default btn-sm elevation-2 refresh-list-view" data-toggle="tooltip" data-placement="bottom" title="{{ __('Refresh records') }}">
                        <span class="d-none d-sm-none d-md-inline-block">{{ __('Refresh') }}</span>
                        <span class="d-md-none d-lg-none d-xl-none"><i class="fas fa-redo"></i></span>
                    </button>

                    @if ($can_create)
                        <a href="{{ route('new.doc', $module['slug']) }}" class="btn bg-gradient-primary btn-sm elevation-2 new-form" data-toggle="tooltip" data-placement="bottom" title="{{ __('New') }} {{ __($module['display_name']) }}">
                            <span class="d-none d-sm-none d-md-inline-block">
                                <i class="fas fa-plus fa-sm pr-1"></i>
                                {{ __('New') }} {{ __($module['display_name']) }}
                            </span>
                            <span class="d-md-none d-lg-none d-xl-none"><i class="fas fa-plus"></i></span>
                        </a>
                    @endif

                    @if ($can_delete)
                        <button class="btn bg-gradient-danger btn-sm elevation-2 delete-selected" style="display: none;" data-toggle="tooltip" data-placement="bottom" title="{{ __('Delete selected records') }}">
                            <span class="d-none d-sm-none d-md-inline-block">{{ __('Delete') }}</span>
                            <span class="d-md-none d-lg-none d-xl-none"><i class="fas fa-trash"></i></span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('body')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card elevation-2">
                    <div class="card-header list-filter-sorting">
                        <div class="row">
                            <div class="col-md-9 col-sm-6 col-6">
                                <button type="button" class="btn btn-default btn-sm" id="add-filter" data-toggle="tooltip" data-placement="right" title="{{ __('Show filters') }}">
                                    <i class="fas fa-search fa-sm pr-1"></i>
                                    {{ __('Search') }}
                                </button>
                                <div class="list-active-filters" style="display: none;"></div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-6 text-right sorting-fields" data-action="{{ route('update.list.sorting', ['slug' => $module['slug']]) }}">
                                <div class="btn-group">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                            <span class="clear" id="sort-field" data-value="{{ $module['sort_field'] }}" data-toggle="tooltip" data-placement="bottom" title="{{ __('Sort by field name') }}">
                                                {{ __(str_replace("Id", "ID", awesome_case($module['sort_field']))) }}
                                            </span>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right list-column-dropdown">
                                            @foreach($table_columns as $column_name => $col)
                                                <a href="#" class="dropdown-item sort-list-by-name" data-value="{{ $column_name }}">
                                                    {{ $col['label'] }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-default btn-sm" id="sort-list-order" data-value="{{ $module['sort_order'] }}" data-toggle="tooltip" data-placement="bottom" title="{{ ($module['sort_order'] == 'asc') ? __('Ascending') : __('Descending') }}">
                                        @if ($module['sort_order'] == "asc")
                                            <i class="fas fa-sort-amount-up"></i>
                                        @else
                                            <i class="fas fa-sort-amount-down"></i>
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-header list-column-filters" style="display: none;">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="custom-select" name="column_name" data-toggle="tooltip" data-placement="bottom" title="{{ __('Select field name') }}">
                                        @foreach($table_columns as $column_name => $col)
                                            <option value="{{ $column_name }}" data-type="{{ $col['type'] }}">
                                                {{ $col['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select class="custom-select" name="column_operator" data-toggle="tooltip" data-placement="bottom" title="{{ __('Select condition') }}">
                                        <option value="=">{{ __('Equals') }}</option>
                                        <option value="!=">{{ __('Not Equals') }}</option>
                                        <option value="like">{{ __('Like') }}</option>
                                        <option value="in">{{ __('In') }}</option>
                                        <option value="notin">{{ __('Not In') }}</option>
                                        <option value=">">></option>
                                        <option value="<"><</option>
                                        <option value=">=">>=</option>
                                        <option value="<="><=</option>
                                        <option value="between">{{ __('Between') }}</option>
                                        <option value="notbetween">{{ __('Not Between') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group column-value-container">
                                    <input type="text" name="column_value" class="form-control" autocomplete="off" data-toggle="tooltip" data-placement="bottom" title="{{ __('Select value') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button class="btn bg-gradient-success btn-sm apply-column-filters" data-toggle="tooltip" data-placement="bottom" title="{{ __('Apply filter') }}">
                                        {{ __('Apply') }}
                                    </button>
                                    <button class="btn bg-gradient-danger btn-sm remove-column-filters" data-toggle="tooltip" data-placement="bottom" title="{{ __('Cancel filter') }}">
                                        <i class="fas fa-times p-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0 list-content">
                        <div class="record-selected-count" style="display: none;"></div>
                        <table class="table table-hover text-nowrap list-view" data-module="{{ $module['name'] }}">
                            <thead>
                                <tr class="list-header">
                                    @if ($can_delete)
                                        <th width="10%" data-field-name="row_check" valign="middle">
                                            <div class="custom-control custom-checkbox text-center">
                                                <input type="checkbox" id="check-all" class="custom-control-input">
                                                <label class="custom-control-label" for="check-all"></label>
                                            </div>
                                        </th>
                                    @else
                                        <th class="text-center" valign="middle">#</th>
                                    @endif
                                    @foreach ($columns as $column)
                                        <th name="{{ $column }}" valign="middle">
                                            {{ __(awesome_case($column)) }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="list-view-items"></tbody>
                        </table>
                    </div>
                    <div class="card-body list-actions">
                        <div class="row page-info">
                            <div class="col-sm-7 col-7 p-0">
                                {{ __('Page') }}:
                                <span class="page-no indicator-pill indicator-primary no-indicator mr-1"></span> • 
                                <span class="item-from ml-1"></span> -
                                <span class="item-to"></span> {{ __('of') }} 
                                <span class="indicator-pill indicator-primary no-indicator item-count"></span>
                                {{ __('records') }}
                            </div>
                            <div class="col-sm-5 col-5 p-0">
                                <div class="origin-pagination-content text-right"></div>
                            </div>
                        </div>
                    </div>
                    <div class="data-loader" style="display: none;">{{ __('Loading') }}...</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset(mix('js/origin/list_view.js')) }}"></script>
@endpush
