(function ($, document) {
    /**
     * Set cookies.
     *
     * @param name
     * @param value
     */
    function setCookie(name, value) {
        var expiresDate = new Date(), expiresDateString;

        expiresDate.setDate(expiresDate.getDate() + 365);
        expiresDateString = expiresDate.toUTCString();
        document.cookie = name + '=' + value + '; path=/; expires=' + expiresDateString;
    }


    $(function () {
        // Main menu
        $('.menu-column')
            .on('click', '.close-menu', function () {
                $(this).parents('.menu-column').eq(0).addClass('closed');
            })
            .on('click', '.open-menu', function () {
                $(this).parents('.menu-column').eq(0).removeClass('closed');
            });

        $(document).on('click', '.element-group-wrapper .menu-element', function () {
            $(this).parent('.element-group-wrapper').toggleClass('active');
        });



        // Additional menu
        $('.additional-menu + .additional-menu-resize').each(function () {
            var menu, menuResize, cookieKey;

            menuResize = $(this);
            menu = menuResize.prev();
            cookieKey = menu.data('menu-resize');

            menuResize.on('mousedown', function () {
                $('html').addClass('no-user-select');

                var mouseMove = function (e) {
                    var newWidth = e.clientX - menu.offset().left;
                    menu.css({width: newWidth + 'px'});
                };

                var mouseUp = function () {
                    $(document).off('mousemove', mouseMove);
                    $(document).off('mouseup', mouseUp);
                    $('html').removeClass('no-user-select');
                    setCookie(cookieKey, menu.outerWidth());
                };

                $(document).on('mousemove', mouseMove);
                $(document).on('mouseup', mouseUp);
            });
        });
    });
})(jQuery, document);