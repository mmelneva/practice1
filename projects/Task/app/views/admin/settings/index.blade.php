@extends('admin.layouts.default')

@section('title')
    Константы
@stop

@section('content')
    {{ Form::tbModelWithErrors([], $errors, ['url' => action('App\Controllers\Admin\SettingsController@putUpdateAll'), 'method' => 'put', 'scrollable' => false]) }}
    <table class="table settings-table">
        <thead>
        <tr>
            <th class="col-name">Название</th>
            <th class="col-value">Значение</th>
        </tr>
        </thead>
        @foreach ($group_list as $group)
            <tbody>
            <tr>
                <th colspan="2">
                    <span class="toggle" data-group-id="{{{ $group->id }}}">{{{ $group->name }}}</span>
                </th>
            </tr>
            </tbody>
            <tbody class="settings-group {{ errors_contain($errors, $group->settings->map(function($v){ return "setting.{$v->id}"; })->toArray()) ? 'group-show' : '' }}"
                   data-group-id="{{{ $group->id }}}">
            @foreach ($group->settings as $setting)
                <tr>
                    <td class="col-name">
                        <label for="setting-{{{ $setting->id }}}">{{{ $setting->title }}}:</label>
                        @if (!empty($setting->description))
                            <div class="setting-description">{{ $setting->description }}</div>
                        @endif
                    </td>
                    <td class="col-value">
                        {{ Form::tbFormGroupOpen("setting.{$setting->id}") }}
                        @if ($setting->value_style == \App\Services\Settings\SettingValue::TYPE_TEXT)
                            {{ Form::tbText("setting[{$setting->id}]", $setting->value, ['id' => "setting-{$setting->id}", 'class' => 'form-control input-sm']) }}
                        @elseif ($setting->value_style == \App\Services\Settings\SettingValue::TYPE_TEXTAREA)
                            {{ Form::tbTextarea("setting[{$setting->id}]", $setting->value, ['id' => "setting-{$setting->id}", 'class' => 'form-control input-sm', 'rows' => 6]) }}
                        @elseif ($setting->value_style == \App\Services\Settings\SettingValue::TYPE_TEXTAREA_TINYMCE)
                            {{ Form::tbTextarea("setting[{$setting->id}]", $setting->value, ['id' => "setting-{$setting->id}", 'class' => 'form-control input-sm', 'rows' => 6, 'data-tinymce' => true]) }}
                        @elseif ($setting->value_style == \App\Services\Settings\SettingValue::TYPE_CHECKBOX)
                            <input type="hidden" name="setting[{{{ $setting->id }}}]" value="0"/>
                            {{ Form::checkbox("setting[{$setting->id}]", 1, $setting->value, ['id' => "setting-{$setting->id}", 'class' => 'checkbox']) }}
                        @elseif ($setting->value_style == \App\Services\Settings\SettingValue::TYPE_REDIRECTS)
                            @include('admin.settings.form._redirects_field')
                        @else
                            {{{ $setting->value }}}
                        @endif
                        {{ Form::tbFormGroupClose() }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        @endforeach
        <tfoot>
        <tr>
            <td class="col-name">&nbsp;</td>
            <td class="col-value">
                <button type="submit" class="btn btn-success">Сохранить</button>
            </td>
        </tr>
        </tfoot>
    </table>
    {{ Form::close() }}
@stop