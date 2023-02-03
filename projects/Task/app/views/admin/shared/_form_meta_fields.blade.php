{{-- Meta fields for form --}}

{{ Form::tbFormGroupOpen('meta_title') }}
    {{ Form::tbLabel('meta_title', trans('validation.attributes.meta_title')) }}
    {{ Form::tbText('meta_title') }}
{{ Form::tbFormGroupClose() }}

{{ Form::tbFormGroupOpen('meta_description') }}
    {{ Form::tbLabel('meta_description', trans('validation.attributes.meta_description')) }}
    {{ Form::tbText('meta_description') }}
{{ Form::tbFormGroupClose() }}

{{ Form::tbFormGroupOpen('meta_keywords') }}
    {{ Form::tbLabel('meta_keywords', trans('validation.attributes.meta_keywords')) }}
    {{ Form::tbText('meta_keywords') }}
{{ Form::tbFormGroupClose() }}
