(function ($) {
    $(function () {
        window.numericInit = function () {
            $('[data-numeric]').numeric();
            $('[data-positive-integer]').numeric({ decimal: false, negative: false });
        };

        numericInit();
    });
})(jQuery);
