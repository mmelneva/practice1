$(document).ready(function () {

    (function () {
        $('form#reviewsform').validate({
            rules: {
                name: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                comment: {
                    required: true
                },
                privacy: {
                    required: true
                }
            },
            messages: {
                privacy: {
                    required: "Необходимо отметить"
                }
            },
            errorClass: "errors",
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            },
            submitHandler: function (form) {
                var formBlock = $(form);
                var parentBlock = formBlock.parent();
                parentBlock.addClass('wait');
                $.ajax({
                    //async: false,
                    url: formBlock.data('action'),
                    type: 'post',
                    data: formBlock.serialize(),
                    dataType: 'json',
                    success: function (response) {
                        if (response['status'] == 'OK') {
                            formBlock.get(0).reset();
                        }
                        parentBlock.find('.result').html(response['content']);
                        parentBlock.removeClass('wait');
                    }
                });

                return false;
            }
        });
    })();


    $('.pop_review').click(function (e) {
        e.preventDefault();
        var self = $(this);
        self
            .find('.privacy input[type="checkbox"]')
            .prop('checked', true)
            .removeClass('error');
        self.find('#privacy-error').remove();

        $('.popreview_' + self.data('review') + ' .popup, .popreview_' + self.data('review') + ' .bg0').fadeIn();
    });

    $('.popreview .close, #popcallback .bg0').click(function () {
        $('.popreview .popup:visible, .popreview .bg0:visible').fadeOut();
        return false;
    });

    if ($('.reviews-swiper-container .review-one').length > 1) {
        var reviewSwiper = new Swiper('.reviews-swiper-container', {
            slidesPerView: 2,
            spaceBetween: 30,
            loop: true,
            nextButton: '.reviews-slider-next',
            prevButton: '.reviews-slider-prev'
        });
    }


});
