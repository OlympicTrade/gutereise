var CommonForm = function(form){
    this.form = form;
    this.data = {};
    this.cookie = 'commonForm';
    this.timer = null;

    var cForm = this;

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

    this.updateForms = function() {
        if(cForm.data === {}) return;

        $('.common-form').each(function () {
            var form = $(this);
            if(cForm.form.attr('class') === form.attr('class')) return;

            $.each(cForm.data, function (key, val) {
                $('[name="' + key + '"]', form).val(val);
            });

            form.submit();
        });
    };

    this.init();

    $('input, select', form).on('keyup change', function () {
        clearTimeout(cForm.timer);
        cForm.timer = setTimeout(function() {
            cForm.setData({
                date:     $('[name="date"]', form).val(),
                time:     $('[name="time"]', form).val(),
                adults:   $('[name="adults"]', form).val(),
                children: $('[name="children"]', form).val()
            });

            cForm.updateForms();
        }, 200);
    });
};