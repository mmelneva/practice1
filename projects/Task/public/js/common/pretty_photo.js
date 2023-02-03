(function ($, window) {
    $(function () {
        window.prettyPhotoInit = function() {
            $("a[rel^='prettyPhoto']").prettyPhoto({
                social_tools: false,
                show_title: false
            });
        };

        prettyPhotoInit();
    });
})(jQuery, window);