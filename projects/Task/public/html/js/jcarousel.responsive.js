$(document).ready(function(){    
  var jcarousel = $('.jcarousel');

  jcarousel.on('jcarousel:reload jcarousel:create', function(){
    var carousel = $(this),
    width = carousel.innerWidth();

	  if (width >= 1024){
	    width = width / 3; //Количество слайдов по умолчанию
	  } else if (width >= 350){
	    width = width / 3;
	  }

	  carousel.jcarousel('items').css('width', Math.ceil(width) + 'px');
  })

  .jcarousel({            
	  //wrap: 'circular', //Цикличность   
    wrap: 'both', //Цикличность        
    animation: 300
	});

	$('.jcarousel-control-prev').jcarouselControl({
	  target: '-=1' //Одновременная прокрутка количества слайдов
	});

	$('.jcarousel-control-next').jcarouselControl({
	  target: '+=1' //Одновременная прокрутка количества слайдов
	});

	$('.jcarousel-pagination').on('jcarouselpagination:active', 'a', function(){
	  $(this).addClass('active');
	})
	  
	.on('jcarouselpagination:inactive', 'a', function(){
	  $(this).removeClass('active');
	})
	  
	.on('click', function(e){
	  e.preventDefault();
	})   
	  
	.jcarouselPagination({
	  perPage: 4, //Одновременная прокрутка количества слайдов   
	  item: function(page){
	    return '<a href="#' + page + '">' + page + '</a>';
	  }
	});   

	$('.jcarousel-wrapper.single .jcarousel-pagination').jcarouselPagination({
	  'perPage': 1 //Одновременная прокрутка количества слайдов для одиночного слайдера      
	});
});