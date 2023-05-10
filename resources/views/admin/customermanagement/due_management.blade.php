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
            {!! Form::open(['route' => ['customer-due-payment'], 'method' => 'POST', 'class' => 'needs-validation', 'novalidate'=> '']) !!}
              <input type="hidden" name="customer_id" value="{{ $customer->id }}">
              <input type="hidden" name="outlet_id" value="{{ $customer->outlet_id }}">
            <div class="service_invoice_header">
                <div class="row">
                    <div class="col-md-4">Customer Name: {{ $customer->name }}</div>

                    <div class="col-md-4">Customer Address :
                        {{ $customer->address }}</div>
                </div>

                <div class="row">
                    <div class="col-md-3">Customer Mobile :  @if ($customer->mobile == null)
                    <b>  N/A
                    @elseif ($customer->mobile != null)
                        {{ $customer->mobile }}
                    @endif
                </b> </div>
                </div>
            </div>


            <table class="table table-bordered mt-2">
                <tr>
                    <th>Invoice</th>
                    <th>Date</th>
                    <th>Total Amount</th>
                    <th>Paid</th>
                    <th>Due</th>
                </tr>

                @foreach ($invoices as $data)
                    <tr>
                        <td>{{ $data->id }}</td>
                        <td> {{ \Carbon\Carbon::parse($data->sale_date)->format('d-m-Y') }} </td>
                        <td>{{ $data->grand_total }}</td>
                        <td>{{ $data->paid_amount }}</td>
                        <td>{{ $data->due_amount}}</td>

                    </tr>
                @endforeach

            </table>

            <div class="row">
                <div class="col-md-7">
                    <br>

                        <table class="table table-borderless">
                            <tr>
                                <th>Payable</th>
                                <td>{{ Form::number('due_amount', $customer->due_balance, ['class' => 'form-control', 'readonly']) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Pay Now</th>
                                <td>{{ Form::number('paid_amount', $customer->due_balance, ['class' => 'form-control', 'required', 'step'=>'any']) }}
                                    @error('paid_amount')
                                    <div class="invalid-feedback2"> {{ $message }}</div>

                                @enderror
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    {{-- {{ Form::hidden('invoice_id', $productPurchase->id) }}

                                    {{ Form::hidden('payable', $productPurchase->due_amount) }} --}}
                                </td>
                                <td>{{ Form::button('Update', ['type' => 'submit', 'class' => 'btn btn-warning btn-sm'] )  }}</td>
                            </tr>
                        </table>





                </div>
                <div class="col-md-5">
                    <table class="table table-borderless">

                        <tr>
                            <td>Due Amount</td>
                            <td>{{ $customer->due_balance }}</td>
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
