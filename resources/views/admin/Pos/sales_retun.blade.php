



@extends('layouts.admin.master')
@section('title')Due Payment
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
			<h3>Due Payment</h3>
        </div>

        </div>

		@endslot

        @slot('button')

        @endslot
	@endcomponent
<div class="col-md-12 col-lg-12">
    <div class="card">
        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">

              
            </div>
        </div>

        <div class="card-body">
            {!! Form::open(['route' => ['sale-return.index'], 'method' => 'PUT', 'class' => 'needs-validation', 'novalidate'=> '']) !!}

            <div class="service_invoice_header">
                <div class="row">
                    <div class="col-md-4">Invoice Id : <b></b></div>
                    <div class="col-md-4">
                        <p class="text-center"><b>Invoice No : </b></p>
                    </div>
                    <div class="col-md-4">Purchase Date :
                        <b></b></div>
                </div>

                <div class="row">
                    <div class="col-md-3">Supplier Name : </div>
                    {{-- <div class="col-md-4"> <b>
                            @if ($productPurchase->supplier_id == null)
                                N/A
                            @elseif ($productPurchase->supplier_id != null)
                                {{ $productPurchase->supplier->supplier_name }}
                            @endif
                        </b></div> --}}


                </div>
                <div class="row">
                    <div class="col-md-3">Manufacturer Name : </div>
                    {{-- <div class="col-md-4"><b>
                            @if ($productPurchase->manufacturer_id == null)
                                N/A
                            @elseif ($productPurchase->manufacturer_id != null)
                                {{ $productPurchase->manufacturer->manufacturer_name }}
                            @endif
                        </b></div> --}}

                </div>
             
            </div>

            <div class="card mt-5">

                <div class="card-header bg-secondary">
                    <i class="fa fa-table"></i> Make Purchase
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

                                 <td><input class="form-control returnqty" type="number" name="returnqty[]" placeholder="" required="" value="" onkeyup="prevent_stock_amount()"
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
                                    <input type="number" class="form-control text-right" name="deduct_amount"
                                           id="deduct_amount" readonly>
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
                                <td class="text-right" colspan="5"><b>Back Amount :</b></td>
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
                        <div class="col-md-6 text-right">
                        </div>
                        <div class="col-md-6 text-right">
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
           

            {!! Form::close() !!}

        </div>
    </div>
</div>

@push('scripts')

<script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
<script src="{{ asset('assets/js/sale_return.js') }}"></script>

<script type="text/javascript">


    function prevent_paid_amount() {
        var paid_amount = $("#pay").val();
        var grand_total_amount = $("#deduct_amount").val();
        if (parseFloat(grand_total_amount) < parseFloat(paid_amount)) {
            alert("You can not back amount not more than reduct amount.");
            $("#pay").val("");
        }
    }

    function prevent_stock_amount() {
        var stockqty = $(".qty").val();
        var qty = $(".returnqty").val();
        if (parseFloat(qty) > parseFloat(stockqty)) {
            alert("Return quantity not more than  purchase quantity.");
            $(".returnqty").val("");
        }
    }


    $(document).ready(function () {

        $(".clearVat,#discount,#pay,#manufacturer_price").on('click', function () {
            let input = $(this).val();
            if (input == 0) {
                $(this).val('');
            }
        })


        let backamount = '';
        $("#full_paid").on('click', function () {
            backamount  = $("#deduct_amount").val();
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
        $("#pay").on('click', function () {
            grandTotal = $("#deduct_amount").val();
            $("#pay").attr({
                max: deduct_amount,
            });
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
            deduct_amount: "#deduct_amount",
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
