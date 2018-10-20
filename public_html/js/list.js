$(function () {
    var box = $('.excursions-block');
    if(!box.length) { return; }

    var list = $('.items-list', box);
    var filters  = $('.filters', box);
    var paginator = $('.paginator', list);
    var wTypes = $('.widget.types', filters);
    var wMuseums  = $('.widget.museums', filters);

    $('.show-all', filters).on('click', function() {
        $(this).css({display: 'none'}).closest('.body').children('.h-box').slideToggle(200);
    });

    function getFiltersUrl() {
        var active = $('.active', wTypes);
        var url = $.aptero.url();

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

        var url = getFiltersUrl();

        $.ajax({
            url: url.getUrl(),
            success: function (resp) {
                var products = $(resp.html.items);
                list.html(products);
                loadMoreProducts();

                //$('html, body').scrollTop(list.offset().top - $('#nav').outerHeight() - 20);

                if(list.is(':visible')) {
                    list.css({display: 'none'})
                }

                list.fadeIn(200);

                /*if(options.filtersUpdate) {
                    var upBox = $('.update-box', filters);
                    upBox.html(resp.html.filters);
                    initElements(filtersBox);
                    initProductsFilters();
                }*/

                History.replaceState({}, resp.meta.title, url.getUrl());
            }
        });
    }

    function loadMoreProducts() {
        paginator = $('.paginator', list);
        if(!paginator.length) {
            return;
        }

        var url = getFiltersUrl();
        url.setParams({page: paginator.data('page')});
        var loadLine = $(window).scrollTop() + ($(window).height()) + 700;
        paginator.remove();
        paginator = $('.paginator', list);

        $.ajax({
            url: url.getUrl(),
            success: function (resp) {
                var products = $(resp.html.items);
                products.appendTo(list);
                paginator = $('.paginator', list);

                if(paginator.length && paginator && loadLine >= paginator.offset().top) {
                    loadMoreProducts();
                }
            }
        });
    }
});