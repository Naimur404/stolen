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
            {!! Form::open(['route' => ['product-purchase.update', $productPurchase->id], 'method' => 'PUT', 'class' => 'needs-validation', 'novalidate'=> '']) !!}

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
                    <div class="col-md-3">Supplier Name :  @if ($productPurchase->supplier_id == null)
                      <b>  N/A
                    @elseif ($productPurchase->supplier_id != null)
                        {{ $productPurchase->supplier->supplier_name }}
                    @endif
                </b> </div>



                </div>
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
                    <th>SL</th>
                    <th>Name Of Medicine</th>
                    <th>Quantity</th>
                    <th>Manufacturer Price</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>

                @foreach ($productPurchaseDetails as $data)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $data->medicine_name }}</td>
                        <td>{{ $data->quantity }}</td>
                        <td>{{ $data->manufacturer_price }}</td>
                        <td>{{ $data->quantity*$data->manufacturer_price}}</td>
                        <td><a href="{{ route('edit-purchase',$data->id) }}"
                            class="btn btn-success btn-xs" title="Edit" style="margin-right:3px"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <a href="{{ route('purchase-delete',$data->id) }}"
                                class="btn btn-danger btn-xs" title="Edit" style="margin-right:3px"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                @endforeach

            </table>

            <div class="row">
                <div class="col-md-7">
                    <br>
                    @if ($productPurchase->due_amount > 0)
                        <table class="table table-borderless">
                            <tr>
                                <th>Payable</th>
                                <td>{{ Form::number('due_amount', $productPurchase->due_amount, ['class' => 'form-control', 'readonly']) }}
                                </td>
                            </tr>
                            <tr>
                                <th>Pay Now</th>
                                <td>{{ Form::number('paid_amount', $productPurchase->due_amount, ['class' => 'form-control', 'required']) }}
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

                    @else
                        <h2 class="paid">Paid</h2>
                    @endif



                </div>
                <div class="col-md-5">
                    <table class="table table-borderless">
                        <tr>
                            <td>Sub Total</td>
                            <td>{{ $productPurchase->sub_total }}</td>
                        </tr>
                        <tr>
                            <td>Vat</td>
                            <td>{{ $productPurchase->vat }}</td>
                        </tr>
                        <tr>
                            <td>Discount</td>
                            <td>{{ $productPurchase->total_discount }}</td>
                        </tr>
                        <tr>
                            <td>Grand Total</td>
                            <td>{{ $productPurchase->grand_total }}</td>
                        </tr>
                        <tr>
                            <td>Paid Amount</td>
                            <td>{{ $productPurchase->paid_amount }}</td>
                        </tr>
                        <tr>
                            <td>Due Amount</td>
                            <td>{{ $productPurchase->due_amount }}</td>
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
