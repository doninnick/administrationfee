/**
 * Donin
 *
 * @category Donin
 * @package Donin_Invoice
 */

define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/totals',
], function (Component, totals) {
    'use strict';

    return Component.extend({

        /**
         * @return {boolean}
         */
        displayFee: function () {
            if (totals.totals()) {
                return !!totals.getSegment('pc_administration_fee');
            }

            return false;
        },

        /**
         * @return {*}
         */
        getPureValue: function () {
            if (totals.totals()) {
                if (totals.getSegment('pc_administration_fee')) {
                    return parseFloat(totals.getSegment('pc_administration_fee').value);
                }
            }

            return 0;
        },

        /**
         * @return {*|String}
         */
        getValue: function () {
            return this.getFormattedPrice(this.getPureValue());
        }

    });
});
