(function ($) {
    $(function () {
        var pagesAssociationContainer;

        pagesAssociationContainer = $('#product-type-page-container');

        pagesAssociationContainer.on('click', '.product-type-page .element-checkbox:checked + .product-name, .filtered .product-name', function () {
            var assocBlockToggle, assocBlock, process;

            assocBlockToggle = $(this);
            assocBlock = assocBlockToggle.next('.association');

            if (assocBlock.length === 0) {
                process = $('<span class="process glyphicon glyphicon-refresh"></span>');
                process.insertAfter(assocBlockToggle);
                $.ajax({
                    cache: false,
                    url: assocBlockToggle.data('associationUrl')
                }).then(function (result) {
                    process.remove();
                    assocBlock = $(result);
                    assocBlock.insertAfter(assocBlockToggle);
                    assocBlock.toggleClass('visible');
                });
            } else {
                assocBlock.toggleClass('visible');
            }
        }).on('change', '.product-type-page .element-checkbox', function () {
            if (!this.checked) {
                $(this).next('.product-name').next('.association').removeClass('visible');
            }
        });

    });
})(jQuery);
