(function ($) {
    $(function () {
        var productAssociationContainer, productTypeTabs, filterInput,
            filterInputInitialValue, updateProductsContainer, filteredProductsList;

        productAssociationContainer = $('#product-association-container');


        productAssociationContainer.on('click', '.manual .element-checkbox:checked + .product-name, .filtered .product-name', function () {
            var assocBlockToggle, assocBlock, process;

            assocBlockToggle = $(this);
            assocBlock = assocBlockToggle.next('.association');

            if (assocBlock.length === 0) {
                process = $('<span class="process glyphicon glyphicon-refresh"></span>');
                process.insertAfter(assocBlockToggle);
                $.ajax({
                    cache: false,
                    url: assocBlockToggle.data('productAssociationUrl')
                }).then(function (result) {
                    process.remove();
                    assocBlock = $(result);
                    assocBlock.insertAfter(assocBlockToggle);
                    assocBlock.toggleClass('visible');
                });
            } else {
                assocBlock.toggleClass('visible');
            }
        }).on('change', '.manual .element-checkbox', function () {
            if (!this.checked) {
                $(this).next('.product-name').next('.association').removeClass('visible');
            }
        });


        productTypeTabs = $('#product-type-tabs');
        productTypeTabs.on('change', '[name="product_list_way"]', function () {
            var value = this.value;

            productTypeTabs.find('.product-association-container .tab').each(function () {
                if (this.id == 'product-association-' + value) {
                    $(this).addClass('active');
                } else {
                    $(this).removeClass('active');
                }
            });
        });


        // Show hide filtered product list
        productAssociationContainer.on('click', '.show-hide-products', function () {
            $(this).next('.products').toggleClass('visible');
        });


        // Update filtered products

        filterInput = productAssociationContainer.find('#filter_query');
        filterInputInitialValue = filterInput.val();
        updateProductsContainer = productAssociationContainer.find('.update-products-container');
        filteredProductsList = productAssociationContainer.find('.products');

        updateProductsContainer.find('a').click(function (e) {
            var filterValue = filterInput.val();
            e.preventDefault();

            filteredProductsList.html('<span class="glyphicon glyphicon-refresh"></span>');
            $.ajax({
                cache: false,
                url: this.href,
                data: {filter_string: filterValue}
            }).then(function (result) {
                filteredProductsList.html($(result));
                updateProductsContainer.removeClass('visible');
                filterInputInitialValue = filterValue;
            });
        });

        filterInput.keyup(function () {
            if (filterInput.val() == filterInputInitialValue) {
                updateProductsContainer.removeClass('visible');
            } else {
                updateProductsContainer.addClass('visible');
            }
        });

        (function () {
            var productTypePageForm = $('form#product-type-page');
            if (productTypePageForm.length > 0) {
                var popularContainer = productTypePageForm.find('#popular-container');

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

                toggleContainer(popularContainer, productTypePageForm.find('[name="in_popular"]'));

                productTypePageForm.on('change', '[name="in_popular"]', function (e) {
                    toggleContainer(popularContainer, $(this));
                });
            }
        })();
    });
})(jQuery);