

@extends('layouts.admin.master')
@section('title')Invoice Details
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
			<h3>Invoice Details</h3>
        </div>

        </div>

		@endslot

        @slot('button')
        <a href="{{ route('invoice.index') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Back</a>
        @endslot
	@endcomponent
<div class="col-md-12 col-lg-12">
    <div class="card">
        <div class="card-header py-2">
            <div class="d-flex justify-content-between align-items-center">


            </div>
        </div>

        <div class="card-body">


            <div class="service_invoice_header">
                <div class="row">
                    <div class="col-md-4">Invoice No : <b>{{ $salesReturn->id }}</b></div>
                    <div class="col-md-4">
                        <p class="text-center"></p>
                    </div>

                <div class="row">
                    <div class="col-md-3">Return Date :
                        <b>{{ \Carbon\Carbon::parse($salesReturn->sale_date)->format('d-m-Y') }}</b></div>



                </div>

                <div class="row">
                    <div class="col-md-3">Customer Name :   <b>{{ $salesReturn->customer->mobile }}</b></div>



                </div>
                <div class="row">
                    <div class="col-md-3">Outlet Name : {{ $salesReturn->outlet->outlet_name }}</div>





                </div>

            </div>


            <table class="table table-bordered mt-2">
                <tr>
                    <th>SL</th>
                    <th>Name Of Product</th>
                    <th>Size</th>
                    <th>Purchase Quantity</th>
                    <th>Return Quantity</th>
                    <th>Price</th>
                    <th>Amount</th>
                </tr>

                @foreach ($salesreturndetails as $data)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>

                        <td>{{ $data->medicine_name }}</td>
                        <td>{{ $data->size }}</td>
                        <td>{{ $data->quantity }}</td>
                        @php
                            $data1 = App\Models\SalesReturn::where('invoice_id', $data->outlet_invoice_id ?? false)->first();
                            $return = App\Models\SalesReturnDetails::where('sales_return_id', $data1->id ?? false)->where('medicine_id', $data->medicine_id)->where('size','=', $data->size)->first();

                        @endphp
                        <td>{{ $return->return_qty ?? '0' }}</td>

                        <td>{{ $data->rate }}</td>
                        <td>{{ $data->total_price }}</td>
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
                    <th>Ammount</th>
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




                </div>
                <div class="col-md-5">
                    <table class="table table-borderless">
                        <tr>
                            <td>Sub Total</td>
                            <td>{{ $salesReturn->sub_total }}</td>
                        </tr>
                        <tr>
                            <td>Vat</td>
                            <td>{{ $salesReturn->vat }}</td>
                        </tr>
                        <tr>
                            <td>Discount</td>
                            <td>{{ $salesReturn->total_discount }}</td>
                        </tr>
                        <tr>
                            <td>Grand Total</td>
                            <td>{{ $salesReturn->grand_total }}</td>
                        </tr>
                        <tr>
                            <td>Paid Amount</td>
                            <td>{{ $salesReturn->grand_total - $salesReturn->due_amount }}</td>
                        </tr>
                        <tr>
                            <td>Due Amount</td>
                            <td>{{ $salesReturn->due_amount }}</td>
                        </tr>
                    </table>
                </div>

            </div>



        </div>
    </div>
</div>
</div>

@push('scripts')

<script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
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
