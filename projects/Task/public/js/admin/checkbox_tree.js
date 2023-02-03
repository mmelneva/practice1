(function ($) {

    function getElementCheckboxList(groupCheckbox) {
        return $(groupCheckbox).parents('.group').eq(0).find('.element-checkbox');
    }

    function getCheckedElementCheckboxList(groupCheckbox) {
        return $(groupCheckbox).parents('.group').eq(0).find('.element-checkbox:checked');
    }

    function updateGroupCheckboxes(categoryTree) {
        categoryTree.find('.group-checkbox').each(function () {
            var groupCheckbox, elementCheckboxList, checkedElementCheckboxList;

            groupCheckbox = this;
            elementCheckboxList = getElementCheckboxList(groupCheckbox);
            checkedElementCheckboxList = getCheckedElementCheckboxList(groupCheckbox);

            if (elementCheckboxList.length !== 0 && elementCheckboxList.length == checkedElementCheckboxList.length) {
                groupCheckbox.checked = true;
                groupCheckbox.indeterminate = false;
            } else if (checkedElementCheckboxList.length === 0) {
                groupCheckbox.checked = false;
                groupCheckbox.indeterminate = false;
            } else {
                groupCheckbox.checked = false;
                groupCheckbox.indeterminate = true;
            }
        });
    }


    $('.checkbox-tree').each(function () {
        var categoryTree = $(this);

        categoryTree.on('change', '.element-checkbox', function () {
            updateGroupCheckboxes(categoryTree);
        });

        categoryTree.on('change', '.group-checkbox', function () {
            var groupCheckbox, elementCheckboxList;

            groupCheckbox = this;
            elementCheckboxList = getElementCheckboxList(groupCheckbox);

            if (groupCheckbox.checked) {
                elementCheckboxList.prop('checked', true);
            } else {
                elementCheckboxList.removeAttr('checked');
            }

            updateGroupCheckboxes(categoryTree);
        });

        updateGroupCheckboxes(categoryTree);


        categoryTree.on('click', '.group-toggle', function () {
            $(this).parents('.group').eq(0).find('.group-content').eq(0).toggleClass('visible');
        });
    });
})(jQuery);