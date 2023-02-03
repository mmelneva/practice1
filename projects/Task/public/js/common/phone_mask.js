(function ($) {
    $(function () {

        $('[data-phone-mask]').inputmask('+7 (999) 9999999', {
            clearMaskOnLostFocus: false
        });

        $('[data-client-phone-mask]').inputmask('+7 (999) 999-99-99', {
            clearMaskOnLostFocus: false
        });
    });
})(jQuery);
