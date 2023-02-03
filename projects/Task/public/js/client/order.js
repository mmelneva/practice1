(function ($, document, window) {
    (function () {
        var orderForm = $('form#order');
        var productIdInput = orderForm.find('input[name="product_id"]');
        var orderPopup = $(document).find('#poporder .popup');
        var orderPopupType = $(document).find('#poporder').attr('data-type');
        var pop_quick_targettype;

        if (orderForm.length == 1) {
            $(document).on('click', '.pop_order', function (e) {
                e.preventDefault();
                var productId = $(this).data('productId');
                if (typeof productId != 'undefined' && productId != '') {
                    if(orderPopupType == 'productpage'){
                        countersListEvent('PRODUCT_PAGE_WANT_THE SAME_WINDOW_SHOW');
                    }
                    showOrderPopup(productId);
                }
            });

            function showOrderPopup(productId) {
                orderForm.show();
                orderPopup.find('.section.nomarg div').show();
                setPopupLocation(orderPopup);
                validateOrderForm.resetForm();
                orderForm.find('.errors').removeClass('errors');
                orderPopup.find('.errors').remove();
                orderPopup.find('.success').remove();
                orderForm.get(0).reset();
                productIdInput.val(productId);

                orderForm
                    .find('.privacy input[type="checkbox"]')
                    .prop('checked', true)
                    .removeClass('error');
                orderForm.find('#privacy-error').remove();


                $('#poporder .popup, #poporder .bg0').fadeIn();
            }

            $('#poporder .close, #poporder .bg0').click(function () {
                $('#poporder .popup:visible, #poporder .bg0:visible').fadeOut();
                if($(this).hasClass('close')){

                    var data = $('#poporder').attr('data-type');
                    if(data=='homepage'){
                        if(pop_quick_targettype=='last') {
                            countersListEvent('MAINPAGE_WANT_THE_SAME_POPUP_CLOSE');
                        }
                    }else if(data=='productpage'){
                        countersListEvent('PRODUCT_PAGE_WANT_THE_SAME_POPUP_CLOSE');
                    }else if(data=='categorypage'){
                        countersListEvent('CATALOG_WANT_THE_SAME_POPUP_CLOSE');
                    }else {
                        console.log(data);
                    }

                }
                return false;
            });

            var validateOrderForm = orderForm.validate({
                rules: {
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
                    privacy: {
                        required: "Необходимо отметить"
                    }
                },
                errorClass: "errors",
                errorPlacement: function (error, element) {
                    error.insertAfter(element);

                    var labelerror = error.html();
                    var datatype = $('#poporder').attr('data-type');

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
