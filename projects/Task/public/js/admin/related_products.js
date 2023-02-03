$(function () {

        // Products tree
        (function(){
            var toggleAdditionalFields;

            // Show-hide handlers
            $(document).on('click', '.related-products .list-toggle', function () {
                $(this).parents('.related-products').eq(0).find('.list-container').eq(0).toggleClass('visible');
            });

            /**
             * Load part of catalog tree
             * @param parentContainer
             */
            var loadSubTrees = function (parentContainer) {
                parentContainer.find('.category-content:not(.loaded)').each(function () {
                    var categoryContentContainer = $(this);
                    var categoryToggleElement = $(this).siblings('.category-toggle');

                    var ajaxLoader = categoryContentContainer.siblings('.loader').first();

                    if (categoryToggleElement.length) {
                        var actionUrl = categoryToggleElement.data('url');
                        var actionMethod = categoryToggleElement.data('method');

                        if (actionUrl && actionMethod) {
                            ajaxLoader.addClass('glyphicon-refresh');
                            $.ajax({
                                async: false,
                                cache: false,
                                url: actionUrl,
                                type: actionMethod,
                                success: function (result) {
                                    categoryContentContainer.html(result['catalog_sub_tree_content']);
                                    categoryContentContainer.addClass('loaded');
                                    loadSubTrees(categoryContentContainer);
                                    ajaxLoader.removeClass('glyphicon-refresh');
                                }
                            });
                        }
                    }
                });
            };

            $(document).on('click', '.related-products .category .category-toggle', function (e) {
                var self = $(this);
                var parentContainer = self.parents('.category').eq(0);
                var categoryContentContainer = parentContainer.find('.category-content').eq(0);

                categoryContentContainer.toggleClass('visible');

                if (categoryContentContainer.is(':visible') && !categoryContentContainer.hasClass('loaded')) {

                    var ajaxLoader = self.siblings('.loader').first();

                    var actionUrl = self.data('url');
                    var actionMethod = self.data('method');

                    if (actionUrl && actionMethod) {
                        ajaxLoader.addClass('glyphicon-refresh');
                        $.ajax({
                            cache: false,
                            url: actionUrl,
                            type: actionMethod,
                            success: function (result) {
                                categoryContentContainer.html(result['catalog_sub_tree_content']);
                                categoryContentContainer.addClass('loaded');
                                initElementsTree(parentContainer);
                                ajaxLoader.removeClass('glyphicon-refresh');
                            }
                        });
                    }
                }
            });

            /**
             * Get product checkboxes for category
             * @param categorySelect
             * @param checked
             * @returns {*|jQuery}
             */
            var getProductCheckboxes = function (categorySelect, checked) {
                var selector;
                checked = checked || false;
                if (checked) {
                    selector = '.category-content input[type="checkbox"]:checked:not(.category-select, .not-togglable)';
                } else {
                    selector = '.category-content input[type="checkbox"]:not(.category-select, .not-togglable)';
                }
                return $(categorySelect).parents('.category').eq(0).find(selector);
            };

            /**
             * Update category checkboxes, which are parents of current element
             * @param currentElement
             */
            var updateCategoryCheckboxes = function (currentElement) {
                $(currentElement).parents('.category').each(function () {
                    $(this).find('.category-select').each(function () {
                        var categorySelect = this, childrenCheckboxes, childrenCheckedCheckboxes;

                        childrenCheckboxes = getProductCheckboxes(this);
                        childrenCheckedCheckboxes = getProductCheckboxes(this, true);

                        var categoryHasLoadedSubCategory = function (categorySelect) {
                            var parentContainer = $(categorySelect).parents('.category').eq(0);

                            var allLength = parentContainer.find('.category-content').length;
                            var loadedLength = parentContainer.find('.category-content.loaded').length;

                            return allLength == loadedLength;
                        };

                        if (childrenCheckboxes.length !== 0 &&
                            childrenCheckedCheckboxes.length == childrenCheckboxes.length) {

                            if (categoryHasLoadedSubCategory(categorySelect)) {
                                categorySelect.checked = true;
                                categorySelect.indeterminate = false;
                            } else {
                                categorySelect.checked = false;
                                categorySelect.indeterminate = true;
                            }

                        } else if (childrenCheckedCheckboxes.length === 0) {
                            categorySelect.checked = false;
                            categorySelect.indeterminate = false;
                        } else {
                            categorySelect.checked = false;
                            categorySelect.indeterminate = true;
                        }
                    });
                });
            };

            $('.related-products').on('change', '.category-select', function () {

                var ajaxLoader = $(this).siblings('.loader').first();

                ajaxLoader.addClass('glyphicon-refresh');
                if (this.checked) {
                    loadSubTrees($(this).parent());
                }
                ajaxLoader.removeClass('glyphicon-refresh');

                // Update product checkboxes when category checkbox is changed
                var checkBoxes = getProductCheckboxes(this);
                if (this.checked) {
                    checkBoxes.prop('checked', true);
                } else {
                    checkBoxes.removeAttr('checked');
                }

                updateCategoryCheckboxes(this);

                $.each(checkBoxes, function (i, checkBox) {
                    toggleAdditionalFields(checkBox);
                });

            }).on('change', 'input[type="checkbox"]', function () {
                updateCategoryCheckboxes(this);
                toggleAdditionalFields(this);
            });

            toggleAdditionalFields = function (checkBox) {
                if (!$(checkBox).hasClass('not-togglable')) {
                    var additionalFieldsContainer = $(checkBox).parents('li:first').find('.additional-fields');

                    if (additionalFieldsContainer.length > 0) {
                        if (checkBox.checked) {
                            additionalFieldsContainer.show();
                        } else {
                            additionalFieldsContainer.hide();
                        }
                    }
                }
            };

            window.initElementsTree = function (parentContainer) {
                var categoryElementList;
                if (parentContainer === undefined) {
                    var rootContainer = $('.related-products');

                    categoryElementList = rootContainer.find('.category-select');
                    rootContainer.find('.validation-errors').parents('.category-content').addClass('visible');
                } else {
                    categoryElementList = $(parentContainer).find('.category-select');
                }
                categoryElementList.each(function () {
                    updateCategoryCheckboxes(this);

                    var checkBoxes = getProductCheckboxes(this);
                    $.each(checkBoxes, function (i, checkBox) {
                        toggleAdditionalFields(checkBox);
                    });
                });
            };

            initElementsTree();
        })();

        // Show products tree
        (function(){
            $(document).on('click', 'a.show-products-tree', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                $(this).hide();

                var productsTreeWrapper = $(this).next();
                productsTreeWrapper.show();

                var container = $('.products-tree-container', productsTreeWrapper);

                if (container.length) {
                    $.ajax({
                        cache: false,
                        url: $(this).data('actionUrl'),
                        type: $(this).data('actionMethod'),
                        success: function (result) {
                            $('.products-tree-container', productsTreeWrapper).html(result['catalog_tree_content']);
                            initElementsTree();
                        }
                    });
                }
            });
        })();
    }
);