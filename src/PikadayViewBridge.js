var pikadayBridge = function (presenterPath) {
    window.rhubarb.viewBridgeClasses.ViewBridge.apply(this, arguments);
};

pikadayBridge.prototype = new window.rhubarb.viewBridgeClasses.ViewBridge();
pikadayBridge.prototype.constructor = pikadayBridge;

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

    this.picker = new Pikaday({
        field: this.viewNode,
        format: this.dateFormat,
        onSelect: function (date) {
            if (self.hiddenNode) {
                var dateString = moment(date).format(self.dateFormat);
                self.hiddenNode.value = dateString;
                self.textNode.nodeValue = dateString;
            }
            self.valueChanged();
        }
    });
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
    var date = this.picker.getDate();

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
