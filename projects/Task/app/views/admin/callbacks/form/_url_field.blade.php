@if (isset($resource->url_referer))
{{ Form::tbFormGroupOpen('url_referer') }}
    {{ Form::tbLabel('url_referer', trans('validation.model_attributes.callback.url_referer')) }}<br/>
    @if(!empty($resource->url_referer))
    <a href="{{ $resource->url_referer }}" target="_blank">
    {{ $resource->url_referer }}
    </a>
    @endif
{{ Form::tbFormGroupClose() }}
@endif