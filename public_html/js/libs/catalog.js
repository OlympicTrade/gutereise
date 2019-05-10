$(function () {
    var box = $('.module-items-block.sidebar-aware');
    var itemsBox = $('.items-list', box);
    var filtersBox  = $('.filters', box);
    var paginator = $('.paginator', itemsBox);
    var wCtegory = $('.widget.catalog', filtersBox);

    $('a', wCtegory).on('click', function () {
        $(this).toggleClass('active').closest('.row').siblings().find('a').removeClass('active');
        updateProducts();
        return false;
    });

    var loadingTimer = null;
    $(window).on('scroll', function () {
        clearTimeout(loadingTimer);
        loadingTimer = setTimeout(function() {
            loadMoreProducts();
        }, 250);
    });
    loadMoreProducts();

    function getFiltersUrl() {
        var url = $.aptero.url();
        url.setPath('/excursions/');

        var search = $('.search-input');

        if(search.val()) {
            url.setParams({search: search.val()});
        } else {
            var active = $('.active', wCtegory);
            url.setPath(active.length ? active.attr('href') : $('input.update-url', filtersBox).val());
        }

        return url;
    }

    var paginatorLine = 0;
    if(paginator.length) {
        paginatorLine = paginator.offset().top;
    }

    function updateProducts() {
        $.aptero.scrollTo(itemsBox);
        itemsBox.empty();

        var url = getFiltersUrl();

        $.ajax({
            url: url.getUrl(),
            success: function (resp) {
                var products = $(resp.html.items);
                itemsBox.html(products);
                loadMoreProducts();

                $('html, body').scrollTop(itemsBox.offset().top - $('#nav').outerHeight() - 20);

                if(itemsBox.is(':visible')) {
                    itemsBox.css({display: 'none'})
                }

                itemsBox.fadeIn(200);

                /*if(options.filtersUpdate) {
                    var upBox = $('.update-box', filtersBox);
                    upBox.html(resp.html.filters);
                    initElements(filtersBox);
                    initProductsFilters();
                }*/

                //History.replaceState({}, resp.meta.title, url.getUrl());
            }
        });
    }

    function loadMoreProducts() {
        var loadLine = $(window).scrollTop() + $(window).height() + 300;

        if(!paginatorLine || loadLine <= paginatorLine) {
            return;
        }

        var url = getFiltersUrl();
        url.setParams({page: paginator.data('page')});
        paginator.remove();
        paginator = $('.paginator', itemsBox);

        $.ajax({
            url: url.getUrl(),
            success: function (resp) {
                var products = $(resp.html.items);
                products.appendTo(itemsBox);
                paginator = $('.paginator', itemsBox);
                if(!paginator.length) {
                    paginatorLine = false;
                    return;
                }

                paginatorLine = paginator.offset().top;

                if(loadLine >= paginatorLine) {
                    loadMoreProducts();
                }
            }
        });
    }
});