// handle file uploading, when it can by uploaded with http address.
$(function() {
    $(document).on('submit', 'form', function (e) {
        var currentForm = $(this);
        currentForm.find('input[type="file"]').each(function () {
            var fileField, textField;
            fileField = $(this);
            textField = currentForm.find('input[type="text"][name="' + this.name + '"]');

            if (textField.length === 1) {
                ((fileField.get(0).files.length == 0) ? fileField : textField).attr('disabled', 'disabled');
            }
        });
    });
});