{{-- Toggle flag functional (AJAX) --}}
<div class="{{{ $attribute }}}-status toggle-flag-container" {{ isset($title) ? "title='{$title}'" : '' }}>
    @if (isset($disable_toggle) && $disable_toggle)
        @if ($element->{$attribute})
            <span class="glyphicon glyphicon-ok-circle toggle-flag"></span>
        @else
            <span class="glyphicon glyphicon-ban-circle toggle-flag"></span>
        @endif
    @else
        @if ($element->{$attribute})
            <a class="glyphicon glyphicon-ok-circle toggle-flag" href="{{{ $action }}}" data-method="put"></a>
        @else
            <a class="glyphicon glyphicon-ban-circle toggle-flag" href="{{{ $action }}}" data-method="put"></a>
        @endif
    @endif
</div>