<li data-element-list="element" data-element-key="{{ $key }}">
    <div>
        <span data-element-list="remove" class="remove glyphicon glyphicon-remove"></span>
        @if ($banner->exists)
            <span data-element-list="toggle-expand" class="toggle-expand glyphicon glyphicon-collapse-down"></span>
        @endif
    </div>

    <div data-element-list="header" class="header {{{ !$banner->exists ? 'dnone' : '' }}}">
        <span data-element-list="edit">{{{ array_get($banner, 'name', '-') }}}</span>
    </div>
    <div data-element-list="fields" class="{{{ !$banner->exists ? '' : 'dnone' }}}">
        {{ Form::hidden("banners[{$key}][id]", array_get($banner, 'id')) }}

        {{ Form::tbFormGroupOpen("banners.{$key}.name") }}
        {{ Form::tbLabel("banners[{$key}][name]", trans('validation.attributes.name')) }}
        {{ Form::tbText("banners[{$key}][name]", array_get($banner, 'name')) }}
        {{ Form::tbFormGroupClose() }}

        {{ Form::tbFormGroupOpen("banners.{$key}.publish") }}
        {{ Form::tbStateCheckbox("banners[{$key}][publish]", trans('validation.attributes.publish'), array_get($banner, 'publish')) }}
        {{ Form::tbFormGroupClose() }}

        {{ Form::tbFormGroupOpen("banners.{$key}.position") }}
        {{ Form::tbLabel("banners[{$key}][position]", trans('validation.attributes.position')) }}
        {{ Form::tbText("banners[{$key}][position]", array_get($banner, 'position')) }}
        {{ Form::tbFormGroupClose() }}

        @include('admin.shared._model_image_field', ['model' => $banner, 'field' => 'image', 'scope' => ['banners', $key], 'label' => trans('validation.model_attributes.banner.image')])

        {{ Form::tbFormGroupOpen("banners.{$key}.link") }}
        {{ Form::tbLabel("banners[{$key}][link]", trans('validation.attributes.link')) }}
        {{ Form::tbText("banners[{$key}][link]", array_get($banner, 'link')) }}
        {{ Form::tbFormGroupClose() }}

        {{ Form::tbFormGroupOpen("banners.{$key}.description") }}
        {{ Form::tbLabel("banners[{$key}][description]", trans('validation.attributes.description')) }}
        {{ Form::tbTextarea("banners[{$key}][description]", array_get($banner, 'description')) }}
        {{ Form::tbFormGroupClose() }}

    </div>
</li>