<div class="box form-section" id="user-details">
    <div class="box-header">
        <h5 class="box-title">User Details
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </h5>
    </div>
    <div class="box-body form-content">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Avatar</label>
                    <div class="media">
                        <div class="pull-left text-center avatar-box">
                        @if (isset($form_data[$table_name]['avatar']) && $form_data[$table_name]['avatar'])
                            <img src="{{ getImage($form_data[$table_name]['avatar'], 100, 100) }}" alt="{{ $form_data[$table_name]['full_name'] }}">
                        @else
                            <i class="fa fa-picture-o fa-2x avatar"></i>
                        @endif
                        </div>
                        <div class="media-body">
                            <label title="Upload image file" for="avatar" class="btn btn-primary btn-sm">
                                <input type="file" accept="image/*" name="avatar" id="avatar" class="hide">
                                Change
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Is Active</label>
                    <div>
                        <select name="is_active" class="form-control" data-mandatory="yes">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Full Name</label>
                    <div>
                        <input type="text" name="full_name" class="form-control" data-mandatory="yes" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Email</label>
                    <div>
                        <input type="text" name="email" class="form-control" data-mandatory="yes" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Login ID</label>
                    <div>
                        <input type="text" name="login_id" class="form-control" data-mandatory="yes" autocomplete="off">
                    </div>
                </div>
            </div>
            @if (!isset($form_data[$table_name]['id']))
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">Password</label>
                        <div>
                            <input type="password" name="password" class="form-control" data-mandatory="yes" autocomplete="off">
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Role</label>
                    <div>
                        <select name="role" class="form-control" data-mandatory="yes">
                            <option value="Administrator">Administrator</option>
                            <option value="Guest">Guest</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>