define(['jquery', 'uiComponent', 'domReady'], function ($, component) {

    return function (widget) {
        var globalOptions = {
            productId: null,
            priceConfig: null,
            prices: {},
            priceTemplate: '<span class="price"><%- data.formatted %></span>'
        };

        $.widget('mage.priceBox', widget, {
            options: globalOptions
        });
        return $.mage.priceBox;
    }
});
