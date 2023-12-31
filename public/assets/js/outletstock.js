(function (jQuery) {
    $.opt = {}; // jQuery Object

    jQuery.fn.invoice = function (options) {
        var ops = jQuery.extend({}, jQuery.fn.invoice.defaults, options);
        $.opt = ops;

        var inv = new Invoice();
        inv.init();

        jQuery('body').on('click', function (e) {
            var cur = e.target.id || e.target.className;

            if (cur == $.opt.addRow.substring(1))
                inv.newRow();

            if (cur == $.opt.addProductRow.substring(1))
                inv.newRow();

            if (cur == $.opt.delete.substring(1))
                inv.deleteRow(e.target);

            inv.init();
        });

        jQuery("body").on('keyup', function (e) {
            inv.init();
        });

        return this;
    };
}(jQuery));


function Invoice() {
    self = this;
}

Invoice.prototype = {
    constructor: Invoice,

    init: function () {
        this.calcTotal();
        // this.calcTotalQty();
        this.calcSubtotal();
        this.calcGrandTotal();
        this.calcPayment();
        this.datePicker();
        this.check();

    },

    /**
     * Calculate total price of an item.
     *
     * @returns {number}
     */
    calcTotal: function () {
        jQuery($.opt.parentClass).each(function (i) {
            var row = jQuery(this);
            var total = row.find($.opt.manu_price).val() * row.find($.opt.qty).val();

            total = self.roundNumber(total, 2);

            // row.find($.opt.total).html(total);

            row.find($.opt.total).val(total);
        });

        return 1;
    },

    check: function () {
        var paid_amount = jQuery($.opt.qty).val();
        var grand_total_amount = jQuery($.opt.stock).val();
        if (parseInt(paid_amount) > parseInt(grand_total_amount)) {
            alert("Quantity not more than Stock.");
            jQuery($.opt.qty).val('');
        }

        return 1;
    },

    /***
     * Calculate total quantity of an order.
     *
     * @returns {number}
     */


    /***
     * Calculate subtotal of an order.
     *
     * @returns {number}
     */
    calcSubtotal: function () {
        var subtotal = 0;
        jQuery($.opt.total).each(function (i) {
            var total = jQuery(this).val();
            // var total = jQuery(this).html();
            if (!isNaN(total)) subtotal += Number(total);
        });

        subtotal = self.roundNumber(subtotal, 2);

        //  console.log('sub total '+subtotal);

        jQuery($.opt.subtotal).val(subtotal);
        // jQuery($.opt.subtotal).html(subtotal);

        // $('#subtotal').val(subtotal);

        return 1;
    },

    /**
     * Calculate grand total of an order.
     *
     * @returns {number}
     */

    calcGrandTotal: function () {
        var grandTotal = Number(jQuery($.opt.subtotal).val()) +
            Number(jQuery($.opt.vat).val()) -
            Number(jQuery($.opt.discount).val());
        grandTotal = self.roundNumber(grandTotal, 2);
        jQuery($.opt.grandTotal).val(grandTotal);
        return 1;
    },


    calcPayment: function () {
        var due = Number(jQuery($.opt.grandTotal).val()) -
            Number(jQuery($.opt.pay).val());
        due = self.roundNumber(due, 2);
        jQuery($.opt.due).val(due);
        return 1;
    },

    datePicker: function () {
        // $(".invoice_datepicker").datepicker({ minDate: 0 });

        // return 0;
    },

    /**
     * Add a row.
     *
     * @returns {number}
     */
    newRow: function () {
        jQuery(".item-row:first").after('<tr class="item-row"><td><input class="form-control pr_id" type="hidden" name="product_id[]"  readonly><input class="form-control create_date" type="hidden" name="create_date[]"  readonly><input class="form-control stock_id" type="hidden" name="stock_id[]"  readonly> <input class="form-control product_name" type="text" name="product_name[]" id="product_name" readonly required> </td><td><input class="form-control barcode"  type="text" name="barcode[]" placeholder="Barcode" readonly></td><td><input class="form-control size" type="text" name="size[]" placeholder="Size" id="expiry_date" required></td><td><input class="form-control qty"  type="number" name="quantity[]" placeholder="Quantity"  id="qty" required></td><td><input class="form-control stock" type="number" step="any" name="stock[]" id="stock" onfocus= "clearInput1(this)" required readonly></td><td><input class="form-control manu_price" name="manu_price[]" type="number" step="any" id="manu_price" readonly><td><input class="form-control price" name="box_mrp[]" type="number" step="any" id="box_price" onfocus= "clearInput2(this)" required></td></td><td><input class="form-control total" name="total_price[]" placeholder="0.00 " readonly></td><td><span class="btn btn-sm btn-danger"><a class=' + $.opt.delete.substring(1) + ' href="javascript:;" title="Remove row">Delete</a></span></td></tr>');


        if (jQuery($.opt.delete).length > 0) {
            jQuery($.opt.delete).show();
        }

        return 1;
    },


    /**
     * Delete a row.
     *
     * @param elem   current element
     * @returns {number}
     */
    deleteRow: function (elem) {
        jQuery(elem).parents($.opt.parentClass).remove();

        if (jQuery($.opt.delete).length < 1) {
            jQuery($.opt.delete).hide();
        }

        return 1;
    },

    /**
     * Round a number.
     * Using: http://www.mediacollege.com/internet/javascript/number/round.html
     *
     * @param number
     * @param decimals
     * @returns {*}
     */
    roundNumber: function (number, decimals) {
        var newString; // The new rounded number
        decimals = Number(decimals);

        if (decimals < 1) {
            newString = (Math.round(number)).toString();
        } else {
            var numString = number.toString();

            if (numString.lastIndexOf(".") == -1) { // If there is no decimal point
                numString += "."; // give it one at the end
            }

            var cutoff = numString.lastIndexOf(".") + decimals; // The point at which to truncate the number
            var d1 = Number(numString.substring(cutoff, cutoff + 1)); // The value of the last decimal place that we'll end up with
            var d2 = Number(numString.substring(cutoff + 1, cutoff + 2)); // The next decimal, after the last one we want

            if (d2 >= 5) { // Do we need to round up at all? If not, the string will just be truncated
                if (d1 == 9 && cutoff > 0) { // If the last digit is 9, find a new cutoff point
                    while (cutoff > 0 && (d1 == 9 || isNaN(d1))) {
                        if (d1 != ".") {
                            cutoff -= 1;
                            d1 = Number(numString.substring(cutoff, cutoff + 1));
                        } else {
                            cutoff -= 1;
                        }
                    }
                }

                d1 += 1;
            }

            if (d1 == 10) {
                numString = numString.substring(0, numString.lastIndexOf("."));
                var roundedNum = Number(numString) + 1;
                newString = roundedNum.toString() + '.';
            } else {
                newString = numString.substring(0, cutoff) + d1.toString();
            }
        }

        if (newString.lastIndexOf(".") == -1) { // Do this again, to the new string
            newString += ".";
        }

        var decs = (newString.substring(newString.lastIndexOf(".") + 1)).length;

        for (var i = 0; i < decimals - decs; i++)
            newString += "0";
        //var newNumber = Number(newString);// make it a number if you like

        return newString; // Output the result to the form field (change for your purposes)
    }
};

/**
 *  Publicly accessible defaults.
 */
jQuery.fn.invoice.defaults = {
    addRow: "#addRow",
    addProductRow: "#addProductRow",
    delete: ".delete",
    parentClass: ".item-row",
    price: ".price",
    manu_price: ".manu_price",
    qty: ".qty",
    stock: ".stock",
    Quantity: "#Quantity",
    total: ".total",
    // totalQty: "#totalQty",

    subtotal: "#subtotal",
    discount: "#discount",
    // shipping: "#shipping",
    vat: "#vat",
    grandTotal: "#grandTotal",
    pay: "#pay",
    due: "#due"
};
