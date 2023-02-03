<div>
    @if (isset($resource->id))
        {{ Form::tbLabel('id', trans('validation.attributes.callback_number')) }}:
        {{ $resource->id }}
    @endif
</div>
