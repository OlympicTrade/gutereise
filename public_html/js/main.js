$document = $(document);

$(window).ready(function() {
    setTimeout(function() {
        let body = $('body');
        initPopups();
        initElements(body);
        initComments(body);
        initSidebar();
        initRegionSettings();
    }, 1);
});

function initRegionSettings() {
    let flags = $('.language .flags', '#header');
    $('.flag', flags).on('click', function () {
        if($(this).hasClass('active')) return;

        let lang = $(this).data('lang');
        $.cookie('lang', lang, {expires: 365, path: "/"});
        location.reload();
    });

    $('.currency select', '#header').on('change', function () {
        $.cookie('currency', $(this).val(), {expires: 365, path: "/"});
        location.reload();
    });
}

function initGallery(box) {
    let gallery = $('.gallery .list', box);

    gallery.lightSlider({
        gallery: true,
        item: 1,
        thumbItem: 7,
        thumbMargin: 4,
        slideMargin: 0,
    });
}

function initSidebar() {
    let sidebar = $('.sidebar');

    sidebar.sidebar({
        margin: 20,
        nav: $('#header')
    });

    $('.widget.catalog .show-all', sidebar).on('click', function() {
        $(this).siblings('.h-box').slideDown(300, function () {
            sidebar.sidebar().update();
        });
        $(this).remove();
    });
}

function initComments(box) {
    box.each(function () {
        let container = $(this);
        let form = $('.form', container);

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
    let url = $.aptero.url();
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

    initGallery(box);

    $('.anchor', box).on('click', function () {
        $.aptero.scrollTo($(this).attr('href'), 300);
        return false;
    });

    $('.select-group', box).each(function () {
        let group = $(this);
        let vals = $('span', group);
        let input = $('input', group);

        let setActive = function (val) {
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

        let initVal = input.val() ? input.val() : vals.eq(0).data('value');
        input.val('');
        setActive(initVal);
    });

    $('input[type="checkbox"]', box).each(function() {
        let checkbox = $(this);
        let label = checkbox.closest('label');

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
        let radio = $(this);
        let label = radio.closest('label');

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
        let el = $(this);
        let input = $('input', el);
        let incr = $('<div class="incr"></div>').appendTo(el);
        let decr = $('<div class="decr"></div>').appendTo(el);

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
            let count = parseInt(input.val());
            count = (isNaN(count) ? 0 : count) + 1;
            let max = input.data('max') ? parseInt(input.data('max')) : 999;
            if(count > max) {
                return false;
            }

            input.val(count);
            checkMinMax();
        });

        decr.on('click', function() {
            let count = parseInt(input.val());
            count = (isNaN(count) ? 0 : count) - 1;
            let min = input.data('min') !== '' ? parseInt(input.data('min')) : 1;
            if(count < min) {
                return false;
            }

            input.val(count);
            checkMinMax();
        });

        input.on('update', function() {
            checkMinMax();
        });

        let timer = null;
        $('.incr, .decr', el).on('click', function () {
            if(timer) clearTimeout(timer);
            timer = setTimeout(function() {
                input.trigger('change');
            }, 150);
        });
    });

    $('.std-input, .std-textarea, .std-select', '.element', box).each(function () {
        let el = $(this);
        let element = $(this).closest('.element');

        if(el.attr('placeholder') !== undefined && el.attr('placeholder') !== '') {
            element.addClass('not-empty');
            return;
        }

        if(el.hasClass('std-select')) {
            element.addClass('not-empty');
            return;
        }

        el.on('focus', function () {
            $(this).closest('.element').addClass('focus');
        });

        el.on('focusout', function () {
            $(this).closest('.element').removeClass('focus');
        });

        el.on('keyup check', function () {
            if($(this).val()) {
                element.addClass('not-empty');
            } else {
                element.removeClass('not-empty');
            }
        });

        el.trigger('keyup');
    });

    $('.short-list').each(function () {
        let box = $(this);
        let slider = $('.slider', box).lightSlider({
            item:   3,
            pager:  false,
            loop:   false,
            easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
            speed:  600,
            slideMove: 3,
            slideMargin: 20,
            responsive : [
                {
                    breakpoint:800,
                    settings: {
                        item:3,
                        slideMove:1,
                        slideMargin:6,
                    }
                },
                {
                    breakpoint:480,
                    settings: {
                        item:2,
                        slideMove:1
                    }
                }
            ]
        });

        $('.prev', box).on('click', function() { slider.goToPrevSlide(3); });
        $('.next', box).on('click', function() { slider.goToNextSlide(3); });
    });

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
        let el = $(this);
        let type = el.hasClass('popup-img') ? 'image' : 'ajax';

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
                    /*slide.$slide.on('click', function(e) {
                        if($(e.target).hasClass('fancybox-slide')) {
                            $.fancybox.close()
                        }
                    });*/

                    initElements(slide.$slide);
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