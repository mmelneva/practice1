(function ($) {
    $(function () {

        // Product attributes
        (function () {
            var container = $('.additional-attributes');

            container.each(function () {
                var additionalAttributes = $(this);
                var disabledAttributesContainer = additionalAttributes.find('.disabled-attributes');

                var toggleInputsDisabled = function () {
                    var inputs = disabledAttributesContainer.find('input,select');

                    if (disabledAttributesContainer.is(':visible')) {
                        inputs.show();
                    }
                };

                additionalAttributes.on('click', '.toggle-other', function () {
                    var inputs = disabledAttributesContainer.find('input[type="checkbox"]');
                    inputs.hide();

                    disabledAttributesContainer.slideToggle(400, toggleInputsDisabled);
                });

                toggleInputsDisabled();
            });
        })();

        (function() {
            var attributeForm = $('form#additional-attribute');
            if (attributeForm.length > 0) {
                var similarProductsNameBlock = attributeForm.find('input[name="similar_products_name"]').parents('.form-group');

                var toggleContainer = function (container, checkbox) {
                    if(checkbox.length > 0) {
                        var allowedInputs = container.find('input');
                        if (checkbox.is(":checked")) {
                            container.show();
                            allowedInputs.prop('disabled', false);
                        } else {
                            container.hide();
                            allowedInputs.prop('disabled', true);
                        }
                    }
                };

                toggleContainer(similarProductsNameBlock, attributeForm.find('[name="use_in_similar_products"]'));

                attributeForm.on('change', '[name="use_in_similar_products"]', function (e) {
                    toggleContainer(similarProductsNameBlock, $(this));
                });
            }
        })();

    });
})(jQuery);
