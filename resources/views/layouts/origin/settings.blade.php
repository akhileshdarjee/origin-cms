<form method="POST" action="{{ route('save.app.settings') }}" name="settings" id="settings" enctype="multipart/form-data">
    {!! csrf_field() !!}
    <div class="box form-section" id="user-details">
        <div class="box-body form-content">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Home Page</label>
                        <div>
                            <select name="home_page" id="home_page" class="form-control" data-mandatory="yes">
                                <option value="modules">Modules</option>
                                <option value="reports">Reports</option>
                                <option value="settings">Settings</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">List View Records</label>
                        <div>
                            <div class="input-group">
                                <input type="text" name="list_view_records" class="form-control" data-mandatory="yes" autocomplete="off">
                                <span class="input-group-addon gray-bg">per page</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Display Type</label>
                        <div>
                            <select name="display_type" class="form-control" data-mandatory="yes">
                                <option value="comfortable">Comfortable</option>
                                <option value="cozy">Cozy</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Theme</label>
                        <div>
                            <select name="theme" class="form-control" data-mandatory="yes">
                                <optgroup label="Solid">
                                    <option value="skin-blue">Blue</option>
                                    <option value="skin-yellow">Yellow</option>
                                    <option value="skin-green">Green</option>
                                    <option value="skin-purple">Purple</option>
                                    <option value="skin-red">Red</option>
                                    <option value="skin-black">Black</option>
                                </optgroup>
                                <optgroup label="Light">
                                    <option value="skin-blue-light">Blue Light</option>
                                    <option value="skin-yellow-light">Yellow Light</option>
                                    <option value="skin-green-light">Green Light</option>
                                    <option value="skin-purple-light">Purple Light</option>
                                    <option value="skin-red-light">Red Light</option>
                                    <option value="skin-black-light">Black Light</option>
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
                            <label class="control-label">Enable Backups</label>
                            <div>
                                <select name="enable_backups" class="form-control" data-mandatory="yes">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <small class="block text-left">
                                    Database backups will run every Saturday at 11PM.
                                    See all your <a href="{{ route('show.app.backups') }}">backups</a>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</form>
