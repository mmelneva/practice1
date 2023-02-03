(function () {

        window.useYandexCounter = function (callback) {
            if (typeof yaCounter38883690 != 'undefined') {
                callback(yaCounter38883690);
            }
        };

        window.useYandexGoal = function (goal) {
            console.log('y:' + goal);
            useYandexCounter(function (ya) {
                ya.reachGoal(goal);
            });
        };

        window.useGoogleTrackEvent = function (category, action) {
            console.log('g:' + category + '.' + action);
            if(typeof ga != 'undefined') {
                ga(
                    'send',
                    'event',
                    category,
                    action
                );
            }
        };

        window.countersListEvent = function(action, category) {
            useYandexGoal(action);
            if(typeof category != 'undefined') {
                useGoogleTrackEvent(category, action);
            }
        }

    })();