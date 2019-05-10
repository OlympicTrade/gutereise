$.fn.sidebar = function (opts) {
    opts = $.extend({
        sidebar: this,
        margin: 20,
        nav: $('#nav')
    }, opts);

    var Sidebar = function () {
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

        var obj = this;

        this.update = function() {
            setTimeout(function () {
                obj.slider.css({
                    top: 0,
                    left: 0,
                    position: 'relative',
                    width: obj.sidebar.innerWidth(),
                    overflow: 'hidden'
                });

                obj.sidebarTop = obj.sidebar.offset().top;
                obj.navH    = obj.nav.outerHeight();
                obj.windowH = $(window).height();
                obj.sliderH = obj.slider.innerHeight() + 20 + obj.margin;
                obj.botLine = obj.sidebar.innerHeight() - obj.sliderH;
            }, 1);

            $(window).trigger('scroll');
        };

        this.init = function(opts) {
            this.margin = opts.margin;
            this.nav = opts.nav;
            this.sidebar = opts.sidebar;
            this.container = opts.sidebar.parent();
            this.slider = opts.sidebar.children();

            this.update();

            var top;

            $(window).on('scroll', function() {
                var newScroll = $(this).scrollTop();
                var sizeRevert = (obj.windowH - obj.navH - obj.margin) > obj.sliderH;

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

    var sidebar = new Sidebar();
    sidebar.init(opts);

    return sidebar;
};
