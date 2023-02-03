(function () {
    /**
     * Prepare ip mask for field.
     * @param ipField
     */
    var handleMaskForIpField = function (ipField) {
        if (ipField.parent('.ip-container').eq(0).find('.toggle-ip-v6').get(0).checked) {
            ipField.inputmask('remove');
        } else {
            ipField.inputmask('9{1,3}.9{1,3}.9{1,3}.9{1,3}');
        }
    };


    $(function() {
        var manageContainer = $('.manage-ip-container');

        // Init masks
        manageContainer.find('.ip-container').each(function () {
            var field = $(this).find('.ip-control');
            handleMaskForIpField(field);
        });

        // Prepare mask toggle
        manageContainer.on('change', '.ip-container .toggle-ip-v6', function () {
            var ipField = $(this).parents('.ip-container').eq(0).find('.ip-control');
            handleMaskForIpField(ipField);
        });

        // Delete allowed ip
        manageContainer.on('click', '.ip-container .remove-ip', function (e) {
            if (confirm('Удалить этот IP адрес?')) {
                $(this).parents('.ip-container').eq(0).remove();
            }

            e.preventDefault();
        });


        // Add new ip field or current IP if it exists
        manageContainer.on('click', '.add-new a', function(e){
            var jThis = $(this), ipToAdd, addNew = true, newIpElement, newIpInput;

            ipToAdd = jThis.data('myip');
            if (!ipToAdd) {
                ipToAdd = '';
            }

            if (ipToAdd !== '') {
                manageContainer.find('.ip-control').each(function(){
                    if ($(this).val() == ipToAdd) {
                        addNew = false;
                        return false;
                    }
                });
            }

            if (addNew) {
                newIpElement = $(manageContainer.find('.add-container').html());
                newIpInput = newIpElement.find('.ip-control');
                newIpInput.val(ipToAdd);
                newIpInput.prop('disabled', false);
                if (ipToAdd.indexOf(':') >= 0) {
                    newIpElement.find('.toggle-ip-v6').prop('checked', true);
                }
                handleMaskForIpField(newIpInput);

                manageContainer.find('.ips-container').append(newIpElement);
            }

            e.preventDefault();
        });

        // Prepare submit form
        $('#admin_user_form').on('submit', function(){
            var myIp, allowSubmit, allIps;

            myIp = $('#add-my-ip').data('myip');
            allowSubmit = false;
            allIps = '';

            manageContainer.find('.ips-container .ip-control').each(function () {
                allIps += $(this).val();
                if ($(this).val() == myIp) {
                    allowSubmit = true;
                    return false;
                }
            });
            if (allIps === '') {
                allowSubmit = true;
            }
            if (!allowSubmit){
                allowSubmit = confirm('Внимание! Вашего IP нет в списке разрешенных. Есть опасность потерять доступ к админ панели! Продолжить?');
            }

            return allowSubmit;
        });
    });
})();
