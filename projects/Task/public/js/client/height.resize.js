//Выравнивание высоты блоков с разным содержимым по высоте. 
//Растягивание блока по самому длинному блоку в одну линию.  

function realHeight(obj) {
    if (obj.is(':visible')) {
        return obj.height();
    } else {
        //We are using jQuery actual plugin
        return obj.actual('height');
    }
}
//Универсальный, для всех блоков
function setEqualHeight(columns) {
    columns.css({'height': ''});
    var maxHeight = Math.max.apply(Math, columns.map(function () {
        return realHeight($(this));
    }).get());
    columns.height(maxHeight);
}

function setEqualHeightForGroup(groupElements, element, count) {
    groupElements.each(function () {
        var c = count;
        var objCount = $(this).find(element).length;
        for (var i = 0; i < objCount; i += c) {
            setEqualHeight($(this).find(element).slice(i, i + c));
        }
    });
}

function getSliceElementCountForHeightResize(parentElement, child) {
    var sliceCount = 0;
    var childElement = parentElement.find(child);
    if (childElement.length > 0) {
        var parentWidth = parentElement.width();
        var elementWidth = childElement.outerWidth(true);
        sliceCount = Math.floor(parentWidth / elementWidth);
    }
    return sliceCount;
}
//end Универсальный, для всех блоков 

$(window).load(function () {

    window.heightResizeInit = function () {
        //Выравнивание высоты блоков в линию для Слайдера
        if ($('ul').is('.infinity')) {
            var elCount = getSliceElementCountForHeightResize($('ul.infinity'), 'li');
            setEqualHeightForGroup($('ul.infinity'), '.blockfix.hts .title', elCount);
            setEqualHeightForGroup($('ul.infinity'), '.blockfix.hts .small-content', elCount);
            setEqualHeightForGroup($('ul.infinity'), '.blockfix.hts .block_price', elCount);
            setEqualHeightForGroup($('ul.infinity'), '.blockimg', elCount);
        }

        var galleryBlock = $('ul.gallery-block');

        if (galleryBlock.length > 0) {
            galleryBlock.each(function () {
                var galleryBlockElCount = getSliceElementCountForHeightResize($(this), 'li');
                setEqualHeightForGroup($(this), 'li .blockimg', galleryBlockElCount);
                setEqualHeightForGroup($(this), 'li .title', galleryBlockElCount);
                setEqualHeightForGroup($(this), 'li .small-content', galleryBlockElCount);
                setEqualHeightForGroup($(this), 'li', galleryBlockElCount);
            });
        }

        // Для штучного ряда ТАБОВ - 4 в строке
        (function () {
            if ($('ul').is('.fournone')) {
                $('ul.fournone').each(function () {
                    setEqualHeight($(this).find('aside .blockfix.hts .title'));
                    setEqualHeight($(this).find('aside .blockfix.hts .small-content'));
                    setEqualHeight($(this).find('aside .blockfix.hts .block_price'));
                    setEqualHeight($(this).find('aside .blockimg'));
                });
            }
        })();

        if ($('ul').is('.reviews-list')) {
            $('ul.reviews-list').each(function () {
                setEqualHeight($(this).find('li.review-container'));
            });
        }

        $('.reviews-swiper-container').each(function () {
            setEqualHeight($(this).find('.review-one'));
        });
    };

    heightResizeInit();

    $(window).resize(function() {
        heightResizeInit();
    });
});