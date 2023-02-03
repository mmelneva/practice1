<div class="ip-container {{{ $errors->has('allowed_ips_' . $key) ? 'ip-has-error' : '' }}}">
    <input class="ip-control" autocomplete="off" type="text" name="allowed_ips[{{{ $key }}}]" value="{{{ $ip }}}" {{{ $key === '' ? 'disabled="disabled"' : '' }}} />
    <label>
        <input type="checkbox" class="toggle-ip-v6" {{{ strpos($ip, ':') !== false ? 'checked="checked"' : '' }}} /> ipv6
    </label>
    <a href="#" class="glyphicon glyphicon-trash remove-ip"></a>

    @if ($errors->has('allowed_ips_' . $key))
        {{ $errors->all('<div class="validation-errors">:message</div>')[0] }}
    @endif
</div>