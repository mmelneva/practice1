$(document).ready(function() {
	/*ScrollHeader -------------------------------------------------------------------------------*/
    scrollHeader = function() {
        var headerScroll = $('header#scrl');
        var headerScrollHeight = headerScroll.height();
        var headerTopScroll = $(this).scrollTop();
        var elContainerMargin = 30;
        var elContainer = $('#container'); //To add 'padding = headerScrollHeight'
        var elContainerHeight = elContainer.height();

        if (headerTopScroll > 1) {
            $('header#fix').addClass('header-hidden');
            $('header#scrl').addClass('header-visible');
            elContainer.css('padding-top', headerScrollHeight + elContainerMargin, 'px');
        } else {
            $('header#fix').removeClass('header-hidden');
            $('header#scrl').removeClass('header-visible');
            elContainer.css('padding-top', '30px');
        }
    };

    scrollHeader();
    $(window).on('scroll resize', scrollHeader);
	/*end ScrollHeader ---------------------------------------------------------------------------*/

  /*Tabs -----------------------------------------------------------------------------------------*/
    $('.tabs_ui').tabs({
        activate: function (event, ui) {
            var tabCarousels = $(document).find(ui.newPanel).find('.jcarousel');
            if (tabCarousels.length > 0) {
                tabCarousels.each(function () {
                    jcarouselInit($(this));
                });
            }
        }
    });
    jcarouselInit();
  /*end Tabs -------------------------------------------------------------------------------------*/

  /*Выпадающее меню на JS ------------------------------------------------------------------------*/  	
	//Открытие/Закрытие меню по классу .open	 
  var subMenu = $('#submenu');
  var subMenuOpenElements = $('.open');   

  subMenuOpenElements.mouseenter(function(){
		subMenu.finish();
		subMenu.fadeIn(1);  

		var menuOffset = subMenu.offset();
		var xFrom, yFrom, xTo, yTo, menuHeight, menuWidth;
		xFrom = menuOffset.left;
		yFrom = menuOffset.top;
		menuWidth = subMenu.width();
		menuHeight = subMenu.height();
		xTo = xFrom + menuWidth;
		yTo = yFrom + menuHeight;

    var subMenuClose = function(e){
	    var isHoverOpener, isHoverMenu, isHover;

			isHoverOpener = $('.open:hover').addClass('actived').length > 0; //Добавление активного класса при наведении на меню  
			
			$('ul.barmain li a.open').hover(function(){  		      
			  $('ul.barmain li a.open').removeClass('actived'); //Удаление активного класса со всех пунктов при наведении на меню        
        $(this).addClass('actived'); //Добавление активного класса при наведении на пункт меню, который открывает выпадающее меню       
      });  
		      
			//Use this, because in IE we can't find hovered 'div' - only hovered 'a'
			isHoverMenu = e.pageX >= xFrom && e.pageX <= xTo && e.pageY >= yFrom && e.pageY <= yTo;

			isHover = isHoverOpener || isHoverMenu;
			if (!isHover){
				subMenu.fadeOut(1);
				$(document).off('mousemove', subMenuClose);  
				$('ul.barmain li a.open').removeClass('actived'); //Удаление активного класса после закрыия меню  
			}
    };

    $(document).on('mousemove', subMenuClose);  
  });
  /*end Выпадающее меню на JS --------------------------------------------------------------------*/   
	 
  /*jQuery.Royalslider ---------------------------------------------------------------------------*/
  jQuery.rsCSS3Easing.easeOutBack = 'cubic-bezier(0.175, 0.885, 0.320, 1.275)';
    var royalSliderBlock = $('.royalSlider');

    if(royalSliderBlock.length > 0) {
      royalSliderBlock.royalSlider({
        autoHeight: true,
        arrowsNav: false,
        fadeinLoadedSlide: false,
        //controlNavigation: 'tabs',
        controlNavigation: 'bullets',
        imageAlignCenter: false,
        imageScaleMode: 'none',
        transitionType: 'fade',
        loop: false,
        loopRewind: true,
        keyboardNavEnabled: true,
        usePreloader: false,
        navigateByClick: false,
        sliderTouch: false,
        sliderDrag: false,

        block: {
          delay: 400
        }
      });

      var slider = royalSliderBlock.data('royalSlider');
      slider.ev.on('rsBeforeAnimStart', function (event) {
        // before animation between slides start
        var currSlideContent = slider.currSlide.content;
        currSlideContent.css('display', 'block');
        var blockToReplace = currSlideContent.find('[data-image-src]');
        if (blockToReplace.length > 0) {
          blockToReplace.replaceWith('<img src="' + blockToReplace.data('imageSrc') + '" >');
        }
      });
    }
  /*end jQuery.Royalslider -----------------------------------------------------------------------*/
	                                      
  /*Form Styler ----------------------------------------------------------------------------------*/
  $(function(){
	  $('input.styles, select.styles').styler();
  });  
  /*end Form Styler ------------------------------------------------------------------------------*/ 
	      
  /*Toggler-js -----------------------------------------------------------------------------------*/ 
	//Фильтр Каталога
  $('.block_filters .toggler').click(function(){      
	  $(this).toggleClass('active');
    $(this).next('.block_toggler').slideToggle();   
	}); 
  /*end Toggler-js -------------------------------------------------------------------------------*/      

  /*JS-scripts -----------------------------------------------------------------------------------*/ 
  //Фильтрация ввода в поля
  $('input.fltr').keypress(function(event){
    var key, keyChar;
    if(!event) var event = window.event;
    if (event.keyCode) key = event.keyCode;
    else if(event.which) key = event.which;
    if(key==null || key==0 || key==8 || key==13 || key==9 || key==46 || key==37 || key==39 ) return true;
    keyChar=String.fromCharCode(key);
    if(!/\d/.test(keyChar)) return false;
  });    
  /*end JS-scripts -------------------------------------------------------------------------------*/         

  /*ScrollTop ------------------------------------------------------------------------------------*/
  var scrollTop = $('#scrolltop');
  scrollTop.hide();

  $(function(){
	  var stBottomDefault = scrollTop.css('bottom'),
	  stHeight = scrollTop.height();

	  var modifyScrollTop = function(){
	    var windowHeight = $(window).height(),
	        scrollOffset = $(this).scrollTop(),
	        footerOffset = $('footer').offset(),
	        stBottom;

	    if (1 < scrollOffset){
	      scrollTop.fadeIn();
	    } else {
	      scrollTop.fadeOut();
	    }

	    if (footerOffset.top > scrollOffset + windowHeight - stHeight){
	      stBottom = stBottomDefault;
	    } else {
	      stBottom = scrollOffset + windowHeight - footerOffset.top;
	    }
	    scrollTop.css({bottom: stBottom});
	  };

	  window.setInterval(modifyScrollTop, 30); 
		$(document).scroll(modifyScrollTop);

	  scrollTop.on('click', 'a', function(){
	    $('html, body').animate({scrollTop: 0}, 800);
	    return false;
	  });
  });
  /*end ScrollTop --------------------------------------------------------------------------------*/

    /* Menu top */
    var topMenu = $('header#fix ul.bar');
    var topMenuScroll = $('header#scrl ul.bar');
   // setStyleFroTopMenu(topMenu);
    setStyleFroTopMenuScroll(topMenuScroll);

    $(window).resize(function() {
       // setStyleFroTopMenu(topMenu);
        setStyleFroTopMenuScroll(topMenuScroll);
    });

    function setStyleFroTopMenu(menu) {
        if (menu.length > 0) {
            var menuHeight;
            if (menu.parents('header#fix').is(':visible')) {
                menuHeight = menu.height();
            } else {
                //We are using jQuery actual plugin
                menuHeight = menu.actual('height');
            }
            if (menuHeight > 20) {
                menu.css({'top' : '0px'});
            } else {
                menu.css({'top' : '10px'});
            }
        }
    }

    function setStyleFroTopMenuScroll(menu) {
        if (menu.length > 0) {
            var menuHeight;
            if (menu.parents('header#scrl').is(':visible')) {
                menuHeight = menu.height();
            } else {
                //We are using jQuery actual plugin
                menuHeight = menu.actual('height');
            }
            if (menuHeight > 20) {
                menu.css({'top' : '0px'});
                menu.css({'margin-top' : '-5px'});
                menu.css({'margin-bottom' : '-10px'});
            } else {
                menu.css({'top' : '1px'});
                menu.css({'margin-top' : '0px'});
                menu.css({'margin-bottom' : '0px'});
            }
        }
    }

    $('ul.bar li.catalog > ul > li').each(function() {
        if($(this).hasClass('active')) {
            $('ul.bar li.catalog').addClass('active');
        }
    });

    /* Radios - start */
    $(document).on('change', '[data-checkbox-singular]', function () {
        var currentCheckbox, group;
        currentCheckbox = this;

        if (currentCheckbox.checked) {
            group = $(currentCheckbox).data('checkboxSingular');
            $('[data-checkbox-singular="' + group + '"]').each(function (index, checkbox) {
                if (currentCheckbox != checkbox) {
                    checkbox.checked = false;
                }
            });
        }
    });
    /* Radios - end */

    /* Popup */
    window.setPopupLocation = function(popup) {
        var top = parseInt((window.innerHeight - popup.outerHeight()) / 2);
        if (top < 0) top = 10;
        popup.css({'top': top + 'px'});
    };
    /* END - Popup */

    /* sent form only one time */
    $(document).on('submit', 'form.submit-form', function (e) {
        var form = $(this);
        form.find('[type="submit"]').each(function() {
            $(this).attr('type', 'button');
        });
    });

    (function(){
        var locationPathname = window.location.pathname.replace(/\/page-\d+$/, '');
        var locationSearch = window.location.search;
        var currentUrl = decodeURIComponent(locationPathname + locationSearch);

        $('#quick_links a').each(function () {
            var href = $(this).attr('href');
            if (typeof href != 'undefined' && href == currentUrl) {
                $(this).parents('li').eq(0).addClass('active');
            }
        });
    })();

    //1) Клик по любой ссылке в верхнем меню (т. е. это одна общая цель)  TOPMENU_LINKS_CLICK
    $(document).on('click', ".topbar-block a", function () {
        countersListEvent('TOPMENU_LINKS_CLICK');
    });

    // mainpage

    //II. Главная страница => Блок «последние работы» => Клик по изображению на карточке
    $(document).on('click', ".product-container.lastwork li a:not(.pop_quick) img", function () {
        countersListEvent('MAINPAGE_LASTWORK_IMG_CLICK');
    });

    // Главная страница => Блок «последние работы» => Клик по ссылке с названием на карточке
    $(document).on('click', ".product-container.lastwork li .title a", function () {
        countersListEvent('MAINPAGE_LASTWORK_NAME_LINK_CLICK');
    });

    // Главная страница => Блок «последние работы» => Клик по любой кнопке «Заказать похожий» у товаров
    // (т. е. не в быстром просмотре)
    $(document).on('click', ".product-container.lastwork li .block_btns button", function () {
        countersListEvent('MAINPAGE_LASTWORK_WANT_THE_SAME_CLICK');
    });

    // Главная страница => Блок «последние работы» => Клик по стрелкам для прокрутки
    $(document).on('click', ".product-container.lastwork .jcarousel-controls a", function () {
        countersListEvent('MAINPAGE_LASTWORK_SCROLL_ARROW_CLICK');
    });


    // popup

    //2) Всплывающее окно при нажатии на кнопку «Быстрый просмотр» в элементах блока Последние работы.
    //Клик по кнопке «Заказать похожий»
    $(document).on('click', "div[data-target-popup=last] button.pop_order", function () {
        countersListEvent('MAINPAGE_LASTWORK_POPUP_WANT_THE_SAME_CLICK');
    });

    //Клик по ссылке с названием
    $(document).on('click', "div[data-target-popup=last] .tith3 a", function () {
        countersListEvent('MAINPAGE_LASTWORK_POPUP_NAME_LINK_CLICK');
    });

    //Клик по кнопке закрытия окна
    $(document).on('click', "div[data-target-popup=last] a.close", function () {
        countersListEvent('MAINPAGE_LASTWORK_POPUP_CLOSE_CLICK');
    });

    //Клик по стрелке прокрутки
    $(document).on('click', "div[data-target-popup=last] a.jcarousel-control-prev, div[data-target-popup=last] a.jcarousel-control-next", function () {
        countersListEvent('MAINPAGE_LASTWORK_POPUP_SCROLL_ARROW_CLICK');
    });

    // popup want the same

    // 3) Всплывающее окно при нажатии на кнопку «Заказать похожий» в элементах блока Последние работы.
    // Клик по кнопке «Заказать»
    $(document).on('click', "#poporder[data-type=homepage] input[type=submit]", function () {
        countersListEvent('MAINPAGE_WANT_THE_SAME_POPUP_ORDER_CLICK');
    });

    // Ввод текста в любое поле в форме хочу такую
    var inputed = false;
    // #poporder[data-type=homepage] input[type=text], #poporder[data-type=homepage] textarea
    $(document).on('keyup', "#poporder input[type=text], #poporder textarea", function () {
        if (!inputed) {
            var datatype = $('#poporder').attr('data-type');

            if (datatype == 'homepage') {
                countersListEvent('MAINPAGE_WANT_THE_SAME_POPUP_DO_INPUT');
            } else if (datatype == 'productpage') {
                countersListEvent('PRODUCT_PAGE_WANT_THE_SAME_POPUP_DO_INPUT');
            } else if (datatype == 'categorypage') {
                countersListEvent('CATALOG_WANT_THE_SAME_POPUP_DO_INPUT');
            } else {
                //categorypage
                console.log(datatype);
            }

            inputed = true;
        }
    });

    $(document).on('focus', "#poporder input[type=text], #poporder textarea", function () {
        inputed = false;
    });

    //4) Блок «Популярные категории»

    // Клик по любой ссылке в блоке «Популярные категории» (одна общая цель)
    $(document).on('click', ".popular_cats_mainpage a", function () {
        countersListEvent('MAINPAGE_POULAR_CATS_LINKS_CLICK');
    });

//III. Страница листинга (любая страница каталога, со списком товара, содержащая название, фото в миниатюре, кнопку «Заказать похожий»).
//Примеры: http://www.lit-mebel.ru/cat/raspashnye-shkafy, http://www.lit-mebel.ru/type/shkafy-kupe-v-spalnyu

    // 1) Каталог товара => Клик по изображению на карточке
    $(document).on('click', ".catalog.ins.infinity a:not(.pop_quick) img", function () {
        countersListEvent('CATALOG_IMG_CLICK');
    });

    // Клик по кнопке «Быстрый просмотр» на изображении
    $(document).on('click', ".catalog.ins.infinity li .eye", function () {
        countersListEvent('CATALOG_QUICK_VIEW_CLICK');
    });

    // Клик по ссылке с названием на карточке
    $(document).on('click', ".catalog.ins.infinity li .title a", function () {
        countersListEvent('CATALOG_NAME_LINK_CLICK');
    });

    // Клик по любой кнопке «Заказать похожий»
    $(document).on('click', ".catalog.ins.infinity li .block_btns button", function () {
        countersListEvent('CATALOG_WANT_THE_SAME_CLICK');
    });

    // popup list

    //2) Всплывающее окно при нажатии на кнопку «Быстрый просмотр» в элементах блока каталога
    //Клик по кнопке «Заказать похожий»
    $(document).on('click', "div[data-target-popup=list] button.pop_order", function () {
        countersListEvent('CATALOG_POPUP_WANT_THE_SAME_CLICK');
    });

    //Клик по ссылке с названием
    $(document).on('click', "div[data-target-popup=list] .tith3 a", function () {
        countersListEvent('CATALOG_POPUP_NAME_LINK_CLICK');
    });

    //Клик по кнопке закрытия окна
    $(document).on('click', "div[data-target-popup=list] a.close", function () {
        countersListEvent('CATALOG_POPUP_CLOSE_CLICK');
    });

    //Клик по стрелке прокрутки
    $(document).on('click', "div[data-target-popup=list] a.jcarousel-control-prev, div[data-target-popup=list] a.jcarousel-control-next", function () {
        countersListEvent('CATALOG_POPUP_SCROLL_ARROW_CLICK');
    });

    // 3) Всплывающее окно при нажатии на кнопку «Заказать похожий»
    // Клик по кнопке «Заказать»
    $(document).on('click', "#poporder[data-type=categorypage] input[type=submit]", function () {
        countersListEvent('CATALOG_WANT_THE_SAME_POPUP_ORDER_CLICK');
    });


    // IV. Карточка товара (Любая страница, содержащая фото товара, блоки с описанием и характеристиками, кнопку «Заказать похожий»)
    // 1) Первый экран

    // Клик по кнопке «Заказать похожий»
    $(document).on('click', ".block_zakaz button.pop_order", function () {
        countersListEvent('PRODUCT_PAGE_WANT_THE_SAME_CLICK');
    });

    // Клик по кнопке «назад»
    $(document).on('click', "a.backpage", function () {
        countersListEvent('PRODUCT_PAGE_BACKPAGE_CLICK');
    });

    // Клик по ссылке «Доставка»
    $(document).on('click', ".block_serv .serv.deliveries a", function () {
        countersListEvent('PRODUCT_PAGE_DELIVERY_CLICK');
    });

    // клик по ссылке «Оплата»
    $(document).on('click', ".block_serv .serv.payment a", function (e) {
        countersListEvent('PRODUCT_PAGE_PAYMENT_CLICK');
    });

    // Клик по ссылке «Гарантия»
    $(document).on('click', ".block_serv .serv.warranty a", function () {
        countersListEvent('PRODUCT_PAGE_WARRANTY_CLICK');
    });

    // 3) Блок характеристики/описание

    // Клик на ссылку «Характеристики»
    $(document).on('click', ".block_opis a#characteristics", function () {
        countersListEvent('PRODUCT_PAGE_OPIS_CHARACTERISTICS_CLICK');
    });

    // Клик на ссылку «Описание»
    $(document).on('click', ".block_opis a#decription", function () {
        countersListEvent('PRODUCT_PAGE_OPIS_DESCRIPTION_CLICK');
    });

    // 4) Блок «Похожие товары»

    // Клик по всем ссылкам типа: «по типу отделки», «по цене»
    $(document).on('click', ".similar-groups a.ui-tabs-anchor", function () {
        countersListEvent('PRODUCT_PAGE_SIMILAR_TABS_CLICK');
    });

    // Клик по изображению на карточке
    $(document).on('click', ".similar-groups a:not(.pop_quick) img", function () {
        countersListEvent('PRODUCT_PAGE_SIMILAR_IMG_CLICK');
    });

    // Клик по кнопке «Быстрый просмотр» на изображении
    $(document).on('click', ".similar-groups li .eye", function () {
        countersListEvent('PRODUCT_PAGE_SIMILAR_QUICK_VIEW_CLICK');
    });

    // Клик по ссылке с названием на карточке
    $(document).on('click', ".similar-groups li .title a", function () {
        countersListEvent('PRODUCT_PAGE_SIMILAR_NAME_LINK_CLICK');
    });

    // Клик по любой кнопке «Заказать похожий»
    $(document).on('click', ".similar-groups li .block_btns button", function () {
        countersListEvent('PRODUCT_PAGE_SIMILAR_WANT_THE_SAME_CLICK');
    });

    // V. Форма обратной связи на странице конктактов - http://www.lit-mebel.ru/kontakty
    // Ввод текста в любое поле
    var inputed2 = false;
    // #poporder[data-type=homepage] input[type=text], #poporder[data-type=homepage] textarea
    $(document).on('keyup', "#contacts input[type=text], #contacts textarea", function () {
        if (!inputed2) {
            countersListEvent('CONTACTS_PAGE_FORM_DO_INPUT');
            inputed2 = true;
        }
    });

    $(document).on('focus', "#contacts input[type=text], #contacts textarea", function () {
        inputed2 = false;
    });

    //Tooltip
    (function ($, document) {
        $(document).tooltip({
            items: '.tooltip',
            position: {my: "center top", at: "center bottom", collision: "flipfit"},

            content: function () {
                var element, imagePath, content = '', tooltipContentId, tooltipContent;

                element = $(this);
                imagePath = element.data('tooltipImage');
                if (imagePath !== '' && imagePath !== undefined) {
                    content += '<img class="tooltip-image" src="' + imagePath + '" alt="" />';
                }

                if (element.is("[title]")) {
                    content += element.attr("title");
                }

                tooltipContentId = element.data('tooltipContent');
                if (tooltipContentId !== undefined) {
                    tooltipContent = $(tooltipContentId);
                    if (tooltipContent.length > 0) {
                        content += tooltipContent.html();
                    }
                }

                return content;
            }
        });
    })(jQuery, document);

    //initialize swiper when document ready
    if ($('.swiper-container .swiper-slide').length > 1) {
        var mySwiper = new Swiper('.swiper-container', {
            direction: 'horizontal',
            autoplay: 5000,
            pagination: '.swiper-pagination',
            paginationClickable: true,
            loop: true,
            nextButton: '.block_royalSlider .swiper-button-next',
            prevButton: '.block_royalSlider .swiper-button-prev'
        });
    }

    // Отзывы на главной странице -> нажатие на стрелки перелистывания(одна цель на обе стрелки).
    $(document).on('click', "body:not(.ins) .reviews-swiper-container .reviews-slider-next, body:not(.ins) .reviews-swiper-container .reviews-slider-prev", function () {
        countersListEvent('MAINPAGE_REVIEWS_SCROLL_ARROW_CLICK');
    });

    // Отзывы на главной странице -> переход на товар(нажатие на название товара или просмотр.
    $(document).on('click', "body:not(.ins) .reviews-swiper-container .review-product li a ", function () {
        countersListEvent('MAINPAGE_REVIEWS_LINK_CLICK');
    });

    // Баннер на главной странице -> нажатие на стрелки перелистывания(одна цель на обе стрелки).
    $(document).on('click', ".block_slider .swiper-button-next, .block_slider .swiper-button-prev ", function () {
        countersListEvent('MAINPAGE_BANNER_SCROLL_ARROW_CLICK');
    });

    // Баннер на главной странице -> Клик по любому баннеру.
    $(document).on('click', ".block_slider .swiper-wrapper img", function () {
        countersListEvent('MAINPAGE_BANNER_IMG_CLICK');

        var link = $(this).parent().attr('href'),
            left_link = $('#calc a').attr('href'),
            host = window.location.href;
        host = host.slice(0, -1);

        if (link == left_link || link == host.left_link || link == host + '/constructor') {
            //клик по баннеру калькулятора на главной (только по одному этому баннеру)
            countersListEvent('MAINPAGE_BANNER_IMG_CALC_CLICK');
        }

    });

    // клик по плавающей кнопке "калькулятор"
    $(document).on('click', "#calc a", function () {
        countersListEvent('CALCULATOR_FIXED_BTN_CLICK');
    });

});