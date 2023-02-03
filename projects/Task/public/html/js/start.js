$(document).ready(function(){    
	/*ScrollHeader ---------------------------------------------------------------------------------*/  
  $(function(){
	  var isShowing = false;
		
	  $(window).scroll(function(){
	    if ($(this).scrollTop() > 40 && ($(document).height() - $(window).height() > 135) && !isShowing){   
	      $('header#fix').fadeOut(1);
	      $('header#scrl').fadeIn(300).css('top', '0px');  
	      $('#container').css('padding-top', '120px'); //Отступ под header#scrl  
	      isShowing = true;
	    } else if ($(this).scrollTop() < 40 && isShowing){
        $('header#scrl').fadeOut(300);
        $('header#fix').fadeIn(1);
        $('#container').css('padding-top', '350px'); //Отступ по умолчанию   
	      $('body.ins #container').css('padding-top', '260px'); //Отступ по умолчанию
        isShowing = false;
	    }
	  });
  });	
	/*end ScrollHeader ----------------------------------------------------------------------------*/  
	 
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
  $('.royalSlider').royalSlider({  
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

  /*Form PlusMinus input -------------------------------------------------------------------------*/
  $('.plus').click(function () {
    var $input = $(this).parent().find('input');
    //if (!$input.val()) {$input.val(1) ; } else { $input.val(parseInt($input.val()) + 1);}
    $input.val(parseInt($input.val()) + 1);
    $input.change();
    return false;
  });

  $('.minus').click(function () {
    var $input = $(this).parent().find('input');
    var count = parseInt($input.val()) - 1;
    count = count < 1 ? 1 : count; // Если нужно колличество в инпуте уменьшить до (1)
    //count = count < 0 ? 0 : count; // Если нужно колличество в инпуте уменьшить до (0)
    $input.val(count);
    $input.change();
    return false;
  });
  /*end Form PlusMinus input ---------------------------------------------------------------------*/ 
	  
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
	
  /*Popups ---------------------------------------------------------------------------------------*/   
	//Окно 'Личный кабинет'
  $('.pop_logout').click(function(){
		$('#poplogout .popup, #poplogout .bg0').fadeIn();   
    $('html, body').scrollTop({top: '0px'}, 800);               
		return false;
	});

	$('#poplogout .close, #poplogout .bg0').click(function(){
		$('#poplogout .popup:visible, #poplogout .bg0:visible').fadeOut();    
		return false;
	}); 
	
	//Окно 'Быстрый заказ'	
  $('.pop_quick').click(function(){
		$('#popquick .popup, #popquick .bg0').fadeIn();   
    $('html, body').scrollTop({top: '0px'}, 800);               
		return false;
	});

	$('#popquick .close, #popquick .bg0').click(function(){
		$('#popquick .popup:visible, #popquick .bg0:visible').fadeOut();    
		return false;
	});   
  /*end Popups -----------------------------------------------------------------------------------*/        
});