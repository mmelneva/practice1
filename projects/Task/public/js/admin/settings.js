(function ($) {
    $(function () {
        $('.settings-table').on('click', '.toggle', function () {
            var jThis = $(this);
            jThis.parents('.settings-table').eq(0).find('.settings-group[data-group-id="' + jThis.data('groupId') + '"]').toggleClass('group-show');
        });
    });
})(jQuery);