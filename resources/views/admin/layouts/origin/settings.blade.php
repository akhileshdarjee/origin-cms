<form method="POST" action="{{ route('save.app.settings') }}" name="settings" id="settings" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <div class="card form-section elevation-2" id="setting-details">
        <div class="card-body form-content pt-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">{{ __('Home Page') }}</label>
                        <div>
                            <select name="home_page" id="home_page" class="custom-select" data-mandatory="yes">
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
                                <small class="block text-muted">
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
