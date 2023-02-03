(function ($) {
    $(function () {

        var galleryImagesContainer = $('.gallery-images-container');

        // Confirmation hook
        $(galleryImagesContainer).on('click', '[data-confirm]', function (e) {
            if (!confirm($(this).data('confirm'))) {
                e.preventDefault();
                e.stopImmediatePropagation();
            }
        });

        // Toggle flags in list hook
        $(galleryImagesContainer).on('click', 'a.toggle-flag', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            var liWrapper = galleryImagesContainer.find('li').has(this);
            var currentLink = $(this), toggleContainer, imageFieldsContainer;
            toggleContainer = currentLink.parent();
            currentLink.remove();
            toggleContainer.html('<span class="glyphicon glyphicon-refresh"></span>');

            imageFieldsContainer = liWrapper.find('.image-fields-container');
            $.ajax({
                url: this.href,
                type: currentLink.data('method'),
                success: function (result) {
                    toggleContainer.replaceWith(result['new_icon']);
                    imageFieldsContainer.replaceWith(result['new_image_fields']);

                    prettyPhotoInit();
                }
            });
        });

        // Remove existing image from gallery.
        $(galleryImagesContainer).on('click', 'a.remove', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            var currentLink = $(this), toggleContainer;
            toggleContainer = currentLink.parent();
            currentLink.remove();
            toggleContainer.html('<span class="glyphicon glyphicon-refresh"></span>');

            $.ajax({
                url: this.href,
                type: currentLink.data('method'),
                success: function (result) {
                    var newLiElements = galleryImagesContainer.find('.gallery-images').find('.new');
                    newLiElements.hide();
                    galleryImagesContainer.append(newLiElements);
                    galleryImagesContainer.find('.gallery-images').replaceWith(result['new_gallery_image_list']);
                    galleryImagesContainer.find('.gallery-images').append(newLiElements);
                    newLiElements.show();

                    prettyPhotoInit();
                }
            });
        });

        // Remove new image from gallery.
        $(galleryImagesContainer).on('click', 'a.remove-new', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            galleryImagesContainer.find('li').has(this).remove();
        });

        galleryImagesContainer.on('click', '.add-new a', function (e) {
            e.preventDefault();

            var jThis = $(this), imagesList, linkContainer;

            imagesList = jThis.parents('.gallery-images-container');
            linkContainer = imagesList.find('.add-new');

            linkContainer.addClass('ajax-loader');
            $.ajax({
                async: false,
                url: this.href,
                data: { generate_id: imagesList.find('li').length + 1 },
                success: function (data) {
                    imagesList.find('.gallery-images').append(data);
                    linkContainer.removeClass('ajax-loader');
                }
            });
        });

        galleryImagesContainer.on('click', '.image-fields-toggle > img', function () {
            var jThis = galleryImagesContainer.find('.image-fields-toggle').has(this), wrapper;
            wrapper = jThis.next('.image-fields-wrapper');

            var liWrapper = galleryImagesContainer.find('li').has(wrapper);

            liWrapper.addClass('expanded');
            liWrapper.find('input.opened-hidden').val(1);
        });

        galleryImagesContainer.on('click', '.collapse', function () {
            var wrapper = galleryImagesContainer.find('.image-fields-wrapper').has(this);

            var liWrapper = galleryImagesContainer.find('li').has(wrapper);

            if (!liWrapper.hasClass('new')) {
                liWrapper.removeClass('expanded');
                liWrapper.find('input.opened-hidden').val(0);
            } else {
                liWrapper.remove();
            }
        });
    });
})(jQuery);