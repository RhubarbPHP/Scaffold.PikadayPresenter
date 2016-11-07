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

    var options = {format: this.dateFormat};

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
