<div class="image-fields-container">
    {{ Form::tbFormGroupOpen("images.{$image->id}.name") }}
        {{ Form::tbLabel("images[{$image->id}][name]", trans('validation.attributes.name')) }}
        {{ Form::tbText("images[{$image->id}][name]", array_get($image, 'name')) }}
    {{ Form::tbFormGroupClose() }}

    {{ Form::tbFormGroupOpen("images.{$image->id}.publish") }}
            {{ Form::tbStateCheckbox("images[{$image->id}][publish]", trans('validation.attributes.publish'), array_get($image, 'publish', 1)) }}
    {{ Form::tbFormGroupClose() }}

    {{ Form::tbFormGroupOpen("images.{$image->id}.position") }}
        {{ Form::tbLabel("images[{$image->id}][position]", trans('validation.attributes.position')) }}
        {{ Form::tbText("images[{$image->id}][position]", array_get($image, 'position')) }}
    {{ Form::tbFormGroupClose() }}
</div>