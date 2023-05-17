/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 
define([
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/shipping-address/form-popup-state',
    'Amasty_Checkout/js/model/address-form-state',
    'Amasty_Checkout/js/model/payment/payment-loading'
], function (wrapper, formPopUpState, addressFormState, paymentLoader) {
    'use strict';

    return function (target) {
        return wrapper.extend(target, {

            /**
             * Removed async validation based on the validationTimeout and setTimeout method to prevent placing order
             *  between operation of saving totals and data validation
             */
            triggerValidation: function () {
                clearTimeout(this.validationTimeout);

                if (!formPopUpState.isVisible() && !addressFormState.isShippingFormVisible()) {
                    paymentLoader(true);

                    target.validation();
                }
            }

        });
    };
});


