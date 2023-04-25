@extends('layouts.admin.master')

@section('title')Create Warehouse Writeoff

@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
			<h3>Create Warehouse Writeoff</h3>
		@endslot
        @slot('button')
        <a href="{{ route('warehouse-writeoff.index') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Back</a>
          @endslot
	@endcomponent

	<div class="container-fluid">
	    <div class="edit-profile">
	        <div class="row">
                <div class="col-xl-2">
                </div>
	            <div class="col-xl-8">
	                <div class="card">
	                    <div class="card-header pb-0">

	                        <div class="card-options">
	                            <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
	                        </div>
	                    </div>
	                    <div class="card-body">
                            {!! Form::open(['route'=>'warehouse-writeoff.store', 'method'=>'POST', 'role' => 'form', 'class' => 'needs-validation', 'novalidate'=> '','files' => true]) !!}

                            <label for="supplier" class="col-md-2 text-right col-form-label">Warehouse:</label>
                            <div class="mb-3 mt-2">
                                {{ Form::select( 'warehouse_id', $warehouse, null, ['class' => 'form-control', 'required', 'placeholder' => 'Select Warehouse', 'id' => 'warehouse'] ) }}
                                <div class="invalid-feedback">Please Add Warehouse</div>
                                @error('warehouse_id')
                                <div class="invalid-feedback2"> {{ $message }}</div>

                            @enderror
                            </div>
                            <label for="supplier" class="col-md-2 text-right col-form-label">Medicine</label>
                            <div class="mb-3 mt-2">
                                {{ Form::select('medicine_id', [], null, ['class' => 'form-control', 'placeholder' => 'Select medicine', 'id' => 'medicine_id' ]) }}
                                <div class="invalid-feedback">Please Add Medicine</div>
                                <input type="hidden" name="stock_id" id="stock_id">
                                <input type="hidden" name="medicine" id="medicine">
                                <input type="hidden" name="medicine_name" id="medicine_name">



                            </div>
                            <div class="mb-3">
                                {{ Form::label('type', 'Type *') }}
                                {{ Form::select('type', [ 'sub' =>'Subtraction','add' => 'Addition'], null, ['class' => 'form-control', 'required']) }}
                                @error('gender')
                                <div class="invalid-feedback2"> {{ $message }}</div>
                            @enderror
                                {{-- <label class="form-label">Email-Address</label>
                                <input class="form-control" placeholder=""  readonly value="{{ $Profile->email }}"/> --}}
                            </div>
                            <div class="mb-3 mt-2">
                                {{ Form::label('pre_quantity', 'Stock Quantity ') }}
                                {{ Form::number('pre_quantity', null, ['class' => 'form-control' ,'id' => 'pre_quantity', 'readonly']) }}
                                @error('quantity')
                                <div class="invalid-feedback2"> {{ $message }}</div>
                            @enderror
                        </div>
                            <div class="mb-3 mt-2">
                                {{ Form::label('quantity', 'Quantity ') }}
                                {{ Form::number('quantity', null, ['class' => 'form-control']) }}
                                @error('quantity')
                                <div class="invalid-feedback2"> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 mt-2">
                            <label class="form-label">Reason</label>
                            {{ Form::select('reason', [' Expired' => ' Expired', 'Damage' =>'Damage', 'Lost' =>'Lost', 'Adjust' =>'Adjust', 'Others' =>'Others'], null, ['class' => 'form-control', 'required']) }}
                        </div>
                        @error('status')
                        <div class="invalid-feedback2"> {{ $message }}</div>
                    @enderror

                    <div class="mb-3 mt-2">
                        {{ Form::label('remarks', 'Remarks ') }}
                        {{ Form::text('remarks', null, ['class' => 'form-control' ,'required']) }}
                        @error('remarks')
                        <div class="invalid-feedback2"> {{ $message }}</div>
                    @enderror
                </div>
	                            <div class="form-footer">
                                    {!!  Form::submit('Submit',['class'=> 'btn btn-primary btn-block mt-4']); !!}

	                            </div>
                                {{ Form::close(); }}
	                    </div>
	                </div>
	            </div>
                <div class="col-xl-2">
                </div>

	        </div>
	    </div>
	</div>

            </div>
        </div>
    </div>
	@push('scripts')
    {{-- <script src="{{ asset('backend/form-validations/pharmacy/product-purchase.js') }}"></script> --}}
    <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
    <script src="{{ asset('assets/js/warehouse_return.js') }}"></script>
    <script type="text/javascript">
    function clearInput1(target){
        if (target.value== '0'){
            target.value= "";
       }
    }
    function clearInput2(target){
        if (target.value== '0'){
            target.value= "";
       }
    }
    function prevent_stock_amount(){
                var stock = $("#stock").val();
            var qty = $("#qty").val();
            if (parseInt(qty) > parseInt(stock)) {
                alert("Quantity not more than Stock amount.");
                $("#qty").val("");
               }
            }
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


            $("#outlet_id").select2({
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






            //  get medicine id





            let warehouse_id = '';
            // manufacturer wise medicine selection
            $("#warehouse").on('change', function() {
                warehouse_id = $(this).val();
            $("#medicine_id").select2({
                ajax: {
                    url: "{!! url('get-warehouse-Stock') !!}"  + "/" + warehouse_id,
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





                // let leaf = $('#leaf').find(":selected").val();
                // let qty = $('#box_qty').val();
                //   let multiply = leaf*qty;
                let qty = $('#qty').val();
                // clear input text after click
                // $('#qty').val('');


                if (medicine_id) {
                    $.ajax({
                        url: "{!! url('get-medicine-details-warehouse') !!}" + "/" + medicine_id  + "/" + warehouse_id,
                        type: "GET",
                        dataType: "json",
                        beforeSend: function() {

                        },

                        success: function(data) {
                            if (data != null) {
                                $('#stock_id').val(data.id);
                                $('#medicine').val(data.medicine_id);
                                $('#pre_quantity').val(data.quantity);
                                $('#medicine_name').val(data.medicine_name);



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
