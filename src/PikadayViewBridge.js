var pikadayBridge = function (presenterPath) {
    window.rhubarb.viewBridgeClasses.ViewBridge.apply(this, arguments);
};

pikadayBridge.prototype = new window.rhubarb.viewBridgeClasses.ViewBridge();
pikadayBridge.prototype.constructor = pikadayBridge;

pikadayBridge.prototype.modes = {
    textInput: 1,
    label: 2,
    calendar: 3
};

pikadayBridge.prototype.attachEvents = function () {
    var self = this;

    this.dateFormat = 'DD/MM/YYYY';

    this.hiddenNode = null;
    for (var i = 0, childNodes = this.viewNode.childNodes; i < childNodes.length; i++) {
        if (childNodes[i].nodeType == document.ELEMENT_NODE && childNodes[i].tagName == 'INPUT' && childNodes[i].type == 'hidden') {
            this.hiddenNode = childNodes[i];
        } else if (childNodes[i].nodeType == document.TEXT_NODE) {
            this.textNode = childNodes[i];
        }
    }

    this.oldValue = this.getValue();

    var daysSinceEpoch = (new Date()).getTime() / 8.64e7;
    var monthColours = null;
    var selectedMonth = null;
    var selectedYear = null;
    var options = {
        format: this.dateFormat,
        theme: this.model.pickerCssClassName,
        disableDayFn: function(date) {
            if (self.model.disablePast) {
                return date.getTime() / 8.64e7 < daysSinceEpoch - 1;
            }
        },
        onDraw:function(element) {
            if (self.model.getClassesForDays) {
                var days = element.el.querySelectorAll('td:not(.is-disabled):not(.is-empty)');

                if (selectedMonth !== element.calendars[0].month + 1 || selectedYear !== element.calendars[0].year ) {
                    selectedMonth = element.calendars[0].month + 1;
                    selectedYear = element.calendars[0].year;

                    self.raiseServerEvent('getClassesForDays', element.calendars[0].month + 1, element.calendars[0].year, function(classes) {
                        monthColours = classes;
                        updateAvailableDays(days);
                    });
                } else {
                    updateAvailableDays(days);
                }
            }
        }
    };

    function updateAvailableDays(days)
    {
        for (var i = 0, end = days.length; i < end; i++) {
            var day = days[i];
            if (monthColours[day.getAttribute('data-day') - 1] instanceof Array) {
                var toAddClasses = monthColours[day.getAttribute('data-day') - 1];
                for (var j = 0, jEnd = toAddClasses.length; j < jEnd; j++) {
                    day.classList.add(toAddClasses[j]);
                }
            }
        }
    }

    if (self.model.disablePast) {
        options.minDate = new Date();
    }

    switch (this.model.mode) {
        case this.modes.label:
            options.field = this.viewNode;
            options.onSelect = function (date) {
                var dateString = moment(date).format(self.dateFormat);
                self.hiddenNode.value = dateString;
                self.textNode.nodeValue = dateString;
                self.valueChanged();
            };
            break;
        case this.modes.calendar:
            options.field = self.hiddenNode;
            options.bound = false;
            options.container = this.viewNode;
            break;
        default:
            options.field = this.viewNode;
            break;
    }

    this.picker = new Pikaday(options);
};

pikadayBridge.prototype.valueChanged = function () {
    // Date Selected event will be called anytime a date is tapped, whether it's a new one or not
    this.raiseClientEvent('DateSelected', this.getValue());

    if (this.oldValue != this.getValue()) {
        // Only trigger events if the date has actually changed
        window.rhubarb.viewBridgeClasses.ViewBridge.prototype.valueChanged.apply(this);
        this.oldValue = this.getValue();
    }
};

pikadayBridge.prototype.getValue = function () {
    if (this.hiddenNode) {
        return this.hiddenNode.value;
    }

    return window.rhubarb.viewBridgeClasses.ViewBridge.prototype.getValue.apply(this);
};

pikadayBridge.prototype.setValue = function (value) {
    if (this.hiddenNode) {
        this.hiddenNode.value = value;
    }

    return window.rhubarb.viewBridgeClasses.ViewBridge.prototype.setValue.apply(this, arguments);
};

pikadayBridge.prototype.getDate = function () {
    var date = this.picker ? this.picker.getDate() : null;

    if (date == null) {
        date = moment(this.getValue(), this.dateFormat).toDate();
    }

    var d = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    d.setTime(d.getTime() + (-date.getTimezoneOffset() * 60 * 1000));

    return d;
};

pikadayBridge.prototype.setDate = function (date) {
    this.picker.setDate(date);
    this.valueChanged();
};

window.rhubarb.viewBridgeClasses.PikadayViewBridge = pikadayBridge;
