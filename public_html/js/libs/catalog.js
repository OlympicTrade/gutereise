$(function () {
    let box = $('.module-items-block.sidebar-aware');
    let itemsBox = $('.items-list', box);
    let filtersBox  = $('.filters', box);
    let paginator = $('.paginator', itemsBox);
    let wCtegory = $('.widget.catalog', filtersBox);
    let search = $('.search-input', filtersBox);

    $('.row', wCtegory).on('click', function () {
        var row = $(this);

        row.addClass('active')
            .find('.sub')
            .slideDown(200);

        row.siblings()
            .removeClass('active')
            .find('.sub')
            .slideUp(200);

        search.val('');
        updateProducts();
        return false;
    });

    let searchTimer;
    search.on('keyup', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () {
            updateProducts();
        }, 250);
    });

    let loadingTimer = null;
    $(window).on('scroll', function () {
        clearTimeout(loadingTimer);
        loadingTimer = setTimeout(function() {
            loadMoreProducts();
        }, 250);
    });
    loadMoreProducts();

    function getFiltersUrl() {
        let url = $.aptero.url();
        let active = $('.active a', wCtegory);

        url.setPath(active.length ? active.attr('href') : $('.base-url', filtersBox).val());
        url.setParams($.aptero.serializeArray(filtersBox));

        return url;
    }

    var paginatorLine = 0;
    if(paginator.length) {
        paginatorLine = paginator.offset().top;
    }

    function updateProducts() {
        $.aptero.scrollTo(itemsBox);
        itemsBox.empty();

        let url = getFiltersUrl();

        $.ajax({
            url: url.getUrl(),
            success: function (resp) {
                let products = $(resp.html.items);
                itemsBox.html(products);
                loadMoreProducts();

                $('html, body').scrollTop(itemsBox.offset().top - $('#nav').outerHeight() - 20);

                if(itemsBox.is(':visible')) {
                    itemsBox.css({display: 'none'})
                }

                itemsBox.fadeIn(200);

                History.replaceState({}, resp.meta.title, url.getUrl());

                setTimeout(function () {
                    $('.sidebar', box).sidebar().update();
                }, 500);
            }
        });
    }

    function loadMoreProducts() {
        let loadLine = $(window).scrollTop() + $(window).height() + 300;

        if(!paginatorLine || loadLine <= paginatorLine) {
            return;
        }

        let url = getFiltersUrl();
        url.setParams({page: paginator.data('page')});
        paginator.remove();
        paginator = $('.paginator', itemsBox);

        $.ajax({
            url: url.getUrl(),
            success: function (resp) {
                let products = $(resp.html.items);
                products.appendTo(itemsBox);
                paginator = $('.paginator', itemsBox);
                if(!paginator.length) {
                    paginatorLine = false;
                    $('.sidebar', box).sidebar().update();
                    return;
                }

                paginatorLine = paginator.offset().top;

                if(loadLine >= paginatorLine) {
                    loadMoreProducts();
                } else {
                    $('.sidebar', box).sidebar().update();
                }
                $('.sidebar', box).sidebar().update();
            }
        });
    }
});