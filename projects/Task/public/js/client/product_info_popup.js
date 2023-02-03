$(document).ready(function () {
    (function () {
        var showPopupQuick = function (productId, targetPopup) {
            var popup = $('[data-target-popup="' + targetPopup + '"]');
            var currentCarousel = popup.find('.popup-carousel');
            if (currentCarousel.length > 0) {

                $('[data-target-popup="' + targetPopup + '"] .popup, [data-target-popup="' + targetPopup + '"] .bg0')
                    .fadeIn(400, function() {
                        setPopupLocation(popup.find('.popup'));
                    });
                scrollToCarouselElement(currentCarousel, productId, 'target');
                setLocationHash(currentCarousel, productId);
                getPopupCarouselItem(currentCarousel, productId, targetPopup);

                currentCarousel.jcarousel('reload');
            }
        };

        //Окно 'Быстрый просмотр'
        $(document).on('click', '.pop_quick', function () {
            var productBlock, target;

            var targettype = pop_quick_targettype =$(this).parents('ul.catalog').attr('data-source-popup');

            if(targettype=='list'){
                countersListEvent('CATALOG_QUICK_VIEW_CLICK');
            }else if(targettype=='similar10'){
                countersListEvent('PRODUCT_PAGE_SIMILAR_QUICK_VIEW_CLICK');
            }else if(targettype=='last'){
                // Главная страница => Блок «последние работы» => Клик по кнопке «Быстрый просмотр» на изображении
                countersListEvent('MAINPAGE_LASTWORK_QUICK_VIEW_CLICK');
            }else if(targettype=='review' && !$('body').hasClass('ins')){
                //Отзывы на главной странице -> быстрый просмотр.
                countersListEvent('MAINPAGE_REVIEW_QUICK_VIEW_CLICK');
            }

            productBlock = $(this).parents('[data-source-product]').eq(0);
            target = productBlock.parents('[data-source-popup]').eq(0);
            if (target.length > 0) {
                showPopupQuick(productBlock.data('sourceProduct'), target.data('sourcePopup'));
                if(targettype=='productpage'){
                    countersListEvent('PRODUCT_PAGE_WANT_THE SAME_WINDOW_SHOW');
                };
            }

            return false;
        });

        $(document).on('click', '[data-target-popup] .close, [data-target-popup] .bg0', function () {
            $('[data-target-popup] .popup:visible, [data-target-popup] .bg0:visible').fadeOut();

            var locationHash = getLocationInfo();
            if (locationHash != null) {
                var currentElement = $('[data-source-popup="' + locationHash.targetPopup + '"] [data-source-product="' + locationHash.productId + '"]');
                if (currentElement.length > 0) {
                    var scrollTop, headerFix, headerScrl;
                    headerFix = $('header#fix');
                    headerScrl = $('header#scrl');
                    scrollTop = currentElement.offset().top;
                    if (headerFix.css('display') != 'none') {
                        scrollTop -= headerFix.outerHeight(true);
                        if (!$('body').hasClass('ins')) {
                            scrollTop -= 70;
                        }
                    } else {
                        scrollTop -= (headerScrl.outerHeight(true) + 20);
                    }

                    $('html, body').animate({
                        scrollTop: scrollTop
                    }, 500);

                    currentElement.addClass('active');
                    setTimeout(function () {
                        currentElement.removeClass('active');
                    }, 10);

                    var currentJcarousel = currentElement.parents('.jcarousel').eq(0);
                    if (currentJcarousel.length > 0) {
                        scrollToCarouselElement(currentJcarousel, locationHash.productId, 'source');
                    }
                }
                history.pushState('', document.title, window.location.pathname + window.location.search);
            }

            return false;
        });

        $(window).load(function () {
            var locationHash = getLocationInfo();
            if (locationHash != null) {
                var dataSourcePopupBlock = $('[data-source-popup="' + locationHash.targetPopup + '"]');
                if (dataSourcePopupBlock.length > 0) {
                    if (locationHash.productId == 0) {
                        var productId = dataSourcePopupBlock.find('[data-source-product]').first().data('sourceProduct');
                        if (typeof productId != 'undefined') {
                            showPopupQuick(productId, locationHash.targetPopup);
                        }
                    } else {
                        var targetSimilar = locationHash.targetPopup.match(/^similar(\d+)$/);
                        if (targetSimilar != null) {
                            var attributeId = targetSimilar[1];
                            var similarBlock = $('#group-products');
                            var li = similarBlock.find('ul.tabs li[data-elem-id="' + attributeId + '"]');
                            if ($('[data-source-popup="' + locationHash.targetPopup + '"] [data-source-product="' + locationHash.productId + '"]').length > 0) {
                                showPopupQuick(locationHash.productId, locationHash.targetPopup);
                                similarBlock.tabs({
                                    active: li.index()
                                });
                            }
                        } else if (dataSourcePopupBlock.find('[data-source-product="' + locationHash.productId + '"]').length > 0) {
                            showPopupQuick(locationHash.productId, locationHash.targetPopup);
                        }
                    }
                }
            }
        });

        function getLocationInfo() {
            var locationHash = location.hash.match(/^#product-info\/(\w+\d*)-(\d+)$/);
            if (locationHash != null) {
                return {targetPopup: locationHash[1], productId: locationHash[2]};
            }

            return null;
        }

        function scrollToCarouselElement(carousel, productId, elementType) {
            var index = $('[data-' + elementType + '-product="' + productId + '"]').index();
            carousel.jcarousel('scroll', index, false);
        }
    })();
});