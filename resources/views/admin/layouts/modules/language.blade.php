<div class="card form-section elevation-2" id="language-details">
    <div class="card-header">
        <h3 class="card-title">{{ __('Language Details') }}</h3>
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
                        <input type="text" name="name" class="form-control " data-mandatory="yes" autocomplete="off">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">
                        {{ __('Locale') }} <span class="text-danger">*</span>
                    </label>
                    <div>
                        @if (isset($form_data[$table_name]['id']))
                            <input type="text" name="locale" class="form-control" maxlength="2" data-mandatory="yes" autocomplete="off" readonly disabled>
                        @else
                            <input type="text" name="locale" class="form-control" maxlength="2" data-mandatory="yes" autocomplete="off">
                        @endif
                        <small class="block text-muted">
                            {{ __('Please enter two-letter ISO 639-1 code from') }}
                            <a href="https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes" target="_blank">{{ __('here') }}</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
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
    </div>
</div>
