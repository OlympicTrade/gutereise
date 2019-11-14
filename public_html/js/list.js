$(function () {
    let box = $('.excursions-block');
    if(!box.length) { return; }

    let list = $('.items-list', box);
    let filters  = $('.filters', box);
    let paginator = $('.paginator', list);
    let wTypes = $('.widget.types', filters);
    let wMuseums  = $('.widget.museums', filters);

    $('.show-all', filters).on('click', function() {
        $(this).css({display: 'none'}).closest('.body').children('.h-box').slideToggle(200);
    });

    function getFiltersUrl() {
        let active = $('.active', wTypes);
        let url = $.aptero.url();

        url.setPath(active.length ? active.attr('href') : $('input.update-url', filters).val());
        url.setParams($.aptero.serializeArray(filters));

        return url;
    }

    $('a', wTypes).on('click', function () {
        $(this).toggleClass('active')/*.parent()*/.siblings().removeClass('active');
        updateProducts({filtersUpdate: true});
        return false;
    });

    $('input', wMuseums).on('change', function () {
        updateProducts({filtersUpdate: true});
    });

    function updateProducts(options) {
        list.fadeOut(200, function () {
            setTimeout(function () {
                if(!list.is(':visible')) {
                    $.aptero.loadingHtml(list);
                    list.css({display: 'block'});
                }
            });
        });

        let url = getFiltersUrl();

        $.ajax({
            url: url.getUrl(),
            success: function (resp) {
                let products = $(resp.html.items);
                list.html(products);
                loadMoreProducts();

                if(list.is(':visible')) {
                    list.css({display: 'none'})
                }

                list.fadeIn(200, function () {
                    $('.sidebar').sidebar().update();
                });

                History.replaceState({}, resp.meta.title, url.getUrl());
            }
        });
    }

    function loadMoreProducts() {
        paginator = $('.paginator', list);
        if(!paginator.length) {
            return;
        }

        let url = getFiltersUrl();
        url.setParams({page: paginator.data('page')});
        let loadLine = $(window).scrollTop() + ($(window).height()) + 700;
        paginator.remove();
        paginator = $('.paginator', list);

        $.ajax({
            url: url.getUrl(),
            success: function (resp) {
                let products = $(resp.html.items);
                products.appendTo(list);
                paginator = $('.paginator', list);

                if(paginator.length && paginator && loadLine >= paginator.offset().top) {
                    loadMoreProducts();
                } else {
                    $('.sidebar').sidebar().update();
                }
            }
        });
    }
});