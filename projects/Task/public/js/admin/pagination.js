(function ($, document) {
    $(function () {
        $(document).on('change', '.choose-on-page', function () {
            window.location.href = this.value;
        });
    });
})(jQuery, document);