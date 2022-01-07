<div class="card form-section elevation-2" id="user-details">
    <div class="card-header">
        <h3 class="card-title">{{ __('User Details') }}</h3>
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
                    <label class="control-label">{{ __('Avatar') }}</label>
                    <div class="media">
                        <div class="pull-left text-center avatar-box">
                            @if (isset($form_data[$table_name]['avatar']) && $form_data[$table_name]['avatar'])
                                <img src="{{ getImage($form_data[$table_name]['avatar'], '100', '100') }}" class="fancyimg" alt="{{ $form_data[$table_name][$form_title] }}" data-big="{{ getImage($form_data[$table_name]['avatar']) }}">
                            @else
                                @if (isset($form_data[$table_name]['id']))
                                    <div class="avatar-initials elevation-1" data-name="{{ $form_data[$table_name]['full_name'] }}"></div>
                                @else
                                    <i class="fas fa-image fa-2x avatar"></i>
                                @endif
                            @endif
                        </div>
                        <div class="media-body">
                            <label for="avatar" class="btn bg-gradient-secondary btn-sm ml-3 text-xs">
                                <input type="file" accept="image/*" name="avatar" id="avatar" class="d-none">
                                {{ __('Change') }}
                            </label>
                        </div>
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
                            <option value="1">{{ __('Yes') }}</option>
                            <option value="0">{{ __('No') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-1">
                <div class="form-group">
                    <label class="control-label">{{ __('Title') }}</label>
                    <div>
                        <select name="title" class="custom-select" data-mandatory="no">
                            <option value="" default selected></option>
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
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">{{ __('First Name') }}</label>
                    <div>
                        <input type="text" name="first_name" class="form-control" data-mandatory="yes" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">{{ __('Last Name') }}</label>
                    <div>
                        <input type="text" name="last_name" class="form-control" data-mandatory="no" autocomplete="off">
                        <input type="hidden" name="full_name" data-mandatory="yes" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">
                        {{ __('Email') }} <span class="text-danger">*</span>
                    </label>
                    <div>
                        <input type="text" name="email" class="form-control" data-mandatory="yes" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">
                        {{ __('Username') }} <span class="text-danger">*</span>
                    </label>
                    <div>
                        <input type="text" name="username" class="form-control" data-mandatory="yes" autocomplete="off">
                    </div>
                </div>
            </div>
            @if (!isset($form_data[$table_name]['id']))
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="control-label">
                            {{ __('Password') }} <span class="text-danger">*</span>
                        </label>
                        <div>
                            <input type="password" name="password" class="form-control" data-mandatory="yes" autocomplete="off">
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<div class="card form-section elevation-2" id="other-details">
    <div class="card-header">
        <h3 class="card-title">{{ __('Other Details') }}</h3>
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
                                <option value="{{ $timezone }}"{{ ($timezone == 'UTC') ? ' default selected' : '' }}>
                                    {{ $timezone }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">
                        {{ __('Role') }} <span class="text-danger">*</span>
                    </label>
                    <div>
                        <select name="role" class="custom-select" data-mandatory="yes">
                            <option value="Administrator">{{ __('Administrator') }}</option>
                            <option value="Guest">{{ __('Guest') }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">
                        {{ __('Banned Until') }}
                    </label>
                    <div>
                        <input type="text" name="banned_until" class="form-control datetimepicker" data-mandatory="no" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
