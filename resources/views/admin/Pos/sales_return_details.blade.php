

@extends('layouts.admin.master')
@section('title')Sale Return Details
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
			<h3>Sale Return Details</h3>
        </div>

        </div>

		@endslot

        @slot('button')
        <a href="{{ route('sale-return.index') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Back</a>
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
                    <div class="col-md-4">Return Id : <b>{{ $salesReturn->id }}</b></div>
                    <div class="col-md-4">
                        <p class="text-center"><b>Invoice No : {{ $salesReturn->invoice_id }}</b></p>
                    </div>

                <div class="row">
                    <div class="col-md-3">Return Date :
                        <b>{{ \Carbon\Carbon::parse($salesReturn->return_date)->format('d-m-Y') }}</b></div>



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
                    <th>Name Of Name of medicine</th>
                    <th>Purchase Quantity</th>
                    <th>Return Quantity</th>
                    <th>Price</th>
                    <th>Amount</th>
                </tr>

                @foreach ($salesreturndetails as $data)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $data->medicine_name }}</td>
                        <td>{{ $data->sold_qty }}</td>
                        <td>{{ $data->return_qty }}</td>
                        <td>{{ $data->rate }}</td>
                        <td>{{ $data->total_price }}</td>
                    </tr>
                @endforeach

            </table>

            <div class="row">
                <div class="col-md-7">




                </div>
                <div class="col-md-5">

                    <table class="table table-borderless mt-3">
                        <tr>
                            <td>Sub Total</td>
                            <td>{{ $salesReturn->sub_total }}</td>
                        </tr>
                        <tr>
                            <td>Deduct Amount</td>
                            <td>{{ $salesReturn->deduct_amount }}</td>
                        </tr>
                        <tr>
                            <td>Grand Total</td>
                            <td>{{ $salesReturn->grand_total }}</td>
                        </tr>
                        <tr>
                            <td>Return Amount</td>
                            <td>{{ $salesReturn->paid_amount }}</td>
                        </tr>
                        <tr>
                            <td>Due Amount</td>
                            <td>{{  $salesReturn->due_amount }}</td>
                        </tr>
                        <tr>
                            <td>Deduct Point</td>
                            <td>{{ $salesReturn->deduct_point }}</td>
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
