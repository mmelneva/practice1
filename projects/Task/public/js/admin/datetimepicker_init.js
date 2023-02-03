(function ($) {
    $(function () {

        window.datetimepickerInit = function () {
            $('[data-datetimepicker]').datetimepicker(
                {
                    lang: 'ru',
                    format: 'd.m.Y H:i:s',
                    scrollInput: false
                }
            );

            $('[data-datepicker]').datetimepicker(
                {
                    lang: 'ru',
                    format: 'd.m.Y',
                    scrollInput: false,
                    timepicker: false
                }
            );

            $('[data-datetimepicker-noyear]').datetimepicker(
                {
                    lang: 'ru',
                    format: 'd.m H:i',
                    scrollInput: false,
                    step: 5,
                    defaultTime: '00:00'
                }
            );

            $('[data-datepicker-noyear]').datetimepicker(
                {
                    lang: 'ru',
                    format: 'd.m',
                    scrollInput: false,
                    timepicker: false
                }
            );
        };

        datetimepickerInit();
    });
})(jQuery);
