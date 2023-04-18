@extends('admin.Pos.pos_master')
@section('title')
    POS
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

        input:focus, textarea:focus, select:focus {
            outline: none;
        }
        .card .card-header{
            padding: 6px !important;
        }
        .card .card-body{
            padding: 10px !important;
        }
        .form-control, .form-select{
            border-color: #172d7b !important;
        }
        .form-control:disabled, .form-control[readonly]{
            border-color: #efeff1 !important;
        }

        .page-main-header{
            max-height: 80px !important;
        }


        main-header-left
        {
            padding: 15px 40px !important;
        }

    </style>
@endpush
@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <div class="row">
                <div class="col-sm-6">
                    <h3>POS</h3>
                </div>

            </div>

        @endslot

    @endcomponent
    {!! Form::open(['id' => 'my-form', 'class' => 'needs-validation', 'novalidate'=> '', 'autocomplete' => 'off', 'autocomplete' => 'off', 'files' => true]) !!}

    <input type="hidden" name="outlet_id" value="{{ $outlet_id }}">
    <div class="row item-row-add">

        <div class="col-md-9 col-lg-9">
            <div class="card ">


                <div class="card-body mt-3">


                    <div class="form-group row">


                        <div class="col-md-3">
                            {{ Form::select('medicine_id', [], null, ['class' => 'form-control', 'placeholder' => 'Select medicine', 'id' => 'medicine_id']) }}


                        </div>

                        {{--
                        <div class="col-md-4">
                            {{ Form::number('', '', array('class'=> 'form-control', 'placeholder' =>'Quantity', 'id'=>'qty'))}}
                        </div> --}}


                        <div class="col-md-1">

                            <button type="button" class="btn btn-primary btn-xs  addProductRow mt-2" id="addProductRow">
                                Add
                            </button>
                        </div>
                        <div class="col-md-3">

                            {!! Form::text('medicine',null,['class'=>'form-control' ,'placeholder'=>'Barcode','id' => 'barcode']) !!}
                        </div>
                        <div class="col-md-5">
                            <p class="btn btn-air-info">{{ Auth::user()->name }}</p>

                            <p class="btn btn-air-info mb-3"
                               style="margin-left:5PX">{{ Carbon\Carbon::today()->format('d M Y') }}</p>
                            <p class="btn btn-air-info mb-3" style="margin-left:5PX" id="time"></p>
                        </div>


                    </div>


                    <div class="card">

                        <div class="card-header bg-primary">
                            <i class="fa fa-table"></i> Item Details
                        </div>

                        <div class="card-body">


                            <div class="table-responsive pt-2">
                                <table class="table table-striped " id="purchaseTable">
                                    <thead>
                                    <tr class="item-row">

                                        <th class="text-center">
                                            <nobr>Product<i class="text-danger">*</i></nobr>
                                        </th>

                                        <th class="text-center">
                                            <nobr>Expiry<i class="text-danger">*</i></nobr>
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
                                            <nobr>Stock <i class="text-danger">*</i></nobr>
                                        </th>
                                        <th class="text-center">
                                            <nobr>Qty<i class="text-danger">*</i></nobr>
                                        </th>

                                        <th class="text-center">
                                            <nobr>Price<i class="text-danger">*</i></nobr>
                                        </th>


                                        <th class="text-center">
                                            <nobr>Discount<i class="text-danger">*</i></nobr>
                                        </th>
                                        <th class="text-center">
                                            <nobr>Total Price</nobr>
                                        </th>
                                        {{-- <th class="text-center">
                                            <nobr>Total_Price</nobr>
                                        </th> --}}
                                        {{-- <th class="text-center">
                                            <nobr>Action</nobr>
                                        </th> --}}
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            {{-- <div class="form-group row">
                                <div class="col-md-6 text-right">
                                </div>
                                <div class="col-md-6 text-right">
                                    <div class="card-footer text-end">
                                    <div class="mt-4">

                                        <button type="button" id="full_paid" class="btn btn-warning" tabindex="19">
                                               Full paid </button>
                                        <button type="submit" class="btn btn-success" tabindex="19" id="save_purchase">
                                                Save
                                            </button>
                                    </div>
                                    </div>
                                </div>
                            </div> --}}

                        </div>
                    </div>
                    <div class="card-header bg-primary">
                        <i class="fa fa-table"></i> Customer Details
                    </div>
                    <div class="form-group row mt-5">


                        <div class="col-md-3">
                            {!! Form::label('mobile', 'Select Customer', array('class' => 'form-label')) !!}
                            {{ Form::select('mobile', [], null, ['class' => 'form-control add', 'placeholder' => 'Walking Customer', 'id' => 'user_id']) }}


                        </div>
                        <div class="col-md-1">
                            <!-- Button trigger modal -->
                            <button class="btn btn-primary btn-xs adduser" style="margin-top: 32px" id="adduser"
                                    type="button"><i data-feather="arrow-right-circle" class="mt-1"></i></button>
                        </div>


                        <div class="col-md-3">
                            {!! Form::label('name', 'Customer Name', array('class' => 'form-label')) !!}
                            {!! Form::text('name',null,['class'=>'form-control', 'id' => 'name','placeholder'=>'Enter Customer Name' ,'required']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! Form::label('address', 'Address', array('class' => 'form-label')) !!}
                            {!! Form::text('address',null,['class'=>'form-control', 'id' => 'address','placeholder'=>'Enter Address' ]) !!}
                        </div>
                        <div class="col-md-2">
                            {!! Form::label('points', 'Points', array('class' => 'form-label')) !!}
                            {!! Form::number('points',null,['class'=>'form-control', 'id' => 'points','placeholder'=>'Points','step' => '0.1' ]) !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-3 col-lg-3">

            <div class="card ">

                <div class="card">

                    <div class="card-header bg-primary">
                        <i class="fa fa-table"></i> Invoice
                    </div>

                    <div class="card-body">


                        <div class="table-responsive pt-2">
                            <table class="table table-striped" id="purchaseTableww">
                                <thead>
                                </thead>
                                <tbody>
                                <tr>
                                    <td class="text-right" colspan="7"><b>Sub total:</b></td>
                                    <td></td>
                                    <td class="text-right">
                                        <input type="number" class="form-control text-right fw-bold text-primary" name="sub_total"
                                               id="subtotal" readonly>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="7"><b>Discount :</b></td>
                                    <td></td>

                                    <td>
                                        <input type="number" id="discount" class="text-right form-control discount"
                                               name="discount" value="0" tabindex="17" step="any" placeholder="Tk"/>
                                    </td>
                                    <td>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="7"><b>Flat Discount :</b></td>
                                    <td class="text-right">

                                        <input type="number" id="discount_percent" max="100"
                                               class="text-right form-control"
                                               placeholder="%"/>

                                    </td>

                                    <td>
                                        <input type="number" id="flatdiscount"
                                               class="text-right form-control flatdiscount"
                                               name="flatdiscount" value="0" tabindex="17" step="any" placeholder="Tk"/>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-right" colspan="8"><b>Point Redeem:</b></td>
                                    {{-- <td></td> --}}
                                    <td class="text-right">
                                        <input type="number" class="form-control text-right redeem_points"
                                               name="redeem_points"
                                               id="redeem_points" onkeyup="prevent_points_amount()"
                                               onchange="prevent_points_amount()">
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-right" colspan="8"><b>After All Discount:</b></td>

                                    <td class="text-right">
                                        <input type="number" class="form-control text-right afterdis fw-bold text-primary" name="afterdis"
                                               id="afterdis" readonly>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-right" colspan="6"><b>Vat:</b></td>
                                    <td></td>
                                    <td class="text-right">

                                        <input type="number" id="vat_percent" max="100" class="text-right form-control"
                                               placeholder="%"/>

                                    </td>
                                    <td>
                                        <input type="number" id="vat" class="text-right form-control clearVat"
                                               name="vat" value="0" step="any" placeholder="Tk"/>
                                    </td>

                                </tr>

                                <tr>
                                    <td class="text-right" colspan="8"><b>Grand Total:</b></td>

                                    <td class="text-right">
                                        <input type="number" class="form-control text-right text-success fw-bold" name="grand_total"
                                               id="grandTotal" readonly="readonly"
                                               required> {{-- <span id="grandTotal">0</span> --}}
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-right" colspan="8"><b>Given Amount :</b></td>

                                    <td class="text-right">
                                        <input type="number" id="pay" class="text-right form-control "
                                               name="paid_amount" value="0" onkeyup="prevent_paid_amount()"
                                               onchange="prevent_paid_amount()" tabindex="16" step=".01" required/>

                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-right" colspan="8"><b>Back Amount :</b></td>

                                    <td class="text-right">
                                        <input type="number" id="back" class="text-right form-control back"
                                               name="back_amount" value="0" readonly="readonly"/>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="text-right" colspan="8"><b>Due Amount :</b></td>

                                    <td class="text-right">
                                        <input type="number" id="due" class="text-right form-control" name="due_amount"
                                               value="0" readonly="readonly"/>
                                    </td>

                                </tr>


                                <tr>
                                    <td class="text-right" colspan="8"><b>Total Items :</b></td>

                                    <td class="text-right">
                                        <input type="number" id="item" class="text-right form-control" name="item"
                                               value="0" readonly="readonly"/>
                                    </td>

                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group row">


                            <div class="col-md-12">
                                <label for="payment_type" class="text-right col-form-label mt-3">Payment Name</label>

                                {{ Form::select( 'payment_method_id', $payment_methods, null, ['class' => 'form-control', 'required' ,'required'], ) }}
                                <div class="invalid-feedback">Please Add Payment Type</div>

                                <div class="card-footer text-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info" onclick="lastprint()">Last Invoice print</button>
                                        &nbsp;
                                        <button type="button" class="btn btn-primary" onclick="submitForm()">Save & Print</button>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    {{ Form::close() }}
    </div>

    @push('scripts')
        {{-- <script src="{{ asset('backend/form-validations/pharmacy/product-purchase.js') }}"></script> --}}
        <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
        <script src="{{ asset('assets/js/pos.js') }}"></script>

        <script type="text/javascript">

            let a;
            let time;
            setInterval(() => {
                a = new Date();
                time = a.getHours() + ':' + a.getMinutes() + ':' + a.getSeconds();
                document.getElementById('time').innerHTML = time;
            }, 1000);

            function prevent_stock_amount() {
                var stock = $("#stock").val();
                var qty = $("#qty").val();
                if (parseInt(qty) > parseInt(stock)) {
                    alert("Quantity not more than Stock amount.");
                    $("#qty").val("");
                }
            }

            function prevent_points_amount() {
                var cuspoint = $("#points").val();
                var point = $("#redeem_points").val();
                if (parseInt(point) > parseInt(cuspoint)) {
                    alert("Redeem Points not more than Customer Point.");
                    $("#redeem_points").val("");
                } else if (cuspoint == '' || cuspoint == 0) {
                    $("#redeem_points").val("");
                    alert("Redeem Points not more than Customer Point.");

                }
            }

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

            function clearInput3(target) {
                if (target.value == '0') {
                    target.value = "";
                }
            }

            function clearInput4(target) {
                if (target.value == '0') {
                    target.value = "";
                }
            }

            function submitForm() {
                var pay = $("#pay").val();
                if (pay == 0 || pay == '') {
                    alert("Given Amount Can Not Be Zero Or Empty.");
                } else {
                    if (confirm('Do You Want To Submit?')) {
                        const form = document.getElementById('my-form');
                        const formData = new FormData(form);

                        // Send AJAX request
                        $.ajax({
                            url: "{{ route('invoice.store') }}",
                            method: "POST",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function (response) {
                                var datas = response.data;
                                var url = "{{ route('print-invoice', ':id') }}";
                                url = url.replace(':id', datas.id);
                                window.open(url, "_blank");
                                window.location.href = "{{ route('invoice.create') }}";

                            },
                            error: function (xhr) {
                                // Handle error response
                            }
                        });

                    } else {
                        return false;
                    }
                }

                // Get form data

            }


            function lastprint() {
                // Get form data
                const form = document.getElementById('my-form');
                const formData = new FormData(form);

                $.ajax({
                    url: "{{ route('last-invoice.print') }}",
                    method: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        var datas = response.data;
                        var url = "{{ route('print-invoice', ':id') }}";
                        url = url.replace(':id', datas.id);
                        window.open(url, "_blank");
                        // window.location.href = "{{ route('invoice.create') }}";

                    },
                    error: function (xhr) {
                        // Handle error response
                    }
                });
            }

            // $(window).on('afterprint', function() {
            //   // Redirect back to the original page
            //   window.location.href = "{{ route('invoice.create') }}";
            // });


            //    function prevent_paid_amount(){
            //             var paid_amount = $("#pay").val();
            //         var grand_total_amount = $("#grandTotal").val();
            //         // if (parseInt(grand_total_amount) < parseInt(paid_amount)) {
            //         //     alert("You can not paid more than grand total amount.");
            //         //     $("#pay").val("");
            //         //    }
            //         }

            $(document).ready(function () {

                $(".clearVat,#discount,#pay,.flatdiscount").on('click', function () {
                    let input = $(this).val();
                    if (input == 0) {
                        $(this).val('');
                    }
                });

                $(window).keydown(function (event) {
                    if (event.keyCode == 13) {
                        event.preventDefault();
                        return false;
                    }
                });

                //  let  grandTotal = '';
                // $("#full_paid").on('click', function() {
                //       grandTotal = $("#grandTotal").val();
                //      $("#pay").val(grandTotal);
                // });
                // payment validations
                //   $("#discount").on('click', function(){
                //         let subTotal = $('#subtotal').val();
                //         $("#discount").attr({
                //              max: subTotal,
                //         });
                //   });
                //   $("#pay").on('click', function(){
                //     grandTotal = $("#grandTotal").val();
                //         $("#pay").attr({
                //              min: grandTotal,
                //         });
                //   });

                //   percentage live calculations
                $("#vat_percent").bind('keypress keyup keydown mouseup', function () {
                    let vat = $(this).val();
                    let subTotal = $("#afterdis").val();
                    let totalVat = calculatePercentage(subTotal, vat);

                    $("#vat").val(totalVat);
                    //    console.log(calResult);
                });

                $("#discount_percent").on('keyup', function () {
                    let vat = $(this).val();
                    let subTotal = $("#afterdis").val();
                    let totalVat = calculatePercentage(subTotal, vat);
                    $("#flatdiscount").val(totalVat);
                    //    console.log(calResult);
                });

                //   $("#discount_percent").bind('keypress keyup keydown mouseup', function(){
                //       let discount = $(this).val();
                //       let subTotal = $("#afterdis").val();
                //       let totalDiscount = calculatePercentage(subTotal, discount);
                //        $("#discount").val(totalDiscount);
                //   })

                function calculatePercentage(subTotal, vat) {
                    let result = subTotal * (vat / 100);
                    return result.toFixed(2);
                }

            })

            // select manufacturer
            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');


            $(document).ready(function () {


                $("#supplier_id").select2({
                    ajax: {
                        url: "{!! url('get-outlet') !!}",
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


                $("#user_id").select2({
                    tags: true,
                    ajax: {
                        url: "{!! url('get-user') !!}",
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

                $("#medicine_id").select2({
                    ajax: {
                        url: "{!! url('get-outlet-Stock') !!}",
                        type: "get",
                        dataType: 'json',
                        //   delay: 250,
                        data: function (params) {
                            return {
                                _token: CSRF_TOKEN,
                                search: params.term, // search term
                                // manufacturer: manufacturer_id, // search term

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

                let user_id = '';
                $("#user_id").on('change', function () {


                    user_id = $(this).val();
                    $("#adduser").click();


                });


                //  get medicine id

                // $("#user_id").on('change', function() {

                //     user_id = $(this).val();

                // });


                $("#adduser").on('click', function () {

                    // let leaf = $('#leaf').find(":selected").val();
                    // let qty = $('#box_qty').val();
                    //   let multiply = leaf*qty;


                    if (user_id) {
                        $.ajax({
                            url: "{!! url('get-user-details') !!}" + "/" + user_id,
                            type: "GET",
                            dataType: "json",
                            beforeSend: function () {

                            },

                            success: function (data) {
                                if (data != null) {
                                    $('#name').first().val(data.name);
                                    $('#address').val(data.address);
                                    $('#points').val(data.points);

                                } else {
                                    alert('Data not found!');

                                }
                            },

                            complete: function () {

                            }

                        });
                    } else
                        alert("Please Select User");

                });

                $("#medicine_id").select2({
                    ajax: {
                        url: "{!! url('get-outlet-Stock') !!}",
                        type: "get",
                        dataType: 'json',
                        //   delay: 250,
                        data: function (params) {
                            return {
                                _token: CSRF_TOKEN,
                                search: params.term, // search term
                                // manufacturer: manufacturer_id, // search term

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


                // 13 is the code for return


                //    let medicine_id = '';
                //     $("#medicine_id").on('change', function() {

                //         medicine_id = $(this).val();

                //     });
                let medicine_id = '';

                $('#medicine_id').on('select2:select', function (e) {


                    medicine_id = $(this).val();

                    $("#addProductRow").click();
                    // Do something
                });


                $("#barcode").keyup(function (e) {
                    if (e.which == 13) {


                        medicine_id = $(this).val();
                        $("#addProductRow").click();
                    } else {
                        // Do whatever you like
                    }
                    e.preventDefault();
                });


                $("#addProductRow").click(function () {

                    // let leaf = $('#leaf').find(":selected").val();
                    // let qty = $('#box_qty').val();
                    //   let multiply = leaf*qty;
                    // let qty = $('#qty').val();
                    // // clear input text after click
                    // $('#qty').val('');


                    if (medicine_id) {
                        $.ajax({
                            url: "{!! url('get-medicine-details-for-sale') !!}" + "/" + medicine_id,
                            type: "GET",
                            dataType: "json",
                            beforeSend: function () {

                            },

                            success: function (data) {
                                if (data != null) {
                                    $('.stock_id').first().val(data.id);
                                    $('.pr_id').first().val(data.medicine_id);
                                    $('#expiry_date').first().val(data.expiry_date);
                                    $('#product_name').first().val(data.medicine_name);
                                    $('.stock-qty').first().val(data.quantity);
                                    $('.qty').first().val('1');
                                    $('#price').val(data.price);
                                    $('.total').first().val(1 * data.price);


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
                    totaldis: ".totaldis",
                    // totalQty: "#totalQty",

                    subtotal: "#subtotal",
                    discount: "#discount",
                    flatdiscount: "#flatdiscount",
                    discountt: "#discountt",
                    // shipping : "#shipping",
                    vat: "#vat",
                    grandTotal: "#grandTotal",
                    afterdis: "#afterdis",
                    pay: "#pay",
                    back: ".back",
                    points: "#redeem_points",
                    item: "#item",
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
