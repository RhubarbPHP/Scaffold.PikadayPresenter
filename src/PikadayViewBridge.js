var pikadayBridge = function (presenterPath) {
    window.rhubarb.viewBridgeClasses.HtmlViewBridge.apply(this, arguments);
};

pikadayBridge.prototype = new window.rhubarb.viewBridgeClasses.HtmlViewBridge();
pikadayBridge.prototype.constructor = pikadayBridge;

pikadayBridge.prototype.attachEvents = function () {
    var self = this;

    this.picker = new Pikaday({
        field: this.viewNode,
        format: 'DD/MM/YYYY',
        onSelect: function () {
            self.valueChanged();
        }
    });
};

pikadayBridge.prototype.getDate = function () {
    var date = picker.getDate();

    var d = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    d.setTime(d.getTime() + (-date.getTimezoneOffset() * 60 * 1000));

    return d;
};

pikadayBridge.prototype.setDate = function (date) {
    this.picker.setDate(date);
    this.valueChanged();
};

window.rhubarb.viewBridgeClasses.PikadayViewBridge = pikadayBridge;
