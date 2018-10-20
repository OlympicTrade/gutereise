var CommonForm = function(){
    this.data = {};
    this.cookie = 'commonForm';

    this.init = function() {
        var jsonCart = $.cookie(this.cookie);
        this.data = jsonCart ? $.parseJSON(jsonCart) : {};
    };

    this.setData = function(data) {
        this.data = $.extend(this.data, data);

        var cartJson = null;

        if(this.data) {
            cartJson = JSON.stringify(this.data);
        }

        $.cookie(this.cookie, cartJson, {expires: 365, path: "/"});
    };

    this.getData = function() {
        return this.data;
    };
};

$.commonForm = new CommonForm();
$.commonForm.init();