@extends('layouts.app')

@section('title', __('Activity') . ' - ' . config('app.brand.name'))

@section('title_section')
    <div id="sticky-anchor"></div>
    <div class="content-header" id="sticky">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-6">
                    <h1 class="m-0">
                        <small>
                            <i class="fas fa-bell"></i> {{ __('Activity') }}
                        </small>
                    </h1>
                </div>
                <div class="col-sm-6 col-6 text-right list-btns">
                    <button class="btn bg-gradient-primary btn-sm elevation-2 refresh-activity" data-toggle="tooltip" data-placement="bottom" data-container="body" title="{{ __('Refresh') }}">
                        <span class="d-none d-sm-none d-md-inline-block">{{ __('Refresh') }}</span>
                        <span class="d-md-none d-lg-none d-xl-none"><i class="fas fa-redo"></i></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card elevation-2">
                    <div class="card-header report-filter-sec">
                        <div class="row" id="report-filters">
                            <div class="col-md-4 col-sm-6 col-6">
                                <div class="form-group">
                                    <input type="text" name="owner" class="form-control autocomplete activity-filter" placeholder="{{ __('Select User') }}..." autocomplete="off" data-ac-module="User" data-ac-field="username" data-ac-image="avatar">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-6">
                                <div class="form-group">
                                    <select name="action" class="custom-select activity-filter">
                                        <option value="" default selected>{{ __('Select Action') }}...</option>
                                        <option value="Login">{{ __('Login') }}</option>
                                        <option value="Logout">{{ __('Logout') }}</option>
                                        <option value="Create">{{ __('Create') }}</option>
                                        <option value="Update">{{ __('Update') }}</option>
                                        <option value="Delete">{{ __('Delete') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-6">
                                <div class="form-group">
                                    <select name="module" class="custom-select activity-filter">
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
                    <div class="card-body report-content">
                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                <div class="timeline origin-activities"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer list-actions bg-white">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-5">
                                <span id="item-from"></span> -
                                <span id="item-to"></span> {{ __('of') }} 
                                <strong>
                                    <span class="badge badge-dark" id="item-count"></span>
                                </strong>
                            </div>
                            <div class="col-md-6 col-sm-6 col-7">
                                <div class="origin-pagination-content"></div>
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
    <script type="text/javascript" src="{{ asset(mix('js/origin/activity.js')) }}"></script>
@endpush
