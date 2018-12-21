var Languages = function () {
    this.code = 'ru';

    var obj = this;

    this.setLanguage = function (code) {
        obj.code = code;
    };

    this.getLanguage = function () {
        return obj.code;
    };
};

$.languages = new Languages();