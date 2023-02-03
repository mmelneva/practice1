(function ($, document, window) {
    (function () {
        var measurementForm = $('form#measurement');
        var productIdInput = measurementForm.find('input[name="product_id"]');
        var measurementPopup = $(document).find('#popmeasurement .popup');
        var measurementPopupType = $(document).find('#popmeasurement').attr('data-type');
        var pop_quick_targettype;

        if (measurementForm.length == 1) {
            $(document).on('click', '.pop_measurement', function (e) {
                e.preventDefault();
                showMeasurementPopup();
            });

            function showMeasurementPopup() {
                measurementForm.show();
                measurementPopup.find('.section.nomarg div').show();
                setPopupLocation(measurementPopup);
                validateMeasurementForm.resetForm();
                measurementForm.find('.errors').removeClass('errors');
                measurementPopup.find('.errors').remove();
                measurementPopup.find('.success').remove();
                measurementForm.get(0).reset();

                measurementForm
                    .find('.privacy input[type="checkbox"]')
                    .prop('checked', true)
                    .removeClass('error');
                measurementForm.find('#privacy-error').remove();


                $('#popmeasurement .popup, #popmeasurement .bg0').fadeIn();
            }

            $('#popmeasurement .close, #popmeasurement .bg0').click(function () {
                $('#popmeasurement .popup:visible, #popmeasurement .bg0:visible').fadeOut();
                return false;
            });

            var validateMeasurementForm = measurementForm.validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    phone: {
                        required: true,
                        phoneNumber: true
                    },
                    email: {
                        email: true
                    },
                    privacy: {
                        required: true
                    }
                },
                messages: {
                    name: {
                        required: "Введите имя"
                    },
                    privacy: {
                        required: "Необходимо отметить"
                    }
                },
                errorClass: "errors",
                errorPlacement: function (error, element) {
                    error.insertAfter(element);

                    var labelerror = error.html();
                    var datatype = $('#popmeasurement').attr('data-type');

                    if (labelerror == 'Это поле необходимо заполнить.') {
                        if (datatype == 'homepage') {
                            countersListEvent('MAINPAGE_WANT_THE_SAME_POPUP_SET_THE_FIELD');
                        } else if (datatype == 'productpage') {
                            countersListEvent('PRODUCT_PAGE_WANT_THE_SAME_POPUP_SET_THE_FIELD');
                        } else if (datatype == 'categorypage') {
                            countersListEvent('CATALOG_WANT_THE_SAME_POPUP_SET_THE_FIELD');
                        } else {
                            console.log(datatype + ';1 ' + labelerror);
                        }

                    } else if (labelerror == 'Укажите корректный номер телефона.') {
                        if (datatype == 'homepage') {
                            countersListEvent('MAINPAGE_WANT_THE_SAME_POPUP_SET_THE_CORRECT_PHONE');
                        } else if (datatype == 'productpage') {
                            countersListEvent('PRODUCT_PAGE_WANT_THE_SAME_POPUP_SET_THE_CORRECT_PHONE');
                        } else if (datatype == 'categorypage') {
                            countersListEvent('CATALOG_WANT_THE_SAME_POPUP_SET_THE_CORRECT_PHONE');
                        } else {
                            console.log(datatype + ';2 ' + labelerror);
                        }
                    } else {
                        console.log(datatype + ';3 "' + labelerror + '"');
                    }

                },
                submitHandler: function (form) {
                    var formBlock = $(form);
                    var parentBlock = formBlock.parent();
                    parentBlock.addClass('wait');
                    $.ajax({
                        //async: false,
                        url: formBlock.data('action'),
                        type: 'post',
                        data: formBlock.serialize(),
                        dataType: 'json',
                        success: function (response) {
                            formSubmitHandler(response, formBlock, parentBlock);
                        }
                    });
                    return false;
                }
            });
        }
    })();

})(jQuery, document, window);
