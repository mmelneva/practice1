$(document).ready(function () {

    var getRowsCount, redirectsContainer = $('#redirectsContainer')
    // Add button click handler
        .on('click', '[data-rule-action="add"]', function () {
            var template = redirectsContainer.find('#templateRow'),
                clone = template
                    .clone()
                    .removeClass('hide')
                    .removeAttr('id')
                    .appendTo(redirectsContainer.find('[data-element-list="rules"]'));

            clone.find('input').removeAttr('disabled');

            // Update the name attributes
            $.each(clone.find('input'), function () {
                $(this).attr('name', $(this).attr('name').replace('%index%', getRowsCount() - 1));
            });
        })

        // Remove button click handler
        .on('click', '[data-rule-action="remove"]', function () {
            var parentRow = $(this).parents('.row');

            console.log(getRowsCount());

            if (getRowsCount() == 1) {
                parentRow.find('input').val('');
            } else {
                parentRow.remove();
            }
        });

    getRowsCount = function () {
        return redirectsContainer.find('[data-element-list="rules"] .row').length;
    }
});