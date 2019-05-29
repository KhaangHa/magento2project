define([
    'jquery',
    'ko',
    'uiComponent',
    'domReady!',
], function ($, ko, component) {
    'use strict';
    return component.extend({
        defaults: {
            template: 'Magenest_Staff/templates'
        },

        initialize: function (config) {
            console.log(config.myVar) //logs My Value
        }
    });
});