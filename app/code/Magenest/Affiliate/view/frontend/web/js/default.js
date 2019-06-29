// global js file
require(
    [
    'jquery',
    'Magento_Ui/js/modal/confirm',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/modal/modal'
    ], function ($, confirmation, alert, modal) {
        'use strict';
        $('button.affiliate-withdaw').click(
            function () {
                var options = {
                    autoOpen: true,
                    type: 'popup',
                    responsive: true,
                    modalClass: 'affiliate-modal',
                    title: 'Affiliate Withdrawal',
                    buttons: false
                };
                modal(options, $('div.request-affiliate-form'));
            }
        );
    }
);