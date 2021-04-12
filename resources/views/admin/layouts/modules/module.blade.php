<div class="card form-section elevation-2" id="module-details">
    <div class="card-header">
        <h3 class="card-title">{{ __('Module Details') }}</h3>
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
                        {{ __('Name') }} <span class="text-danger">*</span>
                    </label>
                    <div>
                        <input type="text" name="name" class="bg-focus form-control" data-mandatory="yes" autocomplete="off">
                        <small class="block text-muted">{{ __('Should be without spaces and any special characters') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">
                        {{ __('Active') }} <span class="text-danger">*</span>
                    </label>
                    <div>
                        <select name="active" class="custom-select" data-mandatory="yes">
                            <option value="1" default selected>{{ __('Yes') }}</option>
                            <option value="0">{{ __('No') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">
                        {{ __('Display Name') }} <span class="text-danger">*</span>
                    </label>
                    <div>
                        <input type="text" name="display_name" class="form-control" data-mandatory="yes" autocomplete="off">
                        <small class="block text-muted">{{ __('Title for the Module') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">
                        {{ __('Table Name') }} <span class="text-danger">*</span>
                    </label>
                    <div>
                        <input type="text" name="table_name" class="form-control" data-mandatory="yes" autocomplete="off">
                        <small class="block text-muted">{{ __('Name of table of your database you want to connect to this Module') }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('Controller Name') }}</label>
                    <div>
                        <input type="text" name="controller_name" class="form-control" data-mandatory="no" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">
                        {{ __('Slug') }} <span class="text-danger">*</span>
                    </label>
                    <div>
                        <input type="text" name="slug" class="form-control" data-mandatory="yes" autocomplete="off">
                        <small class="block text-muted">{{ __('Used as URL slug to show data in list & form view') }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">
                        {{ __('Create Migration') }} <span class="text-danger">*</span>
                    </label>
                    <div>
                        <select name="create_migration" class="custom-select" data-mandatory="yes">
                            <option value="1" default selected>{{ __('Yes') }}</option>
                            <option value="0">{{ __('No') }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">
                        {{ __('List View Columns') }} <span class="text-danger">*</span>
                    </label>
                    <div>
                        <input type="text" name="list_view_columns" class="form-control" data-mandatory="yes" autocomplete="off">
                        <small class="block text-muted">{{ __('Separate multiple columns with comma') }}(,)</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">
                        {{ __('Show') }} <span class="text-danger">*</span>
                    </label>
                    <div>
                        <select name="show" class="custom-select" data-mandatory="yes">
                            <option value="1" default selected>{{ __('Yes') }}</option>
                            <option value="0">{{ __('No') }}</option>
                        </select>
                        <small class="block text-muted">{{ __('Select \'Yes\' to show this on Modules page') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">
                        {{ __('Sequence No') }} <span class="text-danger">*</span>
                    </label>
                    <div>
                        <input type="text" name="sequence_no" class="form-control" data-mandatory="yes" autocomplete="off">
                        <small class="block text-muted">{{ __('You can change the sequence of Modules by drag-drop method on Modules page') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card form-section elevation-2" id="module-customization-details">
    <div class="card-header">
        <h3 class="card-title">{{ __('Customization') }}</h3>
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
                    <label class="control-label">{{ __('Background color') }}</label>
                    <div>
                        <input type="text" name="bg_color" class="form-control" data-mandatory="no" autocomplete="off">
                        <small class="block text-muted">{{ __('Background color of the Module icon') }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('Icon') }}</label>
                    <div>
                        <input type="text" name="icon" class="form-control" data-mandatory="no" autocomplete="off">
                        <small class="block text-muted">{{ __('Icon of the Module (eg. \'fas fa-gem\')') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('Icon Color') }}</label>
                    <div>
                        <input type="text" name="icon_color" class="form-control" data-mandatory="no" autocomplete="off">
                        <small class="block text-muted">{{ __('Foreground color of the Module icon') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card form-section elevation-2" id="module-configuration-details">
    <div class="card-header">
        <h3 class="card-title">{{ __('Configuration') }}</h3>
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
                        {{ __('Form Title') }} <span class="text-danger">*</span>
                    </label>
                    <div>
                        <input type="text" name="form_title" class="form-control" data-mandatory="yes" autocomplete="off" val="id">
                        <small class="block text-muted">{{ __('This field is displayed as individual record title') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('Image Field') }}</label>
                    <div>
                        <input type="text" name="image_field" class="form-control" data-mandatory="no" autocomplete="off">
                        <small class="block text-muted">{{ __('Must be of field Image') }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('Sort Field') }}</label>
                    <div>
                        <input type="text" name="sort_field" class="form-control" data-mandatory="no" autocomplete="off" val="id">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">
                        {{ __('Sort Order') }} <span class="text-danger">*</span>
                    </label>
                    <div>
                        <select name="sort_order" class="custom-select" data-mandatory="yes">
                            <option value="desc" default selected>{{ __('Descending') }}</option>
                            <option value="asc">{{ __('Ascending') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('Is Child Table') }}</label>
                    <div>
                        <select name="is_child_table" class="custom-select" data-mandatory="yes">
                            <option value="1">{{ __('Yes') }}</option>
                            <option value="0" default selected>{{ __('No') }}</option>
                        </select>
                        <small class="block text-muted">{{ __('Child Table will be shown as Grid in form view') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('Description') }}</label>
                    <div>
                        <textarea rows="5" name="description" class="form-control" data-mandatory="no" autocomplete="off"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
