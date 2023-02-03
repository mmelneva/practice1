(function () {
    $(document).on('click', '[data-element-list="add"]', function () {
        var toggle, loadElementUrl, containerId, container, newKey, maxKey;
        toggle = $(this);
        if (toggle.prop('disabled')) {
            return;
        } else {
            toggle.prop('disabled', true);
        }
        loadElementUrl = toggle.data('loadElementUrl');
        containerId = toggle.data('elementListTarget');
        container = $('#' + containerId);
        var containerChildList = container.find('[data-element-list="element"]');
        if (containerChildList.length > 0) {
            maxKey = getMaxKey(containerChildList);
        } else {
            maxKey = 0;
        }
        newKey = (maxKey + 1);

        $.ajax({
            cache: false,
            type: 'GET',
            dataType: 'json',
            url: loadElementUrl,
            data: {key: newKey},
            success: function (result) {
                var jResult = $(result['element']);
                jResult.appendTo(container);
                toggle.prop('disabled', false);
            }
        });
    });

    var toggleExpand = function (element) {
        var elementContainer = $('[data-element-list="element"]').has(element);

        var headerElement = elementContainer.find('[data-element-list="header"]');
        var fieldsElement = elementContainer.find('[data-element-list="fields"]');
        var toggleElement = elementContainer.find('[data-element-list="toggle-expand"]');

        headerElement.toggleClass('dnone');
        fieldsElement.toggleClass('dnone');

        toggleElement.toggleClass('glyphicon-collapse-down');
        toggleElement.toggleClass('glyphicon-collapse-up');
    };

    $(document).on('click', '[data-element-list="container"] [data-element-list="element"] [data-element-list="edit"]', function () {
        toggleExpand(this);
    });

    $(document).on('click', '[data-element-list="container"] [data-element-list="element"] [data-element-list="toggle-expand"]', function () {
        toggleExpand(this);
    });

    $(document).on('click', '.grouped-field-list-container legend .toggle-expand', function () {
        var container = $('.grouped-field-list-container').has(this);

        $(this).toggleClass('glyphicon-collapse-up');
        $(this).toggleClass('glyphicon-collapse-down');

        container.find('> .form-group').toggleClass('dnone');
    });

    $(document).on('click', '[data-element-list="container"] [data-element-list="element"] [data-element-list="remove"]', function () {
        $(this).parents('[data-element-list="element"]').eq(0).remove();
    });

    function getMaxKey(elements){
        return Math.max.apply(Math, elements.map(function(){
            return $(this).data('elementKey')
        }).get());
    }

    // todo: проверить, как это удаление работает с другими, возможно, оставить только одно?
    $(document).on('click', '[data-remove-element]', function () {
        $($(this).data('removeElement')).remove();
    });

})();