var pikadayBridge = function (presenterPath) {
    window.gcd.core.mvp.viewBridgeClasses.HtmlViewBridge.apply(this, arguments);
};

pikadayBridge.prototype = new window.gcd.core.mvp.viewBridgeClasses.HtmlViewBridge();
pikadayBridge.prototype.constructor = datePicker;

pikadayBridge.prototype.attachEvents = function () {
    this.picker = new Pikaday({
        field: this.node,
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

window.gcd.core.mvp.viewBridgeClasses.PikadayViewBridge = pikadayBridge;
