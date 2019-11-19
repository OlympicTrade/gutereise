var Currency = function () {
    this.currency = 'rub';

    var obj = this;

    this.setCurrency = function (code) {
        obj.code = code;
    };

    this.getCurrency = function () {
        return obj.code;
    };
};

$.currency = new Currency();