(function () {
    $.validator.addMethod('phoneNumber', function (value) {
        // validation for phone number. server side: app/services/ValidationRule/SpecialData.php, method: validatePhone
        if (value != '') {
            return /^\+7 \(\d{3}\) (\d{3}-\d{2}-\d{2})$/.test(value);
        } else {
            return true;
        }
    }, 'Please enter a valid phone number.');
})();