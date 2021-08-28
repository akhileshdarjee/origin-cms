@extends('admin')

@section('title', __('Activity') . ' - ' . config('app.brand.name'))

@section('title_section')
    <div id="sticky-anchor"></div>
    <div class="content-header" id="sticky">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-8 content-title">
                    <h1 class="m-0">
                        <small class="content-title-label">
                            <i class="fas fa-bell"></i> {{ __('Activity') }}
                        </small>
                    </h1>
                </div>
                <div class="col-sm-4 col-4 text-right list-btns">
                    <button class="btn bg-gradient-primary btn-sm elevation-2 refresh-activity" data-toggle="tooltip" data-placement="bottom" data-container="body" title="{{ __('Refresh') }}">
                        {{ __('Refresh') }}
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
                                        <option value="Auth">{{ __('Auth') }}</option>
                                        @if (in_array(auth()->user()->role, ["System Administrator", "Administrator"]))
                                            <option value="Report">{{ __('Report') }}</option>
                                        @endif
                                        @if (auth()->user()->role == "Administrator" && auth()->user()->username == "admin")
                                            <option value="Backup">{{ __('Backup') }}</option>
                                        @endif
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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                <div class="timeline origin-activities"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body list-actions with-border-top">
                        <div class="row page-info">
                            <div class="col-sm-7 col-7">
                                {{ __('Page') }} :
                                <strong><span class="page-no mr-1"></span></strong> • 
                                <span class="item-from ml-1"></span> -
                                <span class="item-to"></span> {{ __('of') }} 
                                <span class="badge badge-primary item-count"></span>
                                {{ __('records') }}
                            </div>
                            <div class="col-sm-5 col-5">
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
