define([
    'jquery',
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
    'Magento_Ui/js/modal/modal'
], function ($,_, uiRegistry, select, modal) {
    'use strict';
    return select.extend({

        initialize: function (){
            var field1 = uiRegistry.get('index = config_sale');
            var field2 = uiRegistry.get('index = program_config');
            // var field1 = $('.fieldset-wrapper');
            var status = this._super().initialValue;
            console.log(field1);

            if (status == 2) {
                field1.visible(true);
                field2.visible(false);
            } else{
                field1.visible(false);
                field2.visible(true);

            }
            return this;

        },

        /**
         * On value change handler.
         *
         * @param {String} value
         */
        onUpdate: function (value) {
            var field1 = uiRegistry.get('index = config_sale');
            var field2 = uiRegistry.get('index = program_config');

            if (value == 2) {
                field1.visible(true);
                field2.visible(false);


            } else {
                field1.visible(false);
                field2.visible(true);

            }
            return this._super();
        },
    });
});