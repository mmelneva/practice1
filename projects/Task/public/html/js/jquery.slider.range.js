$(document).ready(function(){           
  /*SliderRange ----------------------------------------------------------------------------------*/
  $('#slider_range').slider({    
	  min: 0,
	  max: 100000,    
	  //value: 25, //Расстояние промежуточных точек ползунка одиночного слайдера  
    values: [0, 100000], //Для двойного слайдера с минимум и максимум		 		
    range: true, //Для двойного слайдера с минимум и максимум		
	  step: 10,   
		animate: true, 
    stop: function(event, ui){
      $('input#min_cost').val(jQuery('#slider_range').slider('values',0));
      $('input#max_cost').val(jQuery('#slider_range').slider('values',1));
    },    
		
		//Для одиночного слайдера 
	  slide: function(event, ui){
		  $('#cost').val('$' + ui.value);    
			$('.cost').val('' + $('.slider_range').slider('value'));        
	  },   
		
		//Для двойного слайдера с минимум и максимум
    slide: function(event, ui){
      $('input#min_cost').val(jQuery('#slider_range').slider('values',0));
      $('input#max_cost').val(jQuery('#slider_range').slider('values',1));
    }
  });

  $('input#min_cost').change(function(){
    var value1=jQuery('input#min_cost').val();
    var value2=jQuery('input#max_cost').val();
		
    if(parseInt(value1) > parseInt(value2)){
    value1 = value2;
      $('input#min_cost').val(value1);
    } $('#slider_range').slider('values',0, value1); 
  }); 

  $('input#max_сost').change(function(){
    var value1=jQuery('input#min_сost').val();
    var value2=jQuery('input#max_сost').val();   		
    $('#slider_range').slider('values',1, value2);   
  });             
	
	//Для пересчета промежуточных точек ползунка одиночного слайдера 
	//$('#slider_range').slider({    
	  //slide: function(event, ui){     
			//$('#cost').val('' + ui.value); 
	  //}                         
	//});  
	
	//Синхронное изменение положения ползунка одиночного слайдера при изменении значения инпут      	       
  //$('.row .cost').keyup(function(){
	  //var jThis, parentBlock;  		
	  //jThis = $(this);
	  //parentBlock = jThis.parents('.row').eq(0);
	  //parentBlock.find('.slider_range').slider({value:jThis.val()});
  //});

  //$('.row .cost').change(function(){
	  //var jThis = $(this);

	  //if(jThis.val() == ''){
	    //jThis.val(0);
	  //} else if (jThis.val() > 100){
	    //jThis.val(100);
	  //}
  //});
  /*end SliderRange ------------------------------------------------------------------------------*/   
});   