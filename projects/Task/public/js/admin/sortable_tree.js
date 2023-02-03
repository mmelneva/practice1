// Sortable trees
$(function () {
    $('[data-sortable-wrapper]').each(function () {
        var sortableWrapper = $(this);

        var disableOn = function () {
            sortableWrapper.find('.sorting-control').removeClass('enabled');
        };

        var disableOff = function () {
            sortableWrapper.find('.sorting-control').addClass('enabled');
        };

        var makeSortable = function () {
            sortableWrapper.find('[data-sortable-group]').sortable({
                handle: '[data-sortable-handler]',
                change: function () {
                    disableOff();
                }
            });
        };

        var refreshList = function () {
            $.ajax({
                async: false,
                cache: false,
                url: sortableWrapper.data('sortableRefreshList'),
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    sortableWrapper.find('[data-sortable-container]').html(response['element_list']);
                    makeSortable();
                }
            });

            disableOn();
        };

        sortableWrapper.on('click', '[data-sortable-button]', function () {
            var element = $(this).parents('[data-element-id]').eq(0);
            switch ($(this).data('sortableButton')) {
                case 'up':
                    var previous = element.prev();
                    if (previous.length === 1) {
                        element.insertBefore(previous);
                    }
                    break;
                case 'down':
                    var next = element.next();
                    if (next.length === 1) {
                        element.insertAfter(next);
                    }
                    break;
                default:
                    console.log('unknown direction');
                    break;
            }

            disableOff();
        });

        sortableWrapper.on('click', '[data-sortable-update-positions]', function () {
            var positions = {};

            var positionOffset = parseInt(sortableWrapper.data('positionOffset'), 10);
            if (isNaN(positionOffset)) {
                positionOffset = 0;
            }

            var positionCounter = positionOffset;

            sortableWrapper.find('[data-sortable-container] [data-element-id]').each(function () {
                positionCounter += 1;
                positions[$(this).data('elementId')] = positionCounter;
            });

            $.ajax({
                async: false,
                url: $(this).data('sortableUpdatePositions'),
                type: 'PUT',
                data: {positions: positions}
            });

            refreshList();
        });

        sortableWrapper.on('click', '[data-sortable-refresh-list]', refreshList);

        makeSortable();
    });
});