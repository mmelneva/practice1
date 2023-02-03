$(document).ready(function () {
    window.setLocationHash = function (carousel, productId) {
        if (typeof productId != 'undefined') {
            var popupBlock;
            popupBlock = carousel.parents('[data-target-popup]').eq(0);
            if (popupBlock.length > 0) {
                location.hash = urlHash(popupBlock.data('targetPopup'), productId);
            }
        }
    };

    window.getPopupCarouselItem = function (carousel, productId, target) {
        if (typeof productId != 'undefined') {
            var currentItem, children, popupBlock, url, pageId;
            currentItem = carousel.find('[data-target-product="' + productId + '"]');
            if (currentItem.length == 0) {
                return;
            }
            children = currentItem.children();
            if (children.length > 0) {
                return;
            }
            popupBlock = currentItem.parents('.popup');
            if (popupBlock.length == 0) {
                return;
            }
            url = popupBlock.data('url');
            if (typeof url == 'undefined') {
                return;
            }

            pageId = popupBlock.data('pageId');
            if (typeof pageId == 'undefined') {
                pageId = '';
            }

            carousel.addClass('wait');
            $.ajax({
                //async: false,
                url: url,
                type: 'get',
                data: {product_id: productId, page_id: pageId, target: target},
                dataType: 'json',
                success: function (response) {
                    if (response['status'] == 'OK') {
                        currentItem.html(response['content']);
                    }
                    carousel.removeClass('wait');
                }
            });
        }
    };

    var urlHash = function (targetPopup, productId) {
        return 'product-info' + '/' + targetPopup + '-' + productId;
    };

    var bindControls = function (elem) {
        elem.parent().find('.jcarousel-control-prev').jcarouselControl({
            target: '-=1', //Одновременная прокрутка количества слайдов
            carousel: elem
        });

        elem.parent().find('.jcarousel-control-next').jcarouselControl({
            target: '+=1', //Одновременная прокрутка количества слайдов
            carousel: elem
        });

        elem.parents('.popup').find('.jcarousel-control-prev').on('click', function () {
            nextPrevPageLocation(elem, $(this), false);
        });

        elem.parents('.popup').find('.jcarousel-control-next').on('click', function () {
            nextPrevPageLocation(elem, $(this), true);
        });

        elem.parent().find('.jcarousel-pagination').on('jcarouselpagination:active', 'a', function () {
            $(this).addClass('active');
        })
            .on('jcarouselpagination:inactive', 'a', function () {
                $(this).removeClass('active');
            })
            .on('click', function (e) {
                e.preventDefault();
            })
            .jcarouselPagination({
                perPage: 4, //Одновременная прокрутка количества слайдов
                item: function (page) {
                    return '<a href="#' + page + '">' + page + '</a>';
                },
                carousel: elem
            });

        elem.parent().find('.jcarousel-wrapper.single .jcarousel-pagination').jcarouselPagination({
            'perPage': 1, //Одновременная прокрутка количества слайдов для одиночного слайдера
            carousel: elem
        });
    };

    $(document).on('jcarousel:reload jcarousel:create', '.jcarousel', function (event, carousel) {
        var currentCarousel = carousel._element;

        if (event.type == 'jcarousel:create') {
            bindControls(currentCarousel);
        }

        if (currentCarousel.hasClass('products-group-carousel')) {
            if (currentCarousel.parents('.product-container > div').is(':visible')) {
                setProductsCarouselItemsWidth(currentCarousel, true);
            }
        } else if (currentCarousel.hasClass('products-carousel')) {
            setProductsCarouselItemsWidth(currentCarousel, true);
        } else if (currentCarousel.hasClass('popup-carousel') && event.type == 'jcarousel:reload') {
            showPopupJcarouselControls(currentCarousel.parents('.jcarousel-wrapper').eq(0), "ul > li");
        }
    });

    $(document).on('jcarousel:targetin', '.popup-carousel li', function (event, carousel) {
        // "this" refers to the item element
        // "carousel" is the jCarousel instance
        var popupBlock = carousel._element.parents('[data-target-popup] .popup');
        if (popupBlock.length > 0 && popupBlock.css('display') != 'none') {
            var productId = $(this).data('targetProduct');
            var targetPopup = carousel._element.parents('[data-target-popup]').data('target-popup');
            setLocationHash(carousel._element, productId);
            getPopupCarouselItem(carousel._element, productId, targetPopup);
        }
    });

    window.jcarouselInit = function (element) {
        if (typeof element == 'undefined') {
            element = $('.jcarousel');
        }
        element.jcarousel({
            //wrap: 'circular', //Цикличность
            wrap: 'both', //Цикличность
            animation: 300
        });
    };

    jcarouselInit();

    function nextPrevPageLocation(carousel, controls, next) {
        if (!controls.hasClass('inactive')) {
            var targetIndex, itemsCount;
            targetIndex = carousel.jcarousel('target').index();
            itemsCount = carousel.jcarousel('items').length;

            var condition = false;
            if ((next && targetIndex == 0) ||
                (!next && targetIndex == (itemsCount - 1))
            ) {
                condition = true;
            }
            if (condition) {
                controls.addClass('inactive');
                var popup = carousel.parents('[data-target-popup]').eq(0);
                if (popup.length > 0) {
                    var url = controls.data('url');
                    if (url != '') {
                        carousel.addClass('wait');
                        window.location.href = url + '#' + urlHash(popup.data('targetPopup'), 0);
                    }
                }
            }
        }
    }

    function showPopupJcarouselControls(container, elem) {
        if (container.length > 0) {
            var elemCount, controlNext, controlPrev, controlNextUrl, controlPrevUrl;
            elemCount = container.find(elem).length;
            controlNext = container.find('.jcarousel-control-next');
            controlPrev = container.find('.jcarousel-control-prev');

            if (elemCount < 2) {
                controlNextUrl = controlNext.data('url');
                controlPrevUrl = controlPrev.data('url');

                if (controlNextUrl == '') {
                    controlNext.css('display', 'none');
                }
                if (controlPrevUrl == '') {
                    controlPrev.css('display', 'none');
                }
            } else {
                controlNext.css('display', 'block');
                controlPrev.css('display', 'block');
            }
        }
    }

    function setProductsCarouselItemsWidth(carousel, setControls) {
        var container, itemElem, additionalWidth, itemOuterWidth, shownItemsCount;
        container = carousel.parents('.products-wrapper').eq(0);
        if (setControls && container.length > 0) {
            container.removeClass('is-products-carousel');
        }
        itemOuterWidth = carousel.innerWidth();

        if (itemOuterWidth >= 1440) {
            shownItemsCount = 6;
        } else if (itemOuterWidth >= 1200) {
            shownItemsCount = 5;
        } else if (itemOuterWidth >= 928) {
            shownItemsCount = 4;
        } else if (itemOuterWidth >= 720) {
            shownItemsCount = 3;
        } else if (itemOuterWidth >= 480) {
            shownItemsCount = 2;
        } else {
            shownItemsCount = 1;
        }

        if (setControls) {
            showProductsJcarouselControls(container, carousel, shownItemsCount);
        } else {
            itemElem = carousel.jcarousel('items').eq(0);
            additionalWidth = parseInt(itemElem.outerWidth(true)) - parseInt(itemElem.width());
            itemOuterWidth = itemOuterWidth / shownItemsCount;

            carousel.jcarousel('items').css('width', Math.ceil(itemOuterWidth) - additionalWidth + 'px');
        }
    }

    function showProductsJcarouselControls(container, carousel, shownItemsCount) {
        if (container.length > 0) {
            var totalItemsCount, controls;
            totalItemsCount = carousel.jcarousel('items').length;
            controls = container.find('.jcarousel-controls');
            if (shownItemsCount >= totalItemsCount) {
                container.removeClass('is-products-carousel');
                controls.css('display', 'none');
            } else {
                if (shownItemsCount > 1) {
                    if (!container.hasClass('is-products-carousel')) {
                        container.addClass('is-products-carousel');
                    }
                }
                controls.css('display', 'block');
            }
            setProductsCarouselItemsWidth(carousel, false);
        }
    }

    function showJcarouselControls(container, elem, user_function) {
        if (container.length > 0) {
            var containerWidth, jcarouselBlock, liElem, controls, liWidth, liCount, count = 0;
            liElem = container.find(elem);
            liCount = liElem.length;
            jcarouselBlock = container.find('.jcarousel');
            controls = container.find('.jcarousel-controls');
            if (liCount > 0) {
                containerWidth = jcarouselBlock.innerWidth();
                liWidth = liElem.outerWidth(true);
                count = Math.ceil(containerWidth / liWidth);
            }
            if (count >= liCount) {
                controls.css('display', 'none');
                if (user_function != '') {
                    user_function(jcarouselBlock, false);
                }
            } else {
                controls.css('display', 'block');
                if (user_function != '') {
                    user_function(jcarouselBlock, true);
                }
            }
        }
    }
});