<link rel="stylesheet" href="{{{ route('laravel-sweet-tooltip.assets.css') }}}"/>
<script src="{{{ route('laravel-sweet-tooltip.assets.js') }}}"></script>
<script>
    $(function () {
         window.sweetTooltip = new SweetTooltip({{ json_encode(Config::get('laravel-sweet-tooltip::rules')) }});
    });
</script>