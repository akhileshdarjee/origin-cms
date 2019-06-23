<div class="row" id="report-filters">
    <div class="col-md-4 col-sm-6 col-xs-6">
        <div class="form-group">
            <input type="text" name="email" id="email" class="form-control autocomplete" 
            placeholder="Email" autocomplete="off" data-ac-module="User" data-ac-field="email">
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-6">
        <div class="form-group">
            <input type="text" name="role" id="role" class="form-control autocomplete" 
            placeholder="Role" autocomplete="off" data-ac-module="User" data-ac-field="role" data-ac-unique="Yes">
        </div>
    </div>
    <div class="col-md-4 col-sm-6 col-xs-6">
        <div class="form-group">
            <select name="is_active" id="is_active" class="form-control">
                <option value="" default selected>Is Active</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
    </div>
</div>
