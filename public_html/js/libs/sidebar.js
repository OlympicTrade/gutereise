let Sidebar = function () {
    this.container = null;
    this.sidebar = null;
    this.slider = null;
    this.nav = null;
    this.navH = 0;
    this.margin = 0;
    this.sidebarTop = 0;
    this.botLine = 0;
    this.sidebarH = 0;
    this.sliderH = 0;
    this.windowH = 0;
    this.oldScrollTop = 0;

    let obj = this;
    let uodateTm = null;

    this.update = function() {
        clearTimeout(uodateTm);
        uodateTm = setTimeout(function () {
            obj.slider.css({
                width: obj.sidebar.innerWidth(),
            });

            if(obj.slider.css('top') == '0px') {
                obj.slider.css({
                    top: 0,
                    left: 0,
                    position: 'relative',
                    //overflow: 'hidden'
                });
            }

            obj.sidebarTop = obj.sidebar.offset().top;
            obj.navH    = obj.nav.outerHeight();
            obj.windowH = $(window).height();
            obj.sliderH = obj.slider.innerHeight() + 20 + obj.margin;
            obj.botLine = obj.sidebar.innerHeight() - obj.sliderH;

            $(window).trigger('scroll');
        }, 100);

        //$(window).trigger('scroll');
    };

    this.init = function(el, opts) {
        opts = $.extend({
            margin: 20,
            nav: $('#nav')
        }, opts);

        this.margin = opts.margin;
        this.nav = opts.nav;
        this.sidebar = el;
        this.container = el.parent();
        this.slider = el.children();

        this.update();

        let top;

        $(window).on('scroll', function() {
            let newScroll = $(this).scrollTop();
            let sizeRevert = (obj.windowH - obj.navH - obj.margin) > obj.sliderH;

            if(!sizeRevert) {
                if(newScroll < obj.oldScrollTop) {
                    if(newScroll > (obj.sidebarTop + top - obj.margin)) {
                        obj.oldScrollTop = newScroll;
                        return;
                    }
                    top = newScroll - (obj.sidebarTop - obj.navH - obj.margin);
                } else {
                    if(newScroll < (obj.sidebarTop + top + obj.sliderH - obj.windowH + obj.margin)) {
                        obj.oldScrollTop = newScroll;
                        return;
                    }
                    top = newScroll - (obj.sidebarTop + obj.sliderH - obj.windowH + obj.margin);
                }
            } else {
                if(newScroll < obj.oldScrollTop) {
                    top = newScroll - (obj.sidebarTop - obj.navH - obj.margin);
                } else {
                    if(newScroll < (obj.sidebarTop + top - obj.navH - obj.margin)) {
                        obj.oldScrollTop = newScroll;
                        return;
                    }
                    top = newScroll - (obj.sidebarTop - obj.navH - obj.margin);
                }
            }

            top = Math.min(top, obj.botLine);
            top = Math.max(top, 0);

            obj.slider.css({top: top});
            obj.oldScrollTop = newScroll;
        }).trigger('scroll');
    };
};

$.fn.sidebar = function (options) {
    let init = function(el) {
        let sl = el.data('ap-sidebar');

        if (sl === undefined || sl === '') {
            sl = new Sidebar();
            sl.init(el, options);
            el.data('ap-sidebar', sl);
        }

        return sl;
    };

    if($(this).length === 1) {
        return init($(this));
    }

    $(this).each(function () {
        init($(this));
    });

    return this;
};
