{{-- Alerts --}}

@if (!empty($alert_success))
    <div class="alert alert-success">
        {{{ $alert_success }}}
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    </div>
@endif

@if (!empty($alert_error))
    <div class="alert alert-danger">
        {{{ $alert_error }}}
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    </div>
@endif