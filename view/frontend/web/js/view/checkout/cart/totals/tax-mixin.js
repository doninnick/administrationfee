/**
 * Donin
 *
 * @category Donin
 * @package Donin_AdministrationFee
 */ 

define([
    'jquery',
    'Magento_Checkout/js/model/quote'
    ], function ($, quote) {
        'use strict';

        var mixin = {
            /**
             * @override
             */
            ifShowValue: function () {
                var address = quote.isVirtual() ? quote.billingAddress() : quote.shippingAddress();
                if (address.countryId === 'AE') {
                    if (this.getPureValue() === 0 || this.getPureValue() === '0.0000') {
                        return false;
                    }
                } else if (this.getPureValue() === 0) {
                    return false;
                }

                return true;
            }
        };

        return function (originalComponent) {
            return originalComponent.extend(mixin);
        };
    }
);
