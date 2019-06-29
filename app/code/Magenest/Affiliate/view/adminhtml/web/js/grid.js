// global js file
require(
    [
    'jquery',
    'Magento_Ui/js/modal/confirm',
    'Magento_Ui/js/modal/alert',
    'Magento_Ui/js/modal/modal'
    ], function ($, confirmation, alert, modal) {
        'use strict';
        var interval = setInterval(
            function () {
                var $row = $('td.affiliate-status>div.data-grid-cell-content');
                $.each(
                    $row, function (key, val) {
                        if ($(val).html() === 'DECLINED' || $(val).html() === 'DISABLED' || $(val).html() === 'CANCELED') {
                            $(val).addClass('affiliate-status-decorated declined');
                            $(val).removeClass('approved');
                            $(val).removeClass('pending');
                        }
                        if ($(val).html() === 'APPROVED') {
                            $(val).addClass('affiliate-status-decorated approved');
                            $(val).removeClass('declined');
                            $(val).removeClass('pending');
                        }
                        if ($(val).html() === 'PENDING' || $(val).html() === 'ERROR') {
                            $(val).addClass('affiliate-status-decorated pending');
                            $(val).removeClass('approved');
                            $(val).removeClass('declined');
                        }
                    }
                );
            }, 200
        );
    }
);