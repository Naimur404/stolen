@extends('layouts.admin.master')
@section('title')Edit Purchase
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
			<h3>Edit Purchase</h3>
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
            {!! Form::open(['route' => 'purchase-update', 'method' => 'POST', 'class' => 'needs-validation', 'novalidate'=> '']) !!}

            <div class="service_invoice_header">
                <div class="row">
                    <div class="col-md-4">Invoice Id : <b>{{ $productPurchase->id }}</b></div>
                    <div class="col-md-4">
                        <p class="text-center"><b>Invoice No : {{ $productPurchase->invoice_no}}</b></p>
                    </div>
                    <div class="col-md-4">Purchase Date :
                        <b>{{ \Carbon\Carbon::parse($productPurchase->purchase_date)->format('d-m-Y') }}</b></div>
                </div>

                <div class="row">
                    <div class="col-md-3">Supplier Name : </div>
                    <div class="col-md-4"> <b>
                            @if ($productPurchase->supplier_id == null)
                                N/A
                            @elseif ($productPurchase->supplier_id != null)
                                {{ $productPurchase->supplier->supplier_name }}
                            @endif
                        </b></div>


                </div>
                <input type="hidden" name="pid" value="{{ $productPurchase->id }}">
                <input type="hidden" name="pdid" value="{{ $data->id }}">
                {{-- <div class="row">
                    <div class="col-md-3">Manufacturer Name : </div>
                    <div class="col-md-4"><b>
                            @if ($productPurchase->manufacturer_id == null)
                                N/A
                            @elseif ($productPurchase->manufacturer_id != null)
                                {{ $productPurchase->manufacturer->manufacturer_name }}
                            @endif
                        </b></div>

                </div> --}}

            </div>


            <table class="table table-bordered mt-2">
                <tr>
                    <th>Name Of Medicine</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>MRP</th>
                    <th>Amount</th>
                </tr>
                    <tr>
                        <td>{{ $data->medicine_name }}</td>
                        <td><input class="form-control" type="text" name="size" value="{{$data->size }}" required>
                        </td>
                        <td> <input type="number" name="qty" id="qty" value="{{ $data->quantity }}" class="form-control" required></td>
                        <td><input type="number" name="price" id="price" value="{{ $data->manufacturer_price }}" step="any" class="form-control" required>
                           </td>
                           <td><input type="number" name="box_mrp" id="box_mrp" value="{{ $data->box_mrp }}" step="any" class="form-control" required>
                           </td>
                        <td><input type="number" name="final" id="final" value="{{ $data->quantity*$data->manufacturer_price}}" class="form-control" readonly>
                            </td>
                    </tr>
            </table>

            {{ Form::button('Update', ['type' => 'submit', 'class' => 'btn btn-warning btn-sm mt-5'] )  }}
            {!! Form::close() !!}

        </div>
    </div>
</div>

@push('scripts')

<script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
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

        $("#qty,#price").bind('keypress keyup keydown mouseup', function () {
            qty = $("#qty").val();
            price =  $("#price").val();
            total = qty*price;
            $("#final").val(total);

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
