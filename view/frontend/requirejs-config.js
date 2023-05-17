/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 
var config = {
    config: {
        mixins: {
            'Magento_Tax/js/view/checkout/cart/totals/tax': {
                'Donin_AdministrationFee/js/view/checkout/cart/totals/tax-mixin': true
            },
            'Amasty_Checkout/js/model/shipping-registry': {
                'Donin_AdministrationFee/js/model/checkout/shipping-registry-mixin': true
            }
        }
    }
};
