require(
    [
        'jquery',
        'Magento_Ui/js/modal/confirm'
    ],
    function(
        $
    ) {
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            title: $.mage.__('Login Form'),
            buttons: [{
                text: $.mage.__('Cancel'),
                class: 'btn-cancel',
                click: function () {
                    this.closeModal();
                }
            },{
                text: $.mage.__('Submit'),
                class: 'btn-submit',
                click: function () {
                    this.closeModal();
                }
            }]
        };
        $(".btn-alert").click(()=>{
            $(".alert-content").alert();
        })
        $(".btn").click(()=>{
            $(".popup-content").modal(options).modal('openModal');

        });
    }
)