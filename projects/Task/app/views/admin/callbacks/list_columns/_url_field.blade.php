<div class="{{{ $column['field'] }}}">
    @if(!empty($resource->{$column['field']}))
    <a href="{{{ $resource->{$column['field']} }}}" target="_blank">
        {{{ $resource->{$column['field']} }}}
    </a>
    @endif
</div>