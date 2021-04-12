@extends('admin')

@section('title', __('Backups') . ' - ' . config('app.brand.name'))

@section('title_section')
    <div class="content-header" id="sticky">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-8">
                    <h1 class="m-0">
                        <small>
                            <i class="fas fa-hdd"></i> {{ __('Backups') }}
                        </small>
                    </h1>
                </div>
                <div class="col-sm-6 col-4 text-right list-btns">
                    <button class="btn btn-outline-default btn-sm elevation-2 refresh-backups" data-toggle="tooltip" data-placement="bottom" data-container="body" title="{{ __('Refresh') }}">
                        <span class="d-none d-sm-none d-md-inline-block">{{ __('Refresh') }}</span>
                        <span class="d-md-none d-lg-none d-xl-none"><i class="fas fa-redo"></i></span>
                    </button>
                    <div class="btn-group new-backup">
                        <button type="button" class="btn bg-gradient-primary btn-sm dropdown-toggle dropdown-icon elevation-2" data-toggle="dropdown">
                            <span class="d-none d-sm-none d-md-inline-block">
                                {{ __('Create Backup') }}
                            </span>
                            <span class="d-md-none d-lg-none d-xl-none"><i class="fas fa-plus"></i></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item create-backup" href="#" data-href="{{ route('create.app.backups') }}">
                                {{ __('Database') }} + {{ __('Files') }}
                            </a>
                            <a class="dropdown-item create-backup" href="#" data-href="{{ route('create.app.backups') }}?type=db">
                                {{ __('Database') }}
                            </a>
                            <a class="dropdown-item create-backup" href="#" data-href="{{ route('create.app.backups') }}?type=files">
                                {{ __('Files') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('body')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card elevation-2">
                    <div class="card-body list-content">
                        <div class="table-responsive list-responsive">
                            <table class="table table-hover list-view">
                                <thead>
                                    <tr class="list-header">
                                        <th class="text-center" valign="middle">#</th>
                                        <th name="name" valign="middle">{{ __('Name') }}</th>
                                        <th name="date" valign="middle">{{ __('Date') }}</th>
                                        <th name="size" valign="middle">{{ __('Size') }}</th>
                                        <th name="size" valign="middle">{{ __('Type') }}</th>
                                        <th name="download" valign="middle">{{ __('Download') }}</th>
                                        <th name="delete" valign="middle">{{ __('Delete') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="list-view-items text-nowrap"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-body list-actions">
                        <div class="row page-info">
                            <div class="col-md-6 col-sm-6 col-5">
                                <span class="item-from"></span> -
                                <span class="item-to"></span> {{ __('of') }} 
                                <span class="badge badge-primary item-count"></span>
                            </div>
                            <div class="col-md-6 col-sm-6 col-7">
                                <div class="origin-pagination-content"></div>
                            </div>
                        </div>
                    </div>
                    <div class="data-loader" style="display: none;">{{ __('Loading') }}...</div>
                </div>
                <div class="data-loader-full" style="display: none;">{{ __('Creating') }}...</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset(mix('js/origin/backups.js')) }}"></script>
@endpush
