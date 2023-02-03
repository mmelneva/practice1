(function ($) {
    var TooltipRule, TooltipElement, TooltipWorld, TooltipObserver, SweetTooltip;


    TooltipRule = (function () {
        /**
         * Tooltip rule to store CSS-selector for jQuery and other options.
         * @param options - hash. Need selector, title and positions (array).
         * @constructor
         */
        var TooltipRule = function (options) {
            var position;
            this.selector = options['selector'];
            this.title = options['title'] || 'Please, configure title';
            position = options['position'] || [];
            this.position = {
                horizontal: position[0] || 'center',
                vertical: position[1] || 'middle'
            };
        };

        return TooltipRule;
    })();


    TooltipElement = (function () {
        /**
         * Tooltip element - contains tooltip point element, element and rule (TooltipRule).
         *
         * @param tooltipPoint - jquery point element.
         * @param element - jquery element.
         * @param rule {TooltipRule} - TooltipRule.
         * @constructor
         */
        var TooltipElement = function (tooltipPoint, element, rule) {
            this.pointElement = tooltipPoint;
            this.element = element;
            this.rule = rule;
            this.updateDimensions();
        };

        /**
         * Get dimensions string to save or compare later.
         *
         * @returns {string}
         */
        TooltipElement.prototype.getDimensions = function () {
            return 'width:' + this.element.outerWidth() + '|' +
                'height:' + this.element.outerHeight() + '|' +
                'left:' + this.element.offset().left + '|' +
                'top:' + this.element.offset().top + '|';
        };


        /**
         * Update dimensions.
         */
        TooltipElement.prototype.updateDimensions = function () {
            this.dimensions = this.getDimensions();
        };


        /**
         * Check if dimensions were changed since last update.
         * @returns {boolean}
         */
        TooltipElement.prototype.dimensionsChanged = function () {
            return this.dimensions != this.getDimensions();
        };

        return TooltipElement;
    })();


    TooltipWorld = (function () {
        /**
         * Sweet tooltip object which handles tooltips.
         *
         * @param tooltipsData
         */
        var TooltipWorld = function (tooltipsData) {
            this.tooltipRules = this.buildTooltipRules(tooltipsData);
            this.tooltipElements = [];
            this.tooltipObserver = null;
            this.activeTooltipElement = null;
            this.tooltipMessage = null;
            this.pointContainer = null;
        };

        /**
         * Build tooltip rules according to tooltip data.
         *
         * @param tooltipsData
         * @returns {Array}
         */
        TooltipWorld.prototype.buildTooltipRules = function (tooltipsData) {
            var i, l, tooltipRules = [];
            for (i = 0, l = tooltipsData.length; i < l; i += 1) {
                tooltipRules.push(new TooltipRule(tooltipsData[i]));
            }

            return tooltipRules;
        };


        /**
         * Rebuild tooltip elements and show them. It will create new and hide old. It won't touch not changed tooltip
         * points.
         * It also shows tooltip message if needed.
         */
        TooltipWorld.prototype.rebuild = function () {
            var self = this, i, l, existingTooltipElement, oldTooltipElements = [], tooltipRule;

            for (i = 0, l = this.tooltipElements.length; i < l; i += 1) {
                existingTooltipElement = this.tooltipElements[i];
                if (!$.contains(document, existingTooltipElement.element[0])) {
                    existingTooltipElement.pointElement.remove();
                    if (existingTooltipElement == this.activeTooltipElement) {
                        this.activeTooltipElement = null;
                        this.renderTooltipMessage();
                    }
                    continue;
                } else if (existingTooltipElement.dimensionsChanged()) {
                    existingTooltipElement.updateDimensions();
                    this.renderTooltipPoint(existingTooltipElement);
                    if (existingTooltipElement == this.activeTooltipElement) {
                        this.renderTooltipMessage();
                    }
                }
                oldTooltipElements.push(existingTooltipElement);
            }

            this.tooltipElements = oldTooltipElements;
            for (i = 0, l = this.tooltipRules.length; i < l; i += 1) {
                tooltipRule = this.tooltipRules[i];
                $(tooltipRule.selector).each(function (index, domElement) {
                    var element, tooltipPoint, newTooltipElement;

                    element = $(domElement);
                    if (!element.data('sweetTooltipElement')) {
                        element.data('sweetTooltipElement', true);
                        tooltipPoint = $('<div class="sweet-tooltip-point">?</div>');

                        newTooltipElement = new TooltipElement(tooltipPoint, element, tooltipRule);
                        newTooltipElement.pointElement.click(function () {
                            if (self.activeTooltipElement === newTooltipElement) {
                                self.activeTooltipElement = null;
                            } else {
                                self.activeTooltipElement = newTooltipElement;
                            }
                            self.renderTooltipMessage();
                        });
                        self.tooltipElements.push(newTooltipElement);
                        self.renderTooltipPoint(newTooltipElement);
                    }
                });
            }
        };


        /**
         * Render all the tooltip points again.
         */
        TooltipWorld.prototype.renderTooltipPoints = function () {
            var i, l, tooltipElement;
            for (i = 0, l = this.tooltipElements.length; i < l; i += 1) {
                tooltipElement = this.tooltipElements[i];
                if (tooltipElement.dimensionsChanged()) {
                    tooltipElement.updateDimensions();
                    this.renderTooltipPoint(tooltipElement);
                    if (tooltipElement == this.activeTooltipElement) {
                        this.renderTooltipMessage();
                    }
                }
            }
        };


        /**
         * Render tooltip message.
         */
        TooltipWorld.prototype.renderTooltipMessage = function () {
            var tooltipElement, position, docWidth, docHeight, i, l, zIndex, maxZIndex = null;

            if (this.tooltipMessage === null) {
                this.tooltipMessage = $('<div class="sweet-tooltip-message"></div>');
                this.tooltipMessage.appendTo(document.body);
            }

            tooltipElement = this.activeTooltipElement;
            for (i = 0, l = this.tooltipElements.length; i < l; i += 1) {
                this.tooltipElements[i].pointElement.css({zIndex: ''});
                this.tooltipElements[i].pointElement.removeClass('active');
                zIndex = parseInt(this.tooltipElements[i].pointElement.css('zIndex'), 10);
                if (maxZIndex === null || zIndex > maxZIndex) {
                    maxZIndex = zIndex;
                }
            }

            if (tooltipElement && tooltipElement.pointElement.is(':visible')) {
                this.tooltipMessage.html(tooltipElement.rule.title);
                position = this.calculatePosition(tooltipElement);
                docWidth = $(document).width();
                docHeight = $(document).height();

                if (position['left'] <= docWidth / 2) {
                    this.tooltipMessage.css({left: position['left'], right: 'auto'});
                } else {
                    this.tooltipMessage.css({left: position['left'] - this.tooltipMessage.outerWidth(), right: 'auto'});
                }

                if (position['top'] <= docHeight / 2) {
                    this.tooltipMessage.css({top: position['top'], bottom: 'auto'});
                } else {
                    this.tooltipMessage.css({top: position['top'] - this.tooltipMessage.outerHeight(), bottom: 'auto'});
                }
                this.tooltipMessage.addClass('visible');
                this.tooltipMessage.css({zIndex: maxZIndex + 1});
                tooltipElement.pointElement.css({zIndex: maxZIndex + 2});
                tooltipElement.pointElement.addClass('active');
            } else {
                this.tooltipMessage.html('');
                this.tooltipMessage.removeClass('visible');
            }
        };


        /**
         * Render tooltip point for tooltipElement.
         *
         * @param tooltipElement {TooltipElement}
         */
        TooltipWorld.prototype.renderTooltipPoint = function (tooltipElement) {
            var position, widthBorder, heightBorder;
            widthBorder = $(document).width();
            heightBorder = $(document).height();

            if (this.pointContainer === null) {
                this.pointContainer = $('<div class="sweet-tooltip-points-container"></div>');
                this.pointContainer.appendTo(document.body);
            }

            position = this.calculatePosition(tooltipElement);
            if (!$.contains(this.pointContainer[0], tooltipElement.pointElement[0])) {
                tooltipElement.pointElement.appendTo(this.pointContainer);
            }
            tooltipElement.pointElement.css({left: position['left'], top: position['top']});

            if (!tooltipElement.element.is(':visible')) {
                tooltipElement.pointElement.css({display: 'none'});
            } else {
                tooltipElement.pointElement.css({display: ''});
            }

            if (position['left'] + tooltipElement.pointElement.outerWidth(true) > widthBorder ||
                position['left'] < 0 ||
                position['top'] + tooltipElement.pointElement.outerHeight(true) > heightBorder ||
                position['top'] < 0) {
                tooltipElement.pointElement.css({display: 'none'});
            }
        };


        /**
         * Calculate position for tooltip.
         *
         * @param tooltipElement {TooltipElement}
         * @returns {{left: *, top: *}}
         */
        TooltipWorld.prototype.calculatePosition = function (tooltipElement) {
            var left, top, offset;
            offset = tooltipElement.element.offset();

            switch (tooltipElement.rule.position.horizontal) {
                case 'left':
                    left = offset.left;
                    break;
                case 'right':
                    left = offset.left + tooltipElement.element.outerWidth();
                    break;
                case 'center':
                    left = offset.left + tooltipElement.element.outerWidth() / 2;
                    break;
            }

            switch (tooltipElement.rule.position.vertical) {
                case 'top':
                    top = offset.top;
                    break;
                case 'bottom':
                    top = offset.top + tooltipElement.element.outerHeight();
                    break;
                case 'middle':
                    top = offset.top + tooltipElement.element.outerHeight() / 2;
                    break;
            }

            return {left: left, top: top};
        };


        /**
         * Clear all the tooltips.
         */
        TooltipWorld.prototype.clear = function () {
            var i, l;
            for (i = 0, l = this.tooltipElements.length; i < l; i += 1) {
                this.tooltipElements[i].element.data('sweetTooltipElement', false);
                this.tooltipElements[i].pointElement.remove();
            }

            this.tooltipElements = [];
            this.activeTooltipElement = null;

            if (this.tooltipMessage) {
                this.tooltipMessage.remove();
                this.tooltipMessage = null;
            }
        };


        return TooltipWorld;
    })();


    TooltipObserver = (function () {
        /**
         * Observer for tooltips.
         * It observes DOM changes and do callback on change.
         *
         * @param callback
         * @param delay
         */
        var TooltipObserver = function (callback, delay) {
            this.callback = callback;
            this.delay = delay;
            this.mutationObserver = null;
        };


        /**
         * Start to observe DOM changes.
         */
        TooltipObserver.prototype.start = function () {
            var observerTimeout, self = this;

            if (this.mutationObserver !== null) {
                this.stop();
            }

            this.mutationObserver = new MutationObserver(function () {
                if (observerTimeout) {
                    clearTimeout(observerTimeout);
                }
                observerTimeout = setTimeout(function () {
                    self.callback();
                }, self.delay);
            });

            this.mutationObserver.observe(document, {attributes: true, childList: true, subtree: true});
        };


        /**
         * Stop observing.
         */
        TooltipObserver.prototype.stop = function () {
            this.mutationObserver.disconnect();
            this.mutationObserver = null;
        };

        return TooltipObserver;
    })();


    SweetTooltip = (function () {
        /**
         * Sweet tooltip.
         *
         * @param tooltipsData
         * @param storageStatusIdentity
         * @constructor
         */
        var SweetTooltip = function (tooltipsData, storageStatusIdentity) {
            this.tooltipWorld = new TooltipWorld(tooltipsData || []);
            this.storageStatusIdentity = storageStatusIdentity || 'sweet-tooltip-status';
            this.active = false;
            this.toggle = null;
            this.onChange = [];

            this.initStorage();
            this.initToggle();
            this.change();
        };


        /**
         * Init browser storage.
         */
        SweetTooltip.prototype.initStorage = function () {
            var self = this, status;

            status = localStorage.getItem(this.storageStatusIdentity);
            if (status == 1 || status === null) {
                this.show();
            }

            this.onChange.push(function () {
                localStorage.setItem(self.storageStatusIdentity, self.active ? 1 : 0);
            });
        };


        /**
         * Init toggle.
         */
        SweetTooltip.prototype.initToggle = function () {
            var self = this;
            this.toggle = $('<div class="sweet-tooltip-toggle" title="Включить/отключить подсказки">?</div>');
            this.toggle.appendTo(document.body);
            this.toggle.click(function () {
                if (self.active) {
                    self.hide();
                } else {
                    self.show();
                }
            });

            this.onChange.push(function () {
                if (self.active) {
                    self.toggle.removeClass('off');
                } else {
                    self.toggle.addClass('off');
                }
            });
        };


        /**
         * Toggle change callbacks.
         */
        SweetTooltip.prototype.change = function () {
            var i, l;
            for (i = 0, l = this.onChange.length; i < l; i += 1) {
                this.onChange[i]();
            }
        };


        /**
         * Show tooltips and start watching dom changes and resize.
         */
        SweetTooltip.prototype.show = function () {
            this.tooltipWorld.rebuild();
            this.startDOMObserve();
            this.startResizeObserve();
            this.startScrollObserve();

            this.active = true;
            this.change();
        };


        /**
         * Hide all the tooltips and stop watching.
         */
        SweetTooltip.prototype.hide = function () {
            this.stopDOMObserve();
            this.stopResizeObserve();
            this.stopScrollObserve();
            this.tooltipWorld.clear();

            this.active = false;
            this.change();
        };


        /**
         * Start DOM observing to rebuild tooltips.
         */
        SweetTooltip.prototype.startDOMObserve = function () {
            var self = this;

            this.stopDOMObserve();
            this.domObserve = true;
            this.tooltipObserver = new TooltipObserver(function () {
                if (self.domObserve) {
                    self.tooltipWorld.rebuild();
                }
            }, 10);
            this.tooltipObserver.start();
        };


        /**
         * Stop DOM observing to rebuild tooltips.
         */
        SweetTooltip.prototype.stopDOMObserve = function () {
            if (this.tooltipObserver) {
                this.domObserve = false;
                this.tooltipObserver.stop();
                this.tooltipObserver = null;
            }
        };


        /**
         * Start to observe window resizing to rebuild tooltips.
         */
        SweetTooltip.prototype.startResizeObserve = function () {
            var self = this, resizeTimeout;

            this.resizeCallback = function () {
                if (resizeTimeout) {
                    clearTimeout(resizeTimeout);
                }
                resizeTimeout = setTimeout(function () {
                    self.tooltipWorld.renderTooltipPoints();
                }, 10);
            };

            this.stopResizeObserve();
            $(window).on('resize', this.resizeCallback);
        };


        /**
         * Stop to observe window resizing to rebuild tooltips.
         */
        SweetTooltip.prototype.stopResizeObserve = function () {
            $(window).off('resize', this.resizeCallback);
        };


        SweetTooltip.prototype.startScrollObserve = function () {
            var self = this, scrollTimeout;

            this.scrollCallback = function () {
                if (scrollTimeout) {
                    clearTimeout(scrollTimeout);
                }
                scrollTimeout = setTimeout(function () {
                    self.tooltipWorld.renderTooltipPoints();
                }, 10);
            };

            this.stopScrollObserve();
            $(window).on('scroll', this.scrollCallback);
        };


        SweetTooltip.prototype.stopScrollObserve = function () {
            $(window).off('scroll', this.scrollCallback);
        };


        return SweetTooltip;
    })();


    window.SweetTooltip = SweetTooltip;
})(jQuery);