//Выравнивание высоты блоков с разным содержимым по высоте. 
//Растягивание блока по самому длинному блоку в одну линию.  

//Универсальный, для всех блоков
function setEqualHeight(columns){
	var maxHeight = Math.max.apply(Math, columns.map(function(){
	  return $(this).height()
	}).get());
	columns.height(maxHeight);
}     

function setEqualHeightForGroup(groupElements, element, count){
	groupElements.each(function(){
		var c = count;
		var objCount = $(this).find('li').length;
		for (var i = 0; i < objCount; i += c){
		  setEqualHeight($(this).find(element).slice(i, i + c));
	  }
	});
}   

function getSliceElementCountForHeightResize(parentElement, child){
	var sliceCount = 0;
	var childElement = parentElement.find(child);
	if (childElement.length > 0){
		var parentWidth = parentElement.width();
		var elementWidth = childElement.outerWidth(true);
		sliceCount = Math.floor(parentWidth / elementWidth);
	} 
	return sliceCount;
}
//end Универсальный, для всех блоков 

$(window).load(function(){    
  //Выравнивание высоты блоков в линию для Слайдера
  if($('ul').is('.infinity')){                                            
    var elCount = getSliceElementCountForHeightResize($('ul.infinity'), 'li');    
    setEqualHeightForGroup($('ul.infinity'), '.blockfix.hts', elCount);      
  }   
	
	//Для штучного ряда ТАБОВ - 4 в строке	 
  if($('ul').is('.four_heightline_tabs')){
    $('ul.four_heightline_tabs').each(function(){
      var c = 4;
      var objCount=$(this).find('aside').length;
      for (var i = 0; i < objCount; i+=c){
        setEqualHeight($(this).find('aside .blockfix.hts').slice(i, i+c));
      }   
			setEqualHeight($('.ui-tabs-panel'), 'aside .blockfix.hts', 4); //Обложка для ТАБов
    });
  }  
	  
  /*Tabs -----------------------------------------------------------------------------------------*/
	//Выравнивание высоты блоков в ТАБах
  $('.tabs_ui').tabs();  
  /*end Tabs -------------------------------------------------------------------------------------*/  
});                                                                     