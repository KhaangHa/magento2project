define(['jquery', 'uiComponent', 'domReady'], function ($, component) {

    return function (widget) {
        var globalOptions = {
            productId: null,
            priceConfig: null,
            prices: {},
            priceTemplate: '<span class="price"><%- data.formatted %> (lv 1)</span>'
        };

        $.widget('mage.priceBox', widget, {
            options: globalOptions
        });
        return $.mage.priceBox;
    }
    // return component.extend({
    //     defaults: {
    //         template: 'Vendor_Module/template'
    //     },
    //     productInfo: window.customInfo.parameter,
    //
    //     initialize: function () {
    //         //init function code here
    //     }
    // });

});
