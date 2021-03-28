<div class="box form-section" id="user-details">
    <div class="box-header">
        <h5 class="box-title">{{ __('User Details') }}
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
                    <label class="control-label">{{ __('Avatar') }}</label>
                    <div class="media">
                        <div class="pull-left text-center avatar-box">
                        @if (isset($form_data[$table_name]['avatar']) && $form_data[$table_name]['avatar'])
                            <img src="{{ getImage($form_data[$table_name]['avatar'], '100', '100') }}" class="fancyimg" alt="{{ $form_data[$table_name][$form_title] }}" data-big="{{ getImage($form_data[$table_name]['avatar']) }}">
                        @else
                            <i class="fa fa-picture-o fa-2x avatar"></i>
                        @endif
                        </div>
                        <div class="media-body">
                            <label title="Upload image file" for="avatar" class="btn btn-primary btn-sm">
                                <input type="file" accept="image/*" name="avatar" id="avatar" class="hide">
                                {{ __('Change') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('Active') }}</label>
                    <div>
                        <select name="active" class="form-control" data-mandatory="yes">
                            <option value="1">{{ __('Yes') }}</option>
                            <option value="0">{{ __('No') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('Title, First & Last Name') }}</label>
                    <div class="input-group">
                        <div class="input-group-addon user-name-addon">
                            <select name="title" class="form-control" data-mandatory="no" style="height: auto;">
                                <option value="" default selected>{{ __('Title') }}</option>
                                <option value="Mr.">{{ __('Mr.') }}</option>
                                <option value="Dr.">{{ __('Dr.') }}</option>
                                <option value="Prof.">{{ __('Prof.') }}</option>
                                <option value="Rev.">{{ __('Rev.') }}</option>
                                <option value="Hon.">{{ __('Hon.') }}</option>
                                <option value="Mrs.">{{ __('Mrs.') }}</option>
                                <option value="Ms.">{{ __('Ms.') }}</option>
                                <option value="Miss">{{ __('Miss') }}</option>
                            </select>
                        </div>
                        <input type="text" name="first_name" class="form-control" placeholder="{{ __('First Name') }}" data-mandatory="yes" autocomplete="off">
                        <input type="text" name="last_name" class="form-control" placeholder="{{ __('Last Name') }}" data-mandatory="no" autocomplete="off">
                    </div>
                    <input type="hidden" name="full_name" class="form-control" data-mandatory="yes" autocomplete="off">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('Email') }}</label>
                    <div>
                        <input type="text" name="email" class="form-control" data-mandatory="yes" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">{{ __('Username') }}</label>
                    <div>
                        <input type="text" name="username" class="form-control" data-mandatory="yes" autocomplete="off">
                    </div>
                </div>
            </div>
            @if (!isset($form_data[$table_name]['id']))
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">{{ __('Password') }}</label>
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
                    <label class="control-label">{{ __('Role') }}</label>
                    <div>
                        <select name="role" class="form-control" data-mandatory="yes">
                            <option value="Administrator">{{ __('Administrator') }}</option>
                            <option value="Guest">{{ __('Guest') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
