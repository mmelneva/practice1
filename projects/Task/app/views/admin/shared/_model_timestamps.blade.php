{{-- Timestamps for form --}}

@if (isset($model->created_at))
    {{ Form::tbFormGroupOpen() }}
        <label>{{{ trans('validation.attributes.created_at') }}}</label><br/>
        {{{ out_datetime($model->created_at) }}}
    {{ Form::tbFormGroupClose() }}
@endif

@if (isset($model->updated_at))
    {{ Form::tbFormGroupOpen() }}
        <label>{{{ trans('validation.attributes.updated_at') }}}</label><br/>
        {{{ out_datetime($model->updated_at) }}}
    {{ Form::tbFormGroupClose() }}
@endif