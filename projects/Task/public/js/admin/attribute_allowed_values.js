(function ($) {
    $(function () {

        // Attribute allowed values: change type
        (function () {
            var attributeForm = $('#additional-attribute');
            var allowedValuesContainer = attributeForm.find('#allowed-values-container');

            var toggleAllowedValuesContainer = function (selectInput) {
                if (selectInput === undefined) {
                    selectInput = attributeForm.find('select#type');
                }

                var allowedValuesInputs = allowedValuesContainer.find('input,select');

                if ($.inArray(selectInput.val(), selectInput.data('withAllowedValues')) === -1) {
                    allowedValuesContainer.hide();
                    allowedValuesInputs.prop('disabled', true);
                } else {
                    allowedValuesContainer.show();
                    allowedValuesInputs.prop('disabled', false);
                }
            };

            toggleAllowedValuesContainer();

            attributeForm.on('change', 'select#type', function (e) {
                var selectInput = $(this);
                toggleAllowedValuesContainer(selectInput);
            });
        })();

        // Attribute allowed values: add/remove
        (function () {
            var additionalAttributeForm = $('#additional-attribute');
            var allowedValuesContainer = additionalAttributeForm.find('.attribute-allowed-values .values-container');

            additionalAttributeForm.on('click', '[data-purpose="add"]', function (e) {
                if (!window.addBlock) {
                    window.addBlock = true;
                    $.ajax({
                        cache: false,
                        type: this.dataset.method,
                        dataType: 'json',
                        data: {'key': 0},
                        url: this.dataset.url,
                        success: function (response) {
                            allowedValuesContainer.append(response['element']);

                            window.addBlock = false;
                        }
                    });
                }
            });

            additionalAttributeForm.on('click', '[data-purpose="remove"]', function (e) {
                $(this).parent().remove();
            });
        })();
    })
})(jQuery);
