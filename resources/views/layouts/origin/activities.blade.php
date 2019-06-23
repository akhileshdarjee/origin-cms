@extends('layouts.app')

@section('title', 'Activities - ' . config('app.brand.name'))
@section('search', 'Activities')

@section('title_section')
    <div id="sticky-anchor"></div>
    <section class="content-header title-section" id="sticky">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-8">
                <div class="form-name">
                    <i class="fa fa-bell"></i> Activities
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-4 text-right">
                <button class="btn btn-primary btn-sm refresh-activity" data-toggle="tooltip" 
                    data-placement="bottom" data-container="body" title="Refresh">
                    <span class="hidden-xs">Refresh</span>
                    <span class="visible-xs"><i class="fa fa-refresh"></i></span>
                </button>
            </div>
        </div>
    </section>
@endsection

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border report-filter-sec">
                    <div class="row" id="report-filters">
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <input type="text" name="owner" class="form-control autocomplete activity-filter" 
                                placeholder="Select User..." autocomplete="off" data-ac-module="User" data-ac-field="login_id">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <select name="action" class="form-control activity-filter">
                                    <option value="" default selected>Select Action...</option>
                                    <option value="Login">Login</option>
                                    <option value="Logout">Logout</option>
                                    <option value="Create">Create</option>
                                    <option value="Update">Update</option>
                                    <option value="Delete">Delete</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <select name="module" class="form-control activity-filter">
                                    <option value="" default selected>Select Module...</option>
                                    @foreach($modules as $module)
                                        <option value="{{ $module['display_name'] }}">
                                            {{ $module['display_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-body report-content">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <ul class="timeline origin-activities"></ul>
                        </div>
                    </div>
                </div>
                <div class="box-footer clearfix">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-5">
                            <span id="item-from"></span> -
                            <span id="item-to"></span> of 
                            <strong>
                                <span class="badge" id="item-count"></span>
                            </strong>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-7">
                            <div class="origin-pagination-content"></div>
                        </div>
                    </div>
                </div>
                <div class="data-loader" style="display: none;">Loading...</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset(mix('js/origin_activity.js')) }}"></script>
@endpush
