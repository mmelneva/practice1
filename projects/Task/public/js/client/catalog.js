(function ($, document, window) {
    $(document).on('change', '#catalog_sort', function () {
        var form, sortSelect;
        form = $('#catalog_filter');
        sortSelect = $(this);

        form.find('[name="sort"]').val(sortSelect.val());
        form.submit();
    });
})(jQuery, document, window);