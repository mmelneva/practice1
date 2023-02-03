{{-- File field for model --}}

{{ Form::tbFormGroupOpen(scope_dot_name("{$field}_file", isset($scope) ? $scope : [])) }}
    {{ Form::tbLabel(scope_field_name("{$field}_file", isset($scope) ? $scope : []), trans("validation.attributes.{$field}_file")) }}

    @if ($model->getAttachment($field)->exists())
        <div class="loaded-file">
            <a href="{{{ $model->getAttachment($field)->getRelativePath() }}}" target="_blank">{{{ $model->file }}}</a>
            <label>
                {{ Form::checkbox(scope_field_name("{$field}_remove", isset($scope) ? $scope : []), 1) }}
                удалить
            </label>
        </div>
    @endif

    <div class="file-upload-container">
        {{ Form::file(scope_field_name("{$field}_file", isset($scope) ? $scope : [])) }}
    </div>
{{ Form::tbFormGroupClose() }}
