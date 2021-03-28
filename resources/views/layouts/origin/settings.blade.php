<form method="POST" action="{{ route('save.app.settings') }}" name="settings" id="settings" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <div class="box form-section" id="user-details">
        <div class="box-body form-content">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">{{ __('Home Page') }}</label>
                        <div>
                            <select name="home_page" id="home_page" class="form-control" data-mandatory="yes">
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
                                <span class="input-group-addon gray-bg">{{ __('per page') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">{{ __('Display Type') }}</label>
                        <div>
                            <select name="display_type" class="form-control" data-mandatory="yes">
                                <option value="comfortable">{{ __('Comfortable') }}</option>
                                <option value="cozy">{{ __('Cozy') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">{{ __('Theme') }}</label>
                        <div>
                            <select name="theme" class="form-control" data-mandatory="yes">
                                <optgroup label="{{ __('Solid') }}">
                                    <option value="skin-blue">{{ __('Blue') }}</option>
                                    <option value="skin-yellow">{{ __('Yellow') }}</option>
                                    <option value="skin-green">{{ __('Green') }}</option>
                                    <option value="skin-purple">{{ __('Purple') }}</option>
                                    <option value="skin-red">{{ __('Red') }}</option>
                                    <option value="skin-black">{{ __('Black') }}</option>
                                </optgroup>
                                <optgroup label="{{ __('Light') }}">
                                    <option value="skin-blue-light">{{ __('Blue Light') }}</option>
                                    <option value="skin-yellow-light">{{ __('Yellow Light') }}</option>
                                    <option value="skin-green-light">{{ __('Green Light') }}</option>
                                    <option value="skin-purple-light">{{ __('Purple Light') }}</option>
                                    <option value="skin-red-light">{{ __('Red Light') }}</option>
                                    <option value="skin-black-light">{{ __('Black Light') }}</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            @if (auth()->user()->role == "Administrator" && auth()->user()->username == "admin")
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">{{ __('Enable Backups') }}</label>
                            <div>
                                <select name="enable_backups" class="form-control" data-mandatory="yes">
                                    <option value="0">{{ __('No') }}</option>
                                    <option value="1">{{ __('Yes') }}</option>
                                </select>
                                <small class="block text-left">
                                    {{ __('Database backups will run every Saturday at 11PM') }}.
                                    {{ __('See all your') }} <a href="{{ route('show.app.backups') }}">{{ __('backups') }}</a>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</form>
