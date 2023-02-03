<fieldset id="allowed-values-container" class="bordered-group grouped-field-list-container">
    <legend>{{{ trans('validation.attributes.allowed_values') }}} @if(count($allowed_values))<span class="toggle-expand glyphicon glyphicon-collapse-down"></span>@endif</legend>
    <div class="form-group @if(count($allowed_values)) dnone @endif">
        <ul class="grouped-field-list" id="allowed_value_list" data-element-list="container">
            @foreach($allowed_values as $key => $allowed_value)
                @include('admin.additional_attributes.form._allowed_value_element')
            @endforeach
        </ul>

        <span data-load-element-url="{{{ action('App\Controllers\Admin\AdditionalAttributesController@getAllowedValueElement') }}}"
              data-element-list-target="allowed_value_list" data-element-list="add"
              class="btn btn-default btn-xs grouped-field-list-add">Добавить новое</span>
    </div>
</fieldset>