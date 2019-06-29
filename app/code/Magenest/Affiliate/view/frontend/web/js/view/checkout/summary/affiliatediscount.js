define(
    [
        'jquery',
        'Magento_Checkout/js/view/summary/abstract-total',
        'jquery/jquery.cookie',
        'mage/url',
        'ko',
        "mage/mage"

    ],
    function ($,Component,cookie,urlBuilder,ko) {
        "use strict";
        //var discount;
        var urlCheck = urlBuilder.build('affiliate/coupon/check');
        console.log(urlCheck);

        return Component.extend({
            defaults: {
                template: 'Magenest_Affiliate/checkout/summary/affiliatediscount'
            },
            discount: ko.observable(null),
            initObservable: function () {
                var self = this;
                this._super();
                $.ajax({
                    url: urlCheck,
                    type: 'POST',
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);
                        self.discount(response.discount);
                    }
                });
                return this;
            },

            isDisplayedCustomdiscount : function(){
                if(this.discount()!=null)
                {
                    return true;
                }
               return false;
            },
            getAffiliateDiscount : function(){
                return "-$"+this.discount();
            }
        });
    }
);