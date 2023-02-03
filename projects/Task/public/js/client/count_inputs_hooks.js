(function ($, document, window) {
    $(function () {
        $(document).on('click', '.plus', function () {
            var input = $(this).parent().find('input');

            var val = parseInt(input.val(), 10);
            if (isNaN(val)) {
                val = 1;
            }

            input.val(val + 1);
            input.change();

            return false;
        });

        $(document).on('click', '.minus', function () {
            var input = $(this).parent().find('input');

            var val = parseInt(input.val(), 10);
            if (isNaN(val)) {
                val = 1;
            }

            var count = val - 1;

            count = count < 1 ? 1 : count;
            input.val(count);
            input.change();

            return false;
        });
    });
})(jQuery, document, window);

