$(function() {
    var body = $('body');
    initPopups();
    initElements(body);
    initComments(body);
    initSidebar();
    //initMetric();
});

function initSidebar() {
    var sidebar = $('.sidebar').sidebar({
        margin: 20,
        nav: $('#header')
    });
}

function initComments(box) {
    box.each(function () {
        var container = $(this);
        var form = $('.form', container);

        form.formSubmit({
            success: function(resp, form){
                $('.list', container).prepend(resp.html);

                //form.reset();
                /*$('.form-box', form).fadeOut(200, function(){
                    $('.success-box', form).fadeIn(200);
                });*/
            }
        });
    });
}

function initMetric() {
    var url = $.aptero.url();
    url.init();

    $.ajax({
        url: '/metrics/init/',
        method: 'post',
        data: {
            query: url.getParams(),
            source: 'desktop'
        }
    });
}

function initElements(box) {
    $('[name="phone"]', box).inputmask('+7 (999) 999-99-99');

    $('.anchor', box).on('click', function () {
        var scrollTo = $($(this).attr('href')).offset().top - 100;

        $('html, body').animate({scrollTop: scrollTo}, 300);

        return false;
    });

    $('.select-group', box).each(function () {
        var group = $(this);
        var vals = $('span', group);
        var input = $('input', group);

        var setActive = function (val) {
            if(input.val() == val) { return; }

            input.val(val).trigger('change');
            $('span[data-value="' + val + '"]', group)
                .addClass('selected')
                .siblings()
                    .removeClass('selected');
        };

        vals.on('click', function () {
            setActive($(this).data('value'));
        });

        var initVal = input.val() ? input.val() : vals.eq(0).data('value');
        input.val('');
        setActive(initVal);
    });

    $('input[type="checkbox"]', box).each(function() {
        var checkbox = $(this);
        var label = checkbox.closest('label');

        label.addClass('checkbox');

        if(checkbox.is(":checked")) {
            label.addClass('checked');
        }

        label.on('click', function() {
            if(checkbox.is(":checked")) {
                label.addClass('checked');
            } else {
                label.removeClass('checked');
            }
        });
    });

    $('input[type="radio"]', box).each(function() {
        var radio = $(this);
        var label = radio.closest('label');

        label.addClass('radio');
        label.attr('data-name', radio.attr('name'));

        if(radio.is(":checked")) {
            label.addClass('checked');
        }

        label.on('click', function() {
            $('label[data-name="' + label.data('name') + '"]')
                .removeClass('checked');
            label.addClass('checked');
        })
    });

    $('.std-counter', box).each(function() {
        var el = $(this);
        var input = $('input', el);
        var incr = $('<div class="incr"></div>').appendTo(el);
        var decr = $('<div class="decr"></div>').appendTo(el);

        function checkMinMax() {
            if(parseInt(input.val()) >= parseInt(input.data('max'))) {
                incr.addClass('incr-max');
            } else {
                incr.removeClass('incr-max');
            }

            if(parseInt(input.val()) <= parseInt(input.attr('min'))) {
                decr.addClass('decr-min');
            } else {
                decr.removeClass('decr-min');
            }
        }

        checkMinMax();

        incr.on('click', function() {
            var count = parseInt(input.val());
            count = (isNaN(count) ? 0 : count) + 1;
            var max = input.data('max') ? parseInt(input.data('max')) : 999;
            if(count > max) {
                return false;
            }

            input.val(count);
            checkMinMax();
        });

        decr.on('click', function() {
            var count = parseInt(input.val());
            count = (isNaN(count) ? 0 : count) - 1;
            var min = input.data('min') !== '' ? parseInt(input.data('min')) : 1;
            if(count < min) {
                return false;
            }

            input.val(count);
            checkMinMax();
        });

        input.on('update', function() {
            checkMinMax();
        });

        var timer = null;
        $('.incr, .decr', el).on('click', function () {
            if(timer) clearTimeout(timer);
            timer = setTimeout(function() {
                input.trigger('change');
            }, 150);
        });
    });

    /*$('.element', box).each(function () {
        $('input, textarea, select', $(this)).on('focus', function () {
            $(this).closest('.element').addClass('focus');
        }).on('focusout', function () {
            $(this).closest('.element').removeClass('focus');
        }).on('keyup', function () {
            var element = $(this).closest('.element');
            if($(this).val()) {
                element.addClass('not-empty');
            } else {
                element.removeClass('not-empty');
            }
        }).trigger('keyup');
    });*/

    $.config.datepicker = {
        clearText: 'Очистить',
        clearStatus: '',
        closeText: 'Закрыть',
        closeStatus: '',
        prevText: '',
        prevStatus: '',
        nextText: '',
        nextStatus: '',
        currentText: 'Сегодня',
        currentStatus: '',
        monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь', 'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
        monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн', 'Июл','Авг','Сен','Окт','Ноя','Дек'],
        monthStatus: '',
        yearStatus: '',
        weekHeader: 'Не',
        weekStatus: '',
        dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
        dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
        dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
        dayStatus: 'DD',
        dateStatus: 'D, M d',
        dateFormat: 'dd.mm.yy',
        firstDay: 1,
        initStatus: '',
        isRTL: false,
        minDate: 1,
        //maxDate: 92
    };

    $('.datepicker', box).datepicker($.config.datepicker);
}

function initPopups() {
    $('body').on('click', '.popup, .popup-img', function() {
        var el = $(this);
        var type = el.hasClass('popup-img') ? 'image' : 'ajax';

        if(el.hasClass('popup-img')) {
            $.fancybox.open({
                src:  el.attr('href'),
                type: 'image',
                opts: {
                    afterLoad: function(e, slide) {
                        slide.$slide.on('click', function(e) {
                            if($(e.target).hasClass('fancybox-slide')) {
                                $.fancybox.close()
                            }
                        });
                    }
                }
            });

            return false;
        }

        $.fancybox.open({
            src: el.attr('href'),
            type: type,
            opts: {
                ajax: {
                    settings: {
                        data: el.data()
                    }
                },
                afterLoad: function(e, slide) {
                    slide.$slide.on('click', function(e) {
                        if($(e.target).hasClass('fancybox-slide')) {
                            $.fancybox.close()
                        }
                    });

                    initElements(e.$refs.slider);
                }
            }
        });

        return false;
    });
}

function getGoogleMapStyle() {
    return [
        {
            "featureType": "administrative",
            "elementType": "labels.text.fill",
            "stylers": [
                {
                    "color": "#444444"
                }
            ]
        },
        {
            "featureType": "landscape",
            "elementType": "all",
            "stylers": [
                {
                    "color": "#f2f2f2"
                }
            ]
        },
        {
            "featureType": "poi",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "road",
            "elementType": "all",
            "stylers": [
                {
                    "saturation": -100
                },
                {
                    "lightness": 45
                }
            ]
        },
        {
            "featureType": "road.highway",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "simplified"
                }
            ]
        },
        {
            "featureType": "road.arterial",
            "elementType": "labels.icon",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "transit",
            "elementType": "all",
            "stylers": [
                {
                    "visibility": "off"
                }
            ]
        },
        {
            "featureType": "water",
            "elementType": "all",
            "stylers": [
                {
                    "color": "#d2d2d2"
                },
                {
                    "visibility": "on"
                }
            ]
        }
    ];
}