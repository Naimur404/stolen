

@extends('layouts.admin.master')
@section('title')Add Distribute Medicine to Outlet
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
            <div class="col-sm-8">
			<h3>Add Distribute Medicine to Outlet</h3>
        </div>

        </div>

		@endslot

        @slot('button')
        <a href="{{ route('distribute-medicine.index') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Back</a>
        @endslot
	@endcomponent


<div class="col-md-12 col-lg-12">
    <div class="card ">


        <div class="card-body mt-3">

            {!! Form::open(['route' => ['distribute-medicine.store'] ,'method' => 'POST', 'class' => 'needs-validation', 'novalidate'=> '', 'autocomplete' => 'off', 'id' => 'product_purchase']) !!}
            <input type="hidden" name="">
            <div class="form-group row">
                {{-- <label for="manufacturer" class="col-md-2 text-right col-form-label">{{ __('purchase.manufacturer') }}:</label>
                <div class="col-md-4">
                    {{ Form::select('manufacturer_id', [], null, ['class' => 'form-control', 'placeholder' => __('purchase.select_manufacturer'), 'id' => 'manufacturer_id']) }}
                </div> --}}

                <label for="supplier" class="col-md-2 text-right col-form-label">Outlet:</label>
                <div class="col-md-4">
                    @if(!is_null($data->outlet_id))
                    @php

                          $data2 = App\Models\Outlet::where('id',$data->outlet_id)->first();
                          $id = $data2->id;
                          $name = $data2->outlet_name
                    @endphp
                    {{ Form::select('outlet_id', [$id=>$name], null, ['class' => 'form-control', 'id' => 'supplier_id' ,'required']) }}
                    @else
                    {{ Form::select('outlet_id', [], null, ['class' => 'form-control', 'placeholder' => 'Select Outlet', 'id' => 'supplier_id' ,'required']) }}
                    @endif

                    <div class="invalid-feedback">Please Add Outlet</div>
                    @error('outlet_id')
                    <div class="invalid-feedback2"> {{ $message }}</div>

                @enderror
                </div>
                <label for="invoicePhoto" class="col-md-2 text-right">Warehouse Name * :</label>
                <div class="col-md-4">

                    {{ Form::select( 'warehouse_id', $warehouse, null, ['class' => 'form-control', 'required'] ) }}




                    <div class="invalid-feedback">Please Add Warehouse</div>
                    @error('warehouse_id')
                    <div class="invalid-feedback2"> {{ $message }}</div>

                @enderror
                </div>
            </div>


            <div class="form-group row">



                <label for="date" class="col-md-2 text-right col-form-label">Date <i class="text-danger">
                            * </i>:</label>
                <div class="col-md-4">
                    <input type="text" name="purchase_date" class="form-control datepicker" id="purdate" placeholder="Purchase Date" value="{{ Carbon\Carbon::today()->toDateString() }}" tabindex="2" required>
                </div>
                {!! Form::label('remarks', 'Remarks:', array('class' => 'col-md-2 text-right')) !!}
                <div class="col-md-4">

                    {!! Form::text('remarks',$data->remarks,['class'=>'form-control', 'id' => 'name','placeholder'=>'Enter Remarks Here' ]) !!}
                    @error('remarks')
                    <div class="invalid-feedback2"> {{ $message }}</div>

                @enderror
                </div>
            </div>






            <div class="card">

                <div class="card-header bg-secondary">
                    <i class="fa fa-table"></i> Make Distribute Medicin
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-3">
                            <h2>Add Medicine</h2>
                        </div>

                        <div class="col-md-4">
                            @if(!is_null($medicinedetails))
                            {{ Form::select('', [], null, ['class' => 'form-control', 'placeholder' => 'Select medicine', 'id' => 'medicine_id']) }}
                             @else
                             {{ Form::select('', [], null, ['class' => 'form-control', 'placeholder' => 'Select medicine', 'id' => 'medicine_id' ,'required']) }}
                             <div class="invalid-feedback">Please Add Medicine</div>
                             @endif




                        </div>

                        {{--
                        <div class="col-md-4">
                            {{ Form::number('', '', array('class'=> 'form-control', 'placeholder' =>'Quantity', 'id'=>'qty'))}}
                        </div> --}}

                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary addProductRow" id="addProductRow" >Add</button>
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
                                        <nobr>Barcode<i class="text-danger">*</i></nobr>
                                    </th>
                                    <th class="text-center">
                                        <nobr>Expiry Date<i class="text-danger">*</i></nobr>
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
                                        <nobr>Quantity <i class="text-danger">*</i></nobr>
                                    </th>
                                    <th class="text-center">
                                        <nobr>Manufacturer Price<i class="text-danger">*</i></nobr>
                                    </th>
                                    <th class="text-center">
                                        <nobr>Box MRP<i class="text-danger">*</i></nobr>
                                    </th>
                                    <th class="text-center">
                                        <nobr>Product Type <i class="text-danger">*</i></nobr>
                                    </th>
                                    <th class="text-center">
                                        <nobr>Total_Price</nobr>
                                    </th>
                                    <th class="text-center">
                                        <nobr>Action</nobr>
                                    </th>
                                </tr>
                                @foreach ($medicinedetails as $details)


                                <tr class="item-row">
                                    <td>
                                    <input class="form-control pr_id" type="hidden" name="product_id[]"  readonly="" value="{{ $details->medicine_id }}">
                                    <input class="form-control stock_id" type="hidden" name="stock_id[]"  readonly="" value="{{ $details->warehouse_stock_id }}">
                                     <input class="form-control product_name" type="text" name="product_name[]" id="product_name" readonly="" required="" value="{{ $details->medicine_name }}"> </td>
                                     <td><input class="form-control barcode" type="text" name="barcode[]" placeholder="Barocde"  value="{{ $details->barcode_text }}"></td>
                                     <td><input class="form-control invoice_datepicker" type="date" name="expiry_date[]" placeholder="Expiry Date" id="expiry_date" required="" value="{{ $details->expiry_date }}"></td>
                                     <td><input class="form-control qty" type="number" name="quantity[]" placeholder="Quantity" required="" value="{{ $details->quantity }}"></td>
                                     @php
                                         $medicine = App\Models\Medicine::where('id',$details->medicine_id)->first();
                                     @endphp
                                     <td><input class="form-control price" type="number" step="any" name="manufacturer_price[]" id="manufacturer_price" required="" value="{{ $medicine->manufacturer_price }}"></td>
                                     <td><input class="form-control" name="box_mrp[]" type="number" step="any" id="box_price" required="" value="{{ $medicine->price }}"</td>
                                     <td><input class="form-control" name="product_type[]" type="text" id="product_type" readonly="" required="" value="medicine"></td>
                                     <td><input class="form-control" name="total_price[]" type="text" id="product_type" readonly="" required="" value="{{ $medicine->manufacturer_price * $details->quantity }}"></td>


                                </tr>
                                @endforeach
                            </thead>
                            <tbody>





                            </tbody>
                        </table>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6 text-right">
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="card-footer text-end">
                            <div class="mt-4">


                                <button type="submit" class="btn btn-success" tabindex="19" id="save_purchase">
                                        Submit
                                    </button>
                            </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

	@push('scripts')
    {{-- <script src="{{ asset('backend/form-validations/pharmacy/product-purchase.js') }}"></script> --}}
    <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
    <script src="{{ asset('assets/js/product_purchase_invoice.js') }}"></script>
    <script type="text/javascript">

       function prevent_paid_amount(){
                var paid_amount = $("#pay").val();
            var grand_total_amount = $("#grandTotal").val();
            if (parseInt(grand_total_amount) < parseInt(paid_amount)) {
                alert("You can not paid more than grand total amount.");
                $("#pay").val("");
               }
            }

        $(document).ready(function() {

            $(".clearVat,#discount,#pay").on('click', function(){
                let input = $(this).val();
                    if(input == 0){
                        $(this).val('');
                    }
            })


             let  grandTotal = '';
            $("#full_paid").on('click', function() {
                  grandTotal = $("#grandTotal").val();
                 $("#pay").val(grandTotal);
            });
            // payment validations
          $("#discount").on('click', function(){
                let subTotal = $('#subtotal').val();
                $("#discount").attr({
                     max: subTotal,
                });
          });
          $("#pay").on('click', function(){
            grandTotal = $("#grandTotal").val();
                $("#pay").attr({
                     max: grandTotal,
                });
          });

        //   percentage live calculations
          $("#vat_percent").bind('keypress keyup keydown mouseup', function(){
              let vat = $(this).val();
              let subTotal = $("#subtotal").val();
              let totalVat = calculatePercentage(subTotal, vat);
               $("#vat").val(totalVat);
            //    console.log(calResult);
          })

          $("#discount_percent").bind('keypress keyup keydown mouseup', function(){
              let discount = $(this).val();
              let subTotal = $("#subtotal").val();
              let totalDiscount = calculatePercentage(subTotal, discount);
               $("#discount").val(totalDiscount);
          })

          function calculatePercentage(subTotal, vat){
                 let result = subTotal*(vat/100);
                 return result;
          }

        })

        // select manufacturer
        let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function() {


            $("#supplier_id").select2({
                ajax: {
                    url: "{!! url('get-outlet') !!}",
                    type: "get",
                    dataType: 'json',
                    //   delay: 250,
                    data: function(params) {
                        return {
                            _token: CSRF_TOKEN,
                            search: params.term,
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }

            });

              // get manufacturer id
            //   $("#supplier_id").on('change', function() {
            //     supplier_id = $(this).val();
            // })

            // manufacturer wise medicine selection
            $("#medicine_id").select2({
                ajax: {
                    url: "{!! url('get-all-medicine') !!}",
                    type: "get",
                    dataType: 'json',
                    //   delay: 250,
                    data: function(params) {
                        return {
                            _token: CSRF_TOKEN,
                            search: params.term, // search term
                            // manufacturer: manufacturer_id, // search term

                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }

            });





            //  get medicine id
            let medicine_id = '';
            $("#medicine_id").on('change', function() {

                medicine_id = $(this).val();

            });

            $("#addProductRow").on('click', function() {

                // let leaf = $('#leaf').find(":selected").val();
                // let qty = $('#box_qty').val();
                //   let multiply = leaf*qty;
                let qty = $('#qty').val();
                // clear input text after click
                $('#qty').val('');


                if (medicine_id) {
                    $.ajax({
                        url: "{!! url('get-medicine-details-for-purchase') !!}" + "/" + medicine_id,
                        type: "GET",
                        dataType: "json",
                        beforeSend: function() {

                        },

                        success: function(data) {
                            if (data != null) {
                                $('.pr_id').first().val(data.medicine_id);
                                $('.stock_id').first().val(data.id);
                                $('.barcode').first().val(data.barcode_text);
                                $('.stock').first().val(data.quantity);
                                $('#qty').first().val('1');
                                $('#product_name').val(data.medicine_name);
                                $('#box_price').first().val(data.price);
                                $('#manu_price').first().val(data.purchase_price);
                                $('#expiry_date').val(data.expiry_date);
                            } else {
                                alert('Data not found!');

                            }
                        },

                        complete: function() {

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
