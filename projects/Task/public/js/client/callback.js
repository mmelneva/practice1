(function ($, document, window) {
    (function () {
        var callbackForm = $('form#callback');
        var callbackPopup = $(document).find('#popcallback .popup');

        window.formSubmitHandler = function(response, formBlock, parentBlock)
        {
            parentBlock.find('.errors').remove();
            parentBlock.find('.success').remove();
            if (response['status'] == 'OK') {
                formBlock.get(0).reset();
                formBlock.before(response['content']);
                formBlock.hide();
                parentBlock.parent().find('.section.nomarg div').hide();
            } else {
                formBlock.before(response['content']);
            }
            parentBlock.removeClass('wait');
        };

        if (callbackForm.length == 1) {
            $('.pop_callback').click(function (e) {
                e.preventDefault();
                if($(this).hasClass('footer')){
                    //3) Клик по ссылке «Заказать обратный звонок» в футере сайта
                    countersListEvent('FOOTER_POP_CALLBACK_CLICK');
                }else{
                    //2) Клик по ссылке «Заказать обратный звонок» в шапке сайта HEADER_POP_CALLBACK_CLICK
                    countersListEvent('HEADER_POP_CALLBACK_CLICK');
                }

                showCallbackPopup();
            });

            function showCallbackPopup() {
                callbackForm.show();
                callbackPopup.find('.section.nomarg div').show();
                setPopupLocation(callbackPopup);
                validateCallbackForm.resetForm();
                callbackForm.find('.errors').removeClass('errors');
                callbackPopup.find('.errors').remove();
                callbackPopup.find('.success').remove();
                callbackForm.get(0).reset();

                callbackForm
                    .find('.privacy input[type="checkbox"]')
                    .prop('checked', true)
                    .removeClass('error');
                callbackForm.find('#privacy-error').remove();

                $('#popcallback .popup, #popcallback .bg0').fadeIn();
            }

            $('#popcallback .close, #popcallback .bg0').click(function () {
                $('#popcallback .popup:visible, #popcallback .bg0:visible').fadeOut();
                return false;
            });

            var validateCallbackForm = callbackForm.validate({
                rules: {
                    name: {
                        required: true
                    },
                    phone: {
                        required: true,
                        phoneNumber: true
                    },
                    privacy: {
                        required: true
                    }
                },
                messages: {
                    privacy: {
                        required: "Необходимо отметить"
                    }
                },
                errorClass: "errors",
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
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

        $(document).on('click', "form#contacts input[type=submit]",  function() {
            countersListEvent('CONTACTS_PAGE_SUBMIT_BUTTON_CLICK');
        });

        $.each(['contacts'], function () {
            var formSelector = 'form#' + this;
            $(formSelector).validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    phone: {
                        required: true,
                        phoneNumber: true
                    },
                    privacy: {
                        required: true
                    }
                },
                messages: {
                    privacy: {
                        required: "Необходимо отметить"
                    }
                },
                errorClass: "errors",
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
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

                            //Удачная отправка сообщения с контактов
                            if($(form).attr('id')=='contacts'){
                                countersListEvent('CONTACTS_PAGE_SUBMIT_SUCCESS_CLICK');
                            }
                        }
                    });
                    return false;
                }
            });
        });
    })();

})(jQuery, document, window);
