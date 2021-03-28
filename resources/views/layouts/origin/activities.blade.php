@extends('layouts.app')

@section('title', __('Activities') . ' - ' . config('app.brand.name'))
@section('search', __('Activities'))

@section('title_section')
    <div id="sticky-anchor"></div>
    <section class="content-header title-section" id="sticky">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-8">
                <div class="form-name">
                    <i class="fa fa-bell"></i> {{ __('Activities') }}
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-4 text-right">
                <button class="btn btn-primary btn-sm refresh-activity" data-toggle="tooltip" data-placement="bottom" data-container="body" title="{{ __('Refresh') }}">
                    <span class="hidden-xs">{{ __('Refresh') }}</span>
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
                                <input type="text" name="owner" class="form-control autocomplete activity-filter" placeholder="{{ __('Select User') }}..." autocomplete="off" data-ac-module="User" data-ac-field="username">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <select name="action" class="form-control activity-filter">
                                    <option value="" default selected>{{ __('Select Action') }}...</option>
                                    <option value="Login">{{ __('Login') }}</option>
                                    <option value="Logout">{{ __('Logout') }}</option>
                                    <option value="Create">{{ __('Create') }}</option>
                                    <option value="Update">{{ __('Update') }}</option>
                                    <option value="Delete">{{ __('Delete') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <select name="module" class="form-control activity-filter">
                                    <option value="" default selected>{{ __('Select Module') }}...</option>
                                    @foreach($modules as $module)
                                        <option value="{{ $module['display_name'] }}">
                                            {{ __($module['display_name']) }}
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
                            <span id="item-to"></span> {{ __('of') }} 
                            <strong>
                                <span class="badge" id="item-count"></span>
                            </strong>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-7">
                            <div class="origin-pagination-content"></div>
                        </div>
                    </div>
                </div>
                <div class="data-loader" style="display: none;">{{ __('Loading') }}...</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset(mix('js/origin/activity.js')) }}"></script>
@endpush
