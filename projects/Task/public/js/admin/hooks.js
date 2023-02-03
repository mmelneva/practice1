(function ($, document) {
    $(function () {
        // Confirmation hook
        $(document).on('click', '[data-confirm]', function (e) {
            if (!confirm($(this).data('confirm'))) {
                e.preventDefault();
                e.stopImmediatePropagation();
            }
        });

        // Toggle flags in list hook
        $(document).on('click', 'a.toggle-flag', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            var currentLink = $(this), container;
            container = currentLink.parent();
            currentLink.remove();
            container.html('<span class="glyphicon glyphicon-refresh"></span>');
            $.ajax({
                url: this.href,
                type: currentLink.data('method'),
                success: function (result) {
                    container.replaceWith(result['new_icon']);
                }
            });
        });

        // REST for links (DELETE, PUT)
        $(document).on('click', 'a[data-method]', function (e) {
            e.preventDefault();

            var form = document.createElement("form");
            form.setAttribute("method", 'post');
            form.setAttribute("action", this.href);

            var methodField = document.createElement("input");
            methodField.setAttribute("type", "hidden");
            methodField.setAttribute("name", "_method");
            methodField.setAttribute("value", $(this).data('method'));

            form.appendChild(methodField);

            document.body.appendChild(form);
            form.submit();
        });

        $(document).on('submit', 'form', function (e) {
            var form = $(this);
            form.find('[type="submit"]').each(function() {
                $(this).attr('type', 'button');
            });
        });
    });
})(jQuery, document);
