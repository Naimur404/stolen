@extends('layouts.admin.master')
@section('title')Add Product To Warehouse
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
                    <h3>Add Purchase</h3>
                </div>

            </div>

        @endslot

        @slot('button')
            <a href="{{ route('product-purchase.index') }}" class="btn btn-primary btn"
               data-original-title="btn btn-danger btn" title="">Back</a>
        @endslot
    @endcomponent


    <div class="col-md-12 col-lg-12">
        <div class="card ">


            <div class="card-body mt-3">

                {!! Form::open(['route' => 'product-purchase.store', 'class' => 'needs-validation', 'novalidate'=> '', 'autocomplete' => 'off', 'files' => true]) !!}

                <div class="form-group row">
                    {{-- <label for="manufacturer" class="col-md-2 text-right col-form-label">{{ __('purchase.manufacturer') }}:</label>
                    <div class="col-md-4">
                        {{ Form::select('manufacturer_id', [], null, ['class' => 'form-control', 'placeholder' => __('purchase.select_manufacturer'), 'id' => 'manufacturer_id']) }}
                    </div> --}}

                    <label for="supplier" class="col-md-2 text-right col-form-label">Supplier:</label>
                    <div class="col-md-4">
                        {{ Form::select('supplier_id', [], null, ['class' => 'form-control', 'placeholder' => 'Select Supplier', 'id' => 'supplier_id','required']) }}
                        <div class="invalid-feedback">Please Add Supplier</div>
                        @error('supplier_id')
                        <div class="invalid-feedback2"> {{ $message }}</div>

                        @enderror
                    </div>
                    <label for="invoicePhoto" class="col-md-2 text-right">Warehouse Name * :</label>
                    <div class="col-md-4">
                        {{ Form::select( 'warehouse_id', $warehouse, null, ['class' => 'form-control', 'required'] ) }}
                        @error('warehouse_id')
                        <div class="invalid-feedback2"> {{ $message }}</div>

                        @enderror
                    </div>
                </div>


                <div class="form-group row">

                    <label for="invoice_no" class="col-md-2 text-right col-form-label">Invoice No<i class="text-danger">
                            * </i>:</label>
                    <div class="col-md-4">
                        <div class="">
                            <input type="text" class="form-control valid_number" name="invoice_no" id="invoice_no"
                                   placeholder="Invoice No" value="" tabindex="3" required>
                            @error('invoice_no')
                            <div class="invalid-feedback2"> {{ $message }}</div>

                            @enderror
                        </div>
                    </div>

                    <label for="date" class="col-md-2 text-right col-form-label">Purchase Date <i class="text-danger">
                            * </i>:</label>
                    <div class="col-md-4">
                        <input class="datepicker-here form-control digits" type="text" data-language="en" data-bs-original-title="" title="" name="purchase_date" value="{{ Carbon\Carbon::today()->format('d-m-Y') }}" tabindex="2" required>

                        {{-- <input type="text" name="purchase_date" class="form-control datepicker" id="purdate"
                               placeholder="Purchase Date" value="{{ Carbon\Carbon::today()->toDateString() }}"
                               tabindex="2" required> --}}
                    </div>
                </div>


                <div class="form-group row">
                    <label for="payment_type" class="col-md-2 text-right col-form-label">Payment Method<i
                            class="text-danger"> * </i>:</label>
                    <div class="col-md-4">
                        {{ Form::select( 'payment_method_id', $payment_methods, null, ['class' => 'form-control', 'required'], ) }}
                        @error('payment_method_id')
                        <div class="invalid-feedback2"> {{ $message }}</div>

                        @enderror
                    </div>


                    <label for="details" class="col-md-2 text-right col-form-label">Purchase Details :</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="purchase_details" id="details"
                               placeholder="Purchase Details" tabindex="4">
                    </div>

                </div>

                <div class="row form-group">
                    <label for="invoicePhoto" class="col-md-2 text-right">Image of invoice :</label>
                    <div class="col-md-4">
                        {{ Form::file('invoice_image', ['class' => 'btn btn-default btn-file btn-block']) }} @if ($errors->has('invoice_image'))
                            <span class="help-block" style="display:block">
                                    <strong>{{ $errors->first('invoice_image') }}</strong>
                                </span> @endif
                    </div>
                </div>


                <div class="card">

                    <div class="card-header bg-secondary">
                        <i class="fa fa-table"></i> Make Purchase
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-3">
                                <h3>Purchase</h3>
                            </div>

                            <div class="col-md-5">
                                {{ Form::select('', [], null, ['class' => 'form-control', 'placeholder' => 'Select Product', 'id' => 'medicine_id','required']) }}
                                <div class="invalid-feedback">Please Add Product</div>


                            </div>

                            {{--
                            <div class="col-md-4">
                                {{ Form::number('', '', array('class'=> 'form-control', 'placeholder' =>'Quantity', 'id'=>'qty'))}}
                            </div> --}}

                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary addProductRow" id="addProductRow">Add
                                </button>
                            </div>

                        </div>


                        <div class="table-responsive pt-2">
                            <table class="table table-bordered table-hover" id="purchaseTable">
                                <thead>
                                <tr class="item-row">
                                    <th class="text-center">
                                        <nobr>Product Info<i class="text-danger">*</i></nobr>
                                    </th>
                                    <th class="text-center">
                                        <nobr>Rack No</nobr>
                                    </th>
                                    <th class="text-center">
                                        <nobr>Size<i class="text-danger">*</i></nobr>
                                    </th>
                                    {{--
                                    <th class="text-center">
                                        <nobr>Stock Qty</nobr>
                                    </th> --}} {{--
                                    <th class="text-center">
                                        <nobr>Box Pattern<i class="text-danger">*</i></nobr>
                                    </th> --}} {{--
                                    <th class="text-center">
                                        <nobr>Box Qty<i class="text-danger">*</i></nobr>
                                    </th> --}}
                                    <th class="text-center">
                                        <nobr>QTY <i class="text-danger">*</i></nobr>
                                    </th>
                                    <th class="text-center">
                                        <nobr>MSRP<i class="text-danger">*</i></nobr>
                                    </th>
                                    <th class="text-center">
                                        <nobr>MRP    <i class="text-danger">*</i></nobr>
                                    </th>
                                    <th class="text-center">
                                        <nobr>Type <i class="text-danger">*</i></nobr>
                                    </th>
                                    <th class="text-center">
                                        <nobr>Total Price</nobr>
                                    </th>
                                    <th class="text-center">
                                        <nobr>Action</nobr>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-right" colspan="7"><b>Sub total :</b></td>
                                    <td class="text-right">
                                        <input type="number" class="form-control text-right" name="sub_total"
                                               id="subtotal" readonly>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="6"><b>Vat :</b></td>
                                    <td class="text-right">

                                        <input type="number" id="vat_percent" max="100" class="text-right form-control"
                                               placeholder="%"/>

                                    </td>
                                    <td>
                                        <input type="number" id="vat" class="text-right form-control clearVat"
                                               name="vat" value="0" step="any" placeholder="Tk"/>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="6"><b>Discount :</b></td>
                                    <td class="text-right">
                                        <input type="number" id="discount_percent" class="text-right form-control"
                                               max="100" tabindex="16" placeholder="%"/>
                                    </td>
                                    <td>
                                        <input type="number" id="discount" class="text-right form-control"
                                               name="total_discount" value="0" tabindex="17" step="any"
                                               placeholder="Tk"/>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="7"><b>Grand Total :</b></td>
                                    <td class="text-right">
                                        <input type="number" class="form-control text-right" name="grand_total"
                                               id="grandTotal" readonly="readonly"
                                               required> {{-- <span id="grandTotal">0</span> --}}
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="7"><b>Paid Amount :</b></td>
                                    <td class="text-right">
                                        <input type="number" id="pay" class="text-right form-control "
                                               name="paid_amount" value="" onkeyup="prevent_paid_amount()" placeholder="0.00"
                                               onchange="prevent_paid_amount()" tabindex="16" step=".01" required/>
                                               <div class="invalid-feedback">Paid Amount Can Not Be Empty</div>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="7"><b>Due Amount :</b></td>
                                    <td class="text-right">
                                        <input type="number" id="due" class="text-right form-control" name="due_amount"
                                               value="0" readonly="readonly"/>
                                    </td>
                                    <td>
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
                                            Full paid
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
                {{ Form::close(); }}
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- <script src="{{ asset('backend/form-validations/pharmacy/product-purchase.js') }}"></script> --}}
        <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
        <script src="{{ asset('assets/js/product_purchase_invoice.js') }}"></script>
        <script type="text/javascript">
            function clearInput1(target) {
                if (target.value == '0') {
                    target.value = "";
                }
            }

            function clearInput2(target) {
                if (target.value == '0') {
                    target.value = "";
                }
            }

        </script>
        <script type="text/javascript">


            function prevent_paid_amount() {
                var paid_amount = $("#pay").val();
                var grand_total_amount = $("#grandTotal").val();
                if (parseFloat(grand_total_amount) < parseFloat(paid_amount)) {
                    alert("You can not paid more than grand total amount.");
                    $("#pay").val("");
                }
            }

            $(document).ready(function () {

                $(".clearVat,#discount,#pay,#manufacturer_price").on('click', function () {
                    let input = $(this).val();
                    if (input == 0) {
                        $(this).val('');
                    }
                })


                let grandTotal = '';
                $("#full_paid").on('click', function () {
                    grandTotal = $("#grandTotal").val();
                    $("#pay").val(grandTotal);
                });
                // payment validations
                $("#discount").on('click', function () {
                    let subTotal = $('#subtotal').val();
                    $("#discount").attr({
                        max: subTotal,
                    });
                });
                $("#pay").on('click', function () {
                    grandTotal = $("#grandTotal").val();
                    $("#pay").attr({
                        max: grandTotal,
                    });
                });

                //   percentage live calculations
                $("#vat_percent").bind('keypress keyup keydown mouseup', function () {
                    let vat = $(this).val();
                    let subTotal = $("#subtotal").val();
                    let totalVat = calculatePercentage(subTotal, vat);
                    totalVat = totalVat.toFixed(2);
                    $("#vat").val(totalVat);
                    //    console.log(calResult);
                })

                $("#discount_percent").bind('keypress keyup keydown mouseup', function () {
                    let discount = $(this).val();
                    let subTotal = $("#subtotal").val();
                    let totalDiscount = calculatePercentage(subTotal, discount);
                    totalDiscount = totalDiscount.toFixed(2);
                    $("#discount").val(totalDiscount);
                })

                function calculatePercentage(subTotal, vat) {
                    let result = subTotal * (vat / 100);
                    return result;
                }

            })

            // select manufacturer
            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $(document).ready(function () {

                let supplier_id = '';
                $("#supplier_id").select2({
                    ajax: {
                        url: "{!! url('get-supplier') !!}",
                        type: "get",
                        dataType: 'json',
                        //   delay: 250,
                        data: function (params) {
                            return {
                                _token: CSRF_TOKEN,
                                search: params.term,
                            };
                        },
                        processResults: function (response) {
                            return {
                                results: response
                            };
                        },
                        cache: true
                    }

                });

                // get manufacturer id
                $("#supplier_id").on('change', function () {
                    supplier_id = $(this).val();
                })

                // manufacturer wise medicine selection
                $("#medicine_id").select2({
                    ajax: {
                        url: "{!! url('get-manufacture-wise-medicine') !!}",
                        type: "get",
                        dataType: 'json',
                        //   delay: 250,
                        data: function (params) {
                            return {
                                _token: CSRF_TOKEN,
                                search: params.term, // search term
                                // manufacturer: manufacturer_id, // search term
                                supplier: supplier_id,
                            };
                        },
                        processResults: function (response) {
                            return {
                                results: response
                            };
                        },
                        cache: true
                    }

                });


                //  get medicine id
                let medicine_id = '';
                $("#medicine_id").on('change', function () {

                    medicine_id = $(this).val();

                });

                $("#addProductRow").on('click', function () {

                    // let leaf = $('#leaf').find(":selected").val();
                    // let qty = $('#box_qty').val();
                    //   let multiply = leaf*qty;
                    let qty = $('#qty').val();
                    // clear input text after click
                    // $('#qty').val('');


                    if (medicine_id) {
                        $.ajax({
                            url: "{!! url('get-medicine-details-for-purchase') !!}" + "/" + medicine_id,
                            type: "GET",
                            dataType: "json",
                            beforeSend: function () {

                            },

                            success: function (data) {
                                if (data != null) {
                                    $('.pr_id').first().val(data.id);
                                    $('.qty').first().val('1');
                                    $('#product_name').val(data.medicine_name);
                                    $('#box_price').val(data.price);
                                    $('#manufacturer_price').val(data.manufacturer_price);
                                    $('#product_type').val('T-Shirt');
                                } else {
                                    alert('Data not found!');

                                }
                            },

                            complete: function () {

                            }

                        });
                    } else
                        alert("Please Select Medicine Name");


                });


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
                    grandTotal: "#grandTotal",
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
