<form method="POST" action="{{ route('save.app.settings') }}" name="settings" id="settings" enctype="multipart/form-data" accept-charset="UTF-8">
    {!! csrf_field() !!}
    <div class="card form-section elevation-2" id="setting-details">
        <div class="card-header">
            <h3 class="card-title">{{ __('Global Settings') }}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-angle-up fa-lg"></i>
                </button>
            </div>
        </div>
        <div class="card-body form-content">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">{{ __('Home Page') }}</label>
                        <div>
                            <select name="home_page" class="custom-select" data-mandatory="yes">
                                <option value="modules">{{ __('Modules') }}</option>
                                <option value="reports">{{ __('Reports') }}</option>
                                <option value="settings">{{ __('Settings') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">{{ __('List View Records') }}</label>
                        <div>
                            <div class="input-group">
                                <input type="text" name="list_view_records" class="form-control" data-mandatory="yes" autocomplete="off">
                                <div class="input-group-append">
                                    <span class="input-group-text text-sm">{{ __('per page') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">{{ __('Language') }}</label>
                        <div>
                            <input type="text" name="language" class="form-control autocomplete" data-ac-module="Language" data-ac-field="name" data-mandatory="yes" autocomplete="off">
                            <input type="hidden" name="locale" data-ac-module="Language" data-ac-field="locale">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">{{ __('Time Zone') }}</label>
                        <div>
                            <select name="time_zone" class="custom-select" data-mandatory="yes">
                                @foreach(timezone_identifiers_list() as $timezone)
                                    <option value="{{ $timezone }}">{{ $timezone }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">{{ __('Theme') }}</label>
                        <div>
                            <select name="theme" id="theme" class="custom-select" data-mandatory="yes">
                                <option value="light">{{ __('Light') }}</option>
                                <option value="dark">{{ __('Dark') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                @if (auth()->user()->role == "Administrator" && auth()->user()->username == "admin")
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">{{ __('Enable Backups') }}</label>
                            <div>
                                <select name="enable_backups" class="custom-select" data-mandatory="yes">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                                <small class="text-muted">
                                    {{ __('Database backups will run every Saturday at 11PM') }}.
                                    {{ __('See all your') }} <a href="{{ route('show.app.backups') }}">{{ __('backups') }}</a>
                                </small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</form>
<form method="POST" action="{{ route('password.change') }}" name="change-password-form" id="change-password-form" accept-charset="UTF-8">
    {!! csrf_field() !!}
    <div class="card form-section elevation-2" id="password-details">
        <div class="card-header">
            <h3 class="card-title">{{ __('Change Password') }}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-angle-up fa-lg"></i>
                </button>
            </div>
        </div>
        <div class="card-body form-content">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">
                            {{ __('Current Password') }} <span class="text-danger">*</span>
                        </label>
                        <div>
                            <input type="password" name="current_password" class="form-control" autocomplete="off">
                            <small class="invalid-feedback">
                                {{ __('Current Password field is required') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">
                            {{ __('New Password') }} <span class="text-danger">*</span>
                        </label>
                        <div>
                            <input type="password" name="new_password" class="form-control" autocomplete="off">
                            <small class="invalid-feedback">
                                {{ __('New Password field is required') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">
                            {{ __('Confirm New Password') }} <span class="text-danger">*</span>
                        </label>
                        <div>
                            <input type="password" name="new_password_confirmation" class="form-control" autocomplete="off">
                            <small class="invalid-feedback">
                                {{ __('Confirm New Password field is required') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <small class="text-muted">
                        {{ __('Password should be at least 8 characters including a number, an uppercase letter and a lowercase letter and should not contain any blank spaces') }}
                    </small>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <button type="submit" class="btn bg-gradient-primary btn-sm btn-block elevation-2">
                            {{ __('Change Password') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
