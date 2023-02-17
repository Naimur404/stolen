


@extends('layouts.admin.master')

@section('title',' All Purchase')

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
			<h3>All Purchase</h3>
        </div>

        </div>
		@endslot

        @slot('button')
        <a href="{{ route('medicine-purchase.create') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Purchase</a>
        @endslot
	@endcomponent

	<div class="container-fluid list-products">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">

	                <div class="card-body">
	                    <div class="table-responsive product-table">
	                        <table class="display data-table">
	                            <thead>
	                                <tr>
                                        <th>SL</th>

                                        <th>Supplier</th>
                                        <th>Purchase Date</th>
                                        {{-- <th>Product Type</th> --}}
                                        <th>Payment Method</th>
                                        <th>Total</th>
                                        <th>Pay</th>
                                        <th>Due</th>


                                        @if (auth()->user()->can('medchine_purchase.edit') || auth()->user()->can('medchine_purchase.delete'))
                                        <th>Action</th>
                                        @endif
                                    </tr>
	                            </thead>
	                            <tbody>
                                    @foreach ($productPurchases as $productPurchase)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        @if ( $productPurchase->supplier_id == null)

                                            <td> N/A </td>
                                        @elseif ( $productPurchase->supplier_id
                                            != null)

                                            <td>{{ $productPurchase->supplier->supplier_name }}</td>
                                        @endif

                                        <td>{{ \Carbon\Carbon::parse($productPurchase->purchase_date)->format('d-m-Y')}}
                                        </td>
                                        <td>@php
                                           $data = App\Models\PaymentMethod::where('id',$productPurchase->payment_method_id)->first();
                                        @endphp
                                            {{ $data->method_name }}</td>

                                        <td>{{ $productPurchase->grand_total }}</td>
                                        <td>{{ $productPurchase->paid_amount }}</td>
                                        @if ($productPurchase->due_amount > 0)
                                            <td> {{ $productPurchase->due_amount }} </td>
                                        @else
                                            <td>Paid</td>
                                        @endif



                                        @if (auth()->user()->can('medchine_purchase.edit') || auth()->user()->can('medchine_purchase.delete'))
                                        <td class="form-inline">
                                            @can('medchine_purchase.edit')
                                                <a href="{{ route('medicine-purchase.edit', $productPurchase->id) }}"
                                                    class="btn btn-success btn-xs" title="Pay Now" style="margin-right:3px"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                            @endcan

                                            {{-- @can('product_purchase.print')
                                            <a href="{{ route('medicine-purchase.show', $productPurchase->id) }}" class="btn btn-info btn-xs"  title="Print Invoice" target="__blank" style="margin-right:3px"><i class="fas fa-print"></i></a>
                                            @endcan --}}

                                            @can('medchine_purchase.delete')
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['medicine-purchase.destroy', $productPurchase->id]]) !!}
                                                {{ Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'id' => 'delete', 'title' => 'Delete']) }}
                                                {!! Form::close() !!}
                                            @endcan

                                            <a href="{{ route('medicine-purchase.checkIn', $productPurchase->id) }}"
                                                class="btn btn-info btn-xs " title="Pay Now" style="margin-left:3px"><i class="fa fa-eye" aria-hidden="true"></i></a>

                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
	                            </tbody>
	                        </table>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <!-- Individual column searching (text inputs) Ends-->
	    </div>
	</div>

	@push('scripts')

    <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>

    <script type="text/javascript">
       $(document).ready(function() {
            $('.data-table').DataTable();
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
    {{-- <script src="{{asset('assets/js/ecommerce.js')}}"></script> --}}
    {{-- <script src="{{asset('assets/js/product-list-custom.js')}}"></script> --}}
	@endpush

@endsection



















