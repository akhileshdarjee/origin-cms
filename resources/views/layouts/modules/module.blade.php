<div class="box form-section" id="module-details">
    <div class="box-header">
        <h5 class="box-title">{{ __('Module Details') }}
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
                    <label class="control-label">{{ __('Name') }}</label>
                    <div>
                        <input type="text" name="name" class="bg-focus form-control" data-mandatory="yes" autocomplete="off">
                        <small class="block">{{ __('Should be without spaces and any special characters') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('Active') }}</label>
                    <div>
                        <select name="active" class="form-control" data-mandatory="yes">
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
                    <label class="control-label">{{ __('Display Name') }}</label>
                    <div>
                        <input type="text" name="display_name" class="form-control" data-mandatory="yes" autocomplete="off">
                        <small class="block">{{ __('Title for the Module') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('Table Name') }}</label>
                    <div>
                        <input type="text" name="table_name" class="form-control" data-mandatory="yes" autocomplete="off">
                        <small class="block">{{ __('Name of table of your database you want to connect to this Module') }}</small>
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
                    <label class="control-label">{{ __('Slug') }}</label>
                    <div>
                        <input type="text" name="slug" class="form-control" data-mandatory="yes" autocomplete="off">
                        <small class="block">{{ __('Used as URL slug to show data in list & form view') }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('Create Migration') }}</label>
                    <div>
                        <select name="create_migration" class="form-control" data-mandatory="yes">
                            <option value="1" default selected>{{ __('Yes') }}</option>
                            <option value="0">{{ __('No') }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('List View Columns') }}</label>
                    <div>
                        <input type="text" name="list_view_columns" class="form-control" data-mandatory="yes" autocomplete="off">
                        <small class="block">{{ __('Separate multiple columns with comma') }}(,)</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('Show') }}</label>
                    <div>
                        <select name="show" class="form-control" data-mandatory="yes">
                            <option value="1" default selected>{{ __('Yes') }}</option>
                            <option value="0">{{ __('No') }}</option>
                        </select>
                        <small class="block">{{ __('Select \'Yes\' to show this on Modules page') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('Sequence No') }}</label>
                    <div>
                        <input type="text" name="sequence_no" class="form-control" data-mandatory="yes" autocomplete="off">
                        <small class="block">{{ __('You can change the sequence of Modules by drag-drop method on Modules page') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="box form-section" id="module-customization-details">
    <div class="box-header">
        <h5 class="box-title">{{ __('Customization') }}
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
                    <label class="control-label">{{ __('Background color') }}</label>
                    <div>
                        <input type="text" name="bg_color" class="form-control" data-mandatory="no" autocomplete="off">
                        <small class="block">{{ __('Background color of the Module icon') }}</small>
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
                        <small class="block">{{ __('Icon of the Module (eg. \'fa fa-diamond\')') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('Icon Color') }}</label>
                    <div>
                        <input type="text" name="icon_color" class="form-control" data-mandatory="no" autocomplete="off">
                        <small class="block">{{ __('Foreground color of the Module icon') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="box form-section" id="module-configuration-details">
    <div class="box-header">
        <h5 class="box-title">{{ __('Configuration') }}
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
                    <label class="control-label">{{ __('Form Title') }}</label>
                    <div>
                        <input type="text" name="form_title" class="form-control" data-mandatory="yes" autocomplete="off" val="id">
                        <small class="block">{{ __('This field is displayed as individual record title') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('Image Field') }}</label>
                    <div>
                        <input type="text" name="image_field" class="form-control" data-mandatory="no" autocomplete="off">
                        <small class="block">{{ __('Must be of field Image') }}</small>
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
                    <label class="control-label">{{ __('Sort Order') }}</label>
                    <div>
                        <select name="sort_order" class="form-control" data-mandatory="yes">
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
                        <select name="is_child_table" class="form-control" data-mandatory="yes">
                            <option value="1">{{ __('Yes') }}</option>
                            <option value="0" default selected>{{ __('No') }}</option>
                        </select>
                        <small class="block">{{ __('Child Table will be shown as Grid in form view') }}</small>
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
