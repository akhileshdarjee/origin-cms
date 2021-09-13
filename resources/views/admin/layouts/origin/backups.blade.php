@extends('admin')

@section('title', __('Backups') . ' - ' . config('app.brand.name'))

@section('title_section')
    <div id="sticky-anchor"></div>
    <div class="content-header" id="sticky">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-6 content-title">
                    <h1 class="m-0">
                        <small class="content-title-label">
                            <i class="fas fa-hdd"></i> {{ __('Backups') }}
                        </small>
                    </h1>
                </div>
                <div class="col-sm-4 col-6 text-right list-btns">
                    <button class="btn btn-default btn-sm elevation-2 refresh-backups" data-toggle="tooltip" data-placement="bottom" data-container="body" title="{{ __('Refresh') }}">
                        <span class="d-none d-sm-none d-md-inline-block">{{ __('Refresh') }}</span>
                        <span class="d-md-none d-lg-none d-xl-none"><i class="fas fa-redo"></i></span>
                    </button>
                    <div class="btn-group new-backup">
                        <button type="button" class="btn bg-gradient-primary btn-sm dropdown-toggle dropdown-icon elevation-2" data-toggle="dropdown">
                            {{ __('Create Backup') }}
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
                            <table class="table list-view backups-view">
                                <thead>
                                    <tr class="list-header">
                                        <th class="text-center" valign="middle">#</th>
                                        <th name="name" valign="middle">{{ __('Name') }}</th>
                                        <th name="date" valign="middle">{{ __('Date') }}</th>
                                        <th name="size" valign="middle">{{ __('Size') }}</th>
                                        <th name="size" valign="middle">{{ __('Type') }}</th>
                                        <th class="text-center" name="download" valign="middle">{{ __('Download') }}</th>
                                        <th class="text-center" name="delete" valign="middle">{{ __('Delete') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="list-view-items text-nowrap"></tbody>
                            </table>
                        </div>
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
