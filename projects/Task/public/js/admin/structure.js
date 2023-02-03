(function ($, document) {
    $(function () {
        // Show/hide alias for unique/not unique types
        var nodeTypeSelector = 'select[data-node-type]';
        var handleNodeType = function (nodeTypeSelect) {
            var isUnique = false;
            var selectedOption = $(nodeTypeSelector + ' option[value=' + nodeTypeSelect.val() + ']').eq(0);
            if (selectedOption.length === 1) {
                isUnique = selectedOption.data('unique');
            }
            $('#alias').parents('.form-group').css('display', isUnique ? 'none' : 'block');
        };
        $(document).on('change', 'select[data-node-type]', function () {
            handleNodeType($(this));
        });
        handleNodeType($(nodeTypeSelector));
    });
})(jQuery, document);
