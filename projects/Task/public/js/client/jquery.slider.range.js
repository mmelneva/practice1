$(document).ready(function () {
    $('.slider_range').each(function () {
        var slider, fromInput, toInput, min, max, fromValue, toValue, changeInput, decimals, sliderStep, i;

        slider = $(this);
        fromInput = $(slider.data('from'));
        toInput = $(slider.data('to'));
        min = parseFloat(fromInput.data('border'));
        max = parseFloat(toInput.data('border'));
        fromValue = parseFloat(fromInput.val());
        toValue = parseFloat(toInput.val());

        decimals = slider.data('decimals');
        sliderStep = 1;
        for (i = 0; i < decimals; i += 1) {
            sliderStep = sliderStep / 10;
        }

        slider.slider({
            range: true,
            animate: true,
            min: min,
            max: max,
            step: sliderStep,
            values: [fromValue, toValue],
            slide: function (event, ui) {
                fromInput.val(ui.values[0].toFixed(decimals));
                toInput.val(ui.values[1].toFixed(decimals));
            }
        });

        changeInput = function () {
            var fromVal, toVal;
            fromVal = parseFloat(fromInput.val());
            toVal = parseFloat(toInput.val());

            if (isNaN(fromVal) || fromVal < min) {
                fromVal = min;
            }
            if (isNaN(toVal) || toVal > max) {
                toVal = max;
            }

            if (fromVal > toVal) {
                fromVal = toVal;
            }

            fromInput.val(fromVal);
            toInput.val(toVal);

            slider.slider('values', 0, fromVal);
            slider.slider('values', 1, toVal);
        };

        fromInput.change(changeInput);
        toInput.change(changeInput);
    });
});   