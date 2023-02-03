<div class="row">
    <div class="col-sm-4">
        @include('admin.settings.form._redirects_field._rule_field')
    </div>
    <div class="col-sm-1" style="text-align: center;">
        <i class="fa fa-angle-right fa-2x" aria-hidden="true"></i>
    </div>
    <div class="col-sm-4">
        @include('admin.settings.form._redirects_field._url_field')
    </div>
    <div class="col-sm-1">
        <button type="button" class="btn btn-default" data-rule-action="remove"><i class="fa fa-minus"></i></button>
    </div>
</div>