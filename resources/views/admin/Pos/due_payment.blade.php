

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
            {!! Form::open(['route' => ['paymentDue'], 'method' => 'POST', 'class' => 'needs-validation']) !!}

            <div class="service_invoice_header">
                <div class="row">
                    <div class="col-md-4">Invoice No : <b>{{ $outletInvoice->id }}</b></div>

                    <div class="col-md-4">Purchase Date :
                        <b>{{ \Carbon\Carbon::parse($outletInvoice->sale_date)->format('d-m-Y') }}</b></div>
                </div>


                       <input type="hidden" name="outlet_invoice_id" value="{{ $outletInvoice->id }}">
                <div class="row">
                    <div class="col-md-3">Customer Mobile : <b>
                        @if ($outletInvoice->customer_id == null)
                            N/A
                        @elseif ($outletInvoice->customer_id != null)
                            {{ $outletInvoice->customer->mobile }}
                        @endif
                    </b></div>



                </div>
                <div class="col-md-4">Sale By : <b>{{ Auth::user()->name }}
                </b></div>


            </div>


            <table class="table table-bordered mt-2">
                <tr>
                    <th>SL</th>
                    <th>Name Of Medicine</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Amount</th>
                </tr>

                @foreach ($outletInvoiceDetails as $data)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $data->medicine->medicine_name }}</td>
                        <td>{{ $data->quantity }}</td>
                        <td>{{ $data->rate }}</td>
                        <td>{{ $data->discount }}</td>
                        <td>{{ ($data->quantity*$data->rate) - $data->discount}}</td>
                    </tr>
                @endforeach

            </table>
            @if (count($saledetails) !=0)

  <br>
  <h5>Due Payment Details</h5>

            <table class="table table-bordered mt-2">
                <tr>
                    <th>Invoice Id</th>
                    <th>Customer Name</th>
                    <th>Amount</th>
                    <th>Pay</th>
                    <th>Due</th>

                </tr>

                @foreach ($saledetails as $data)
                    <tr>
                        <td>{{ $data->invoice_id }}</td>
                        <td>{{ $data->customer->name }}</td>
                        <td>{{ $data->amount }}</td>
                        <td>{{ $data->pay }}</td>
                        <td>{{ $data->due }}</td>

                    </tr>
                @endforeach

            </table>
            @endif

            <div class="row">
                <div class="col-md-7">
                    <br>
                    @if ($outletInvoice->due_amount > 0)
                        <table class="table table-borderless">
                            <tr>
                                <th>Payable</th>
                                <td>{{ Form::number('due_amount', $outletInvoice->due_amount, ['class' => 'form-control', 'readonly', 'id' => 'due_amount']) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Flat Discount</th>
                                <td>
                                    <input class="form-control" type="number" name="discount" id="discount" value="" onkeyup="prevent_paid_amount()" placeholder="0.00"
                                    onchange="prevent_paid_amount()">
                                </td>
                            </tr>
                            <tr>
                                <th>Grand Total</th>
                                <td>

                                    <input class="form-control" type="number" name="total" id="total" value="0" step="any" readonly>

                                </td>
                            </tr>
                            <tr>
                                <th>Pay Now</th>
                                <td>

                                    <input class="form-control" type="number" name="paid_amount" id="paid_amount" value="" step="any" placeholder="0.00" required>

                                </td>
                            </tr>


                            <tr>
                                <th>Due</th>
                                <td>

                                    <input class="form-control" type="number" name="due" id="due" value="0"
                                           onkeyup="prevent_due_amount()" placeholder="0.00" step="any"
                                           onchange="prevent_due_amount()" readonly>

                                </td>
                            </tr>

                            <tr>
                                <th>Payment Method</th>
                                <td>

                                    {{ Form::select( 'payment_method_id', $payment, null, ['class' => 'form-control', 'required' ,'required'], ) }}

                                </td>
                            </tr>

                            <tr>
                                <td>

                                </td>
                                <td>{{ Form::button('Update', ['type' => 'submit', 'class' => 'btn btn-warning btn-sm'] )  }}</td>
                            </tr>

                        </table>

                    @else
                        <h2 class="paid">Paid</h2>
                    @endif



                </div>
                <div class="col-md-5">
                    <table class="table table-borderless">
                        <tr>
                            <td>Sub Total</td>
                            <td>{{ $outletInvoice->sub_total }}</td>
                        </tr>
                        <tr>
                            <td>Vat</td>
                            <td>{{ $outletInvoice->vat }}</td>
                        </tr>
                        <tr>
                            <td>Discount</td>
                            <td>{{ $outletInvoice->total_discount }}</td>
                        </tr>
                        <tr>
                            <td>Grand Total</td>
                            <td>{{ $outletInvoice->grand_total }}</td>
                        </tr>
                        <tr>
                            <td>Paid Amount</td>
                            <td>{{ $outletInvoice->grand_total - $outletInvoice->due_amount }}</td>
                        </tr>
                        <tr>
                            <td>Due Amount</td>
                            <td>{{ $outletInvoice->due_amount }}</td>
                        </tr>
                    </table>
                </div>

            </div>

            {!! Form::close() !!}

        </div>
    </div>
</div>

@push('scripts')

<script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
<script type="text/javascript">



function prevent_paid_amount() {
                var discount = $("#discount").val();
                var due_amount = $("#due_amount").val();
                if (parseFloat(discount) > parseFloat(due_amount)) {
                    alert("You can not Discount more than Due amount.");
                    $("#discount").val("");
                }
            }
$(document).ready(function () {
let subTotal = $("#due_amount").val();
$("#total").val(subTotal);

$("#discount").bind('keypress keyup keydown mouseup', function () {
let discount = $(this).val();
let subTotal = $("#due_amount").val();
let finalpay = (subTotal - discount);
finalpay = finalpay.toFixed();
$("#total").val(finalpay);
$("#due").val(finalpay);
                //    console.log(calResult);

});
$("#paid_amount").bind('keypress keyup keydown mouseup', function () {
let total = $(this).val();
let subTotal = $("#total").val();
let finaldue = (subTotal - total);
finaldue = finaldue.toFixed(2);
$("#due").val(finaldue);
                //    console.log(calResult);

});

});
</script>
@if (Session()->get('success'))

<script type="text/javascript">
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
