(function ($) {
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    // Add search functionality for select inputs
    window.initSelects2 = function() {
        $('select[data-with-search]').select2(
            {
                theme: "bootstrap",
                language: "ru"
            }
        );
    };

    initSelects2();
})(jQuery);
