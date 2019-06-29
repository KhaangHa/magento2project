require(
    [
    'jquery',
    'Magento_Ui/js/modal/confirm',
    'Magento_Ui/js/modal/alert'

    ], function ($, confirmation, alert) {
        'use strict';
        $('button#btn-approve-affiliate').click(
            function () {
                console.log(window.magenest.affiliate.joinUrl);
                confirmation(
                    {
                        title: 'Join Affiliate Program',
                        content: '<h2>Are you sure ?</h2>',
                        actions: {
                            confirm: function () {
                                $.ajax(
                                    {
                                        showLoader: true,
                                        type: 'POST',
                                        url: window.magenest.affiliate.joinUrl,
                                        dataType: "json",
                                        success: function (response) {
                                            if (response.success) {
                                                var code = response.code;
                                                var mess;
                                                if (code === 1) {
                                                    mess = "Your request is pending. Please wait for admin's approvement or Contact us for more details.";
                                                }
                                                if (code === 2) {
                                                    mess = "Your request has been approved!";
                                                }
                                                alert(
                                                    {
                                                        title: '',
                                                        content: mess,
                                                        autoOpen: true,
                                                        actions: {
                                                            always: function () {
                                                                location.reload();
                                                            }
                                                        }
                                                    }
                                                );
                                            }
                                        },
                                        error: function (response) {
                                            alert({content: "Has something wrong"});
                                        }
                                    }
                                )
                            },
                            cancel: function () {
                            },
                            always: function () {
                            }
                        }
                    }
                )
            }
        );
        $('button#btn-generate-url').click(
            function () {
                var url;

                url = $('input#url-here').val();
                var base_url = window.magenest.affiliate.baseUrl;
                if (validateUrl(url)) {
                    url = encodeURIComponent(url);
                    var custom_param = window.magenest.affiliate.customParam;
                    if (custom_param.length === 0) {
                        custom_param = 'checkout/cart/CouponPost/coupon_code/';
                    }
                    $('p#url-result').html(base_url + custom_param + '/'+ window.magenest.affiliate.uniqueCode + '/?return_url='+url);
                }
                else {
                    alert(
                        {
                            content: 'Please enter a valid URL'
                        }
                    );
                }


            }
        );

        function validateUrl(str) 
        {
            return true;
        }
    }
);