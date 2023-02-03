{{-- Image field for model --}}

{{ Form::tbFormGroupOpen(scope_dot_name("{$field}_file", isset($scope) ? $scope : [])) }}
    {{ Form::tbLabel(scope_field_name("{$field}_file", isset($scope) ? $scope : []), isset($label) ? $label : trans("validation.attributes.{$field}_file")) }}
    @if (!is_null($model) && $model->getAttachment($field)->exists())
        <div class="loaded-image">
            <a href="{{{ (empty($mini)) ? $model->getAttachment($field)->getRelativePath() : $model->getAttachment($field)->getRelativePath('small') }}}" target="_blank" rel="prettyPhoto">
                <img src="{{{ $model->getAttachment($field)->getRelativePath('thumb') }}}" />
            </a>
            <label>
                {{ Form::checkbox(scope_field_name("{$field}_remove", isset($scope) ? $scope : []), 1) }}
                удалить
            </label>
        </div>
    @endif
    <div class="file-upload-container">
        {{ Form::file(scope_field_name("{$field}_file", isset($scope) ? $scope : [])) }}
        <label for="{{{ scope_field_name("{$field}_file_text", isset($scope) ? $scope : []) }}}">или url:</label>
        <input type="text" id="{{{ scope_field_name("{$field}_file_text", isset($scope) ? $scope : []) }}}" name="{{{ scope_field_name("{$field}_file", isset($scope) ? $scope : []) }}}" class="form-control" />
    </div>
{{ Form::tbFormGroupClose() }}