@extends('layouts.admin.master')
@section('title')Sales Return
@endsection
@push('css')
<style>
    .delete {
        color: #fff;
    }

    .custom-td {
        padding: 5px !important;
        vertical-align: middle !important;
    }
</style>
@endpush
@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
			<h3>Sales Return</h3>
        </div>

        </div>

		@endslot

        @slot('button')

        @endslot
	@endcomponent
<div class="col-md-12 col-lg-12">
    <div class="card">
        {!! Form::open(['route' => ['sale-return.store'], 'method' => 'post', 'class' => 'needs-validation', 'novalidate'=> '']) !!}
        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">


            </div>
        </div>

        <div class="card-body">


            <div class="service_invoice_header">
                <div class="row">
                    <div class="col-md-8"><b>Invoice Id : #{{ $data->id }}</b></div>
                     <input type="hidden" name="invoice_id" value="{{ $data->id }}">
                    <div class="col-md-4"><b>Sale Date : {{ \Carbon\Carbon::parse($data->sale_date)->format('d-m-Y') }}
                    </b></div>
                </div>

                <div class="row">
                    <input type="hidden" name="outlet_id" value="{{ $data->outlet_id }}">
                    <div class="col-md-8"><b>Outlet Name :  {{ $data->outlet->outlet_name }}</b></div>
                    {{-- <div class="col-md-4"> <b>
                            @if ($productPurchase->supplier_id == null)
                                N/A
                            @elseif ($productPurchase->supplier_id != null)
                                {{ $productPurchase->supplier->supplier_name }}
                            @endif
                        </b></div> --}}

                        <input type="hidden" name="customer_id" value="{{ $data->customer_id }}">
                        <div class="col-md-4"><b>Customer Phone : {{ $data->customer->mobile }}</b>
                        <b></b></div>
                </div>


            </div>

            <div class="card mt-5">

                <div class="card-header bg-secondary">
                    <i class="fa fa-table"></i> Return Purchase Details
                </div>

                <div class="card-body">
                    <div class="table-responsive pt-2">
                        <table class="table table-bordered table-hover" id="purchaseTable">
                            <thead>
                            <tr class="item-row">
                                <th class="text-center">
                                    <nobr>Medicine<i class="text-danger">*</i></nobr>
                                </th>

                                <th class="text-center">
                                    <nobr>Expiry Date<i class="text-danger">*</i></nobr>
                                </th>

                                <th class="text-center">
                                    <nobr>QTY <i class="text-danger">*</i></nobr>
                                </th>
                                <th class="text-center">
                                    <nobr>Return <i class="text-danger">*</i></nobr>
                                </th>

                                <th class="text-center">
                                    <nobr>Price<i class="text-danger">*</i></nobr>
                                </th>

                                <th class="text-center">
                                    <nobr>Total Price</nobr>
                                </th>


                            </tr>
                            @foreach ($medicinedetails as $details)


                            <tr class="item-row">
                                <td>
                                <input class="form-control pr_id" type="hidden" name="product_id[]"  readonly="" value="{{ $details->medicine_id }}">
                                 <input class="form-control product_name" type="text" name="product_name[]" id="product_name" readonly="" required="" value="{{ $details->medicine_name }}"> </td>

                                 <td><input class="form-control invoice_datepicker" type="date" name="expiry_date[]" placeholder="Expiry Date" id="expiry_date" required="" value="{{ $details->expiry_date }}"></td>
                                 <td><input class="form-control qty" type="number" name="quantity[]" placeholder="Quantity" required="" value="{{ $details->quantity }}" readonly></td>

                                 <td><input class="form-control qty2" type="number" name="returnqty[]" placeholder="" required="" value="0" onkeyup="prevent_stock_amount()"
                                    onchange="prevent_stock_amount()" ></td>
                                 <td><input class="form-control price" name="box_mrp[]" type="number" step="any" id="box_price" required="" value="{{ $details->rate }}" readonly></td>
                                 <input class="form-control" type="hidden" name="returnamount[]"  readonly="" id="returnamount" value="">
                                 <td><input class="form-control total" name="total_price[]" type="text" id="product_type" readonly="" required="" value="{{ $details->rate * $details->quantity }}"></td>


                            </tr>
                            @endforeach
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-right" colspan="5"><b>Sub total :</b></td>
                                <td class="text-right">
                                    <input type="number" class="form-control text-right" name="sub_total"
                                           id="subtotal" readonly>
                                </td>

                            </tr>
                            <tr>
                                <td class="text-right" colspan="5"><b>Deduct Amount :</b></td>
                                <td class="text-right">
                                    <input type="number" class="form-control text-right deduct_amount" name="deduct_amount"
                                           id="deduct_amount"  onkeyup="prevent_amount()" value="0"
                                           onchange="prevent_amount()" required>
                                </td>

                            </tr>


                            <tr>
                                <td class="text-right" colspan="5"><b>Grand Total :</b></td>
                                <td class="text-right">
                                    <input type="number" class="form-control text-right" name="grand_total"
                                           id="grandTotal" readonly="readonly"
                                           required> {{-- <span id="grandTotal">0</span> --}}
                                </td>

                            </tr>
                            <tr>
                                <td class="text-right" colspan="5"><b>Retrun Amount :</b></td>
                                <td class="text-right">
                                    <input type="number" id="pay" class="text-right form-control "
                                           name="paid_amount" value="0" onkeyup="prevent_paid_amount()"
                                           onchange="prevent_paid_amount()" tabindex="16" step=".01"/>
                                </td>

                            </tr>
                            <tr>
                                <td class="text-right" colspan="5"><b>Due Amount :</b></td>
                                <td class="text-right">
                                    <input type="number" id="due" class="text-right form-control" name="due_amount"
                                           value="0" readonly="readonly"/>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4 text-right">
                            <label for="payment_type" class="text-right col-form-label mt-3">Payment Method *</label>

                            {{ Form::select( 'payment_method_id', $payment_methods, null, ['class' => 'form-control', 'required' ,'required'], ) }}
                            <div class="invalid-feedback">Please Add Payment Type</div>
                        </div>
                        <div class="col-md-8 text-right">
                            <div class="card-footer text-end">
                                <div class="mt-4">

                                    <button type="button" id="full_paid" class="btn btn-warning" tabindex="19">
                                      Return Back Full
                                    </button>
                                    <button type="submit" class="btn btn-success" tabindex="19" id="save_purchase">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>




        </div>
    </div>
    {!! Form::close() !!}
</div>

@push('scripts')

<script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
<script src="{{ asset('assets/js/sale_return.js') }}"></script>

<script type="text/javascript">


function prevent_amount() {
        var paid_amount = $("#deduct_amount").val();
        var grand_total_amount = $("#subtotal").val();
        if (parseFloat(grand_total_amount) < parseFloat(paid_amount)) {
            alert("Deduct amount not more than sub total.");
            $("#deduct_amount").val("");
        }
    }

    function prevent_paid_amount() {
        var paid_amount = $("#pay").val();
        var grand_total_amount = $("#grandTotal").val();
        if (parseFloat(grand_total_amount) < parseFloat(paid_amount)) {
            alert("Back amount not more than grand total.");
            $("#pay").val("");
        }
    }

    function prevent_stock_amount() {
        var stockqty = $(".qty").val();
        var qty = $(".qty2").val();
        if (parseFloat(qty) > parseFloat(stockqty)) {
            alert("Return quantity not more than  purchase quantity.");
            $(".qty2").val("");
        }
    }


    $(document).ready(function () {

        $(".clearVat,.qty2,.deduct_amount,#discount,#pay,#manufacturer_price").on('click', function () {
            let input = $(this).val();
            if (input == 0) {
                $(this).val('');
            }
        })


        let backamount = '';
        $("#full_paid").on('click', function () {
            backamount  = $("#grandTotal").val();
            $("#pay").val(backamount);
        });
        // payment validations
        $("#discount").on('click', function () {
            let subTotal = $('#subtotal').val();
            $("#discount").attr({
                max: subTotal,
            });
        });
        let grandTotal = '';
        $("#deduct_amount").on('mouseover', function () {

            grandTotal = $("#grandTotal").val();

            $("#pay").val(grandTotal);
            // $("#pay").attr({
            //     max: grandTotal,
            // });
        });

        //   percentage live calculations







    // select manufacturer


        // manufacturer wise medicine selection



        //  get medicine id





        // purchase invoice generator
        jQuery().invoice({
            addRow: "#addRow",
            addProductRow: "#addProductRow",
            delete: ".delete",
            parentClass: ".item-row",

            price: ".price",
            qty: ".qty",
            qty2: ".qty2",
            Quantity: "#Quantity",
            total: ".total",
            // totalQty: "#totalQty",

            subtotal: "#subtotal",
            discount: "#discount",
            // shipping : "#shipping",
            vat: "#vat",
            returnqty: "#returnqty",
            deduct: "#deduct_amount",
            grandTotal: "#grandTotal",
            return: ".returnqty",
            returnamount: "#returnamount",
            deductamount: "#deduct_amount",
            pay: "#pay",
            due: "#due"
        });

    });
</script>
@if (Session()->get('success'))

<script>
$.notify('<i class="fa fa-bell-o"></i><strong>{{ Session()->get('success') }}</strong>', {
type: 'theme',
allow_dismiss: true,
delay: 2000,
showProgressbar: true,
timer: 300
});
</script>

@endif
@if (Session()->get('error'))

<script>
$.notify('<i class="fa fa-bell-o"></i><strong>{{ Session()->get('error') }}</strong>', {
type: 'theme',
allow_dismiss: true,
delay: 2000,
showProgressbar: true,
timer: 300
});
</script>
@endif
@endpush
@endsection
