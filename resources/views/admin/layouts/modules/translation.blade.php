<div class="card form-section elevation-2" id="translation-details">
    <div class="card-header">
        <h3 class="card-title">{{ __('Translation Details') }}</h3>
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
                        {{ __('Language') }} <span class="text-danger">*</span>
                    </label>
                    <div>
                        <input type="text" name="language" class="form-control autocomplete" data-ac-module="Language" data-ac-field="name" data-mandatory="yes" autocomplete="off">
                        <input type="hidden" name="locale" data-ac-module="Language" data-ac-field="locale">
                        <small class="text-muted">
                            {{ __('Text will be translated in this language') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">
                        {{ __('From') }} <span class="text-danger">*</span>
                    </label>
                    <div>
                        <textarea rows="5" name="from" class="form-control" data-mandatory="yes" autocomplete="off"></textarea>
                        <small class="text-muted">
                            {{ __('Text that you wish to translate (English)') }}
                        </small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">
                        {{ __('To') }} <span class="text-danger">*</span>
                    </label>
                    <div>
                        <textarea rows="5" name="to" class="form-control" data-mandatory="yes" autocomplete="off"></textarea>
                        <small class="text-muted">
                            {{ __('Translated text') }}
                            (<span class="selected-language">{{ __('Selected') }}</span>)
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
