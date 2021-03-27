<div class="row" id="report-filters">
    <div class="col-md-4 col-sm-6 col-6">
        <div class="form-group">
            <input type="text" name="full_name" class="form-control autocomplete" placeholder="{{ __('Full Name') }}" autocomplete="off" data-ac-module="User" data-ac-field="full_name" data-ac-image="avatar">
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-6">
        <div class="form-group">
            <input type="text" name="username" class="form-control autocomplete" placeholder="{{ __('Username') }}" autocomplete="off" data-ac-module="User" data-ac-field="username">
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-6">
        <div class="form-group">
            <input type="text" name="email" class="form-control autocomplete" placeholder="{{ __('Email') }}" autocomplete="off" data-ac-module="User" data-ac-field="email">
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-6">
        <div class="form-group">
            <input type="text" name="role" class="form-control autocomplete" placeholder="{{ __('Role') }}" autocomplete="off" data-ac-module="User" data-ac-field="role" data-ac-unique="Yes">
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-6">
        <div class="form-group">
            <select name="active" class="form-control">
                <option value="" default selected>{{ __('Active') }}</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
    </div>
</div>
