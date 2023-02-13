


@extends('layouts.admin.master')

@section('title',' All Return Medicine To Warehouse')

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-8">
			<h3>All Return Medicine To Warehouse</h3>
        </div>

        </div>
		@endslot

        @slot('button')
        <a href="{{ route('warehouse-return.create') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Return Medicine To Warehouse</a>
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
                                        <th>Outlet Name</th>
                                        <th>Warehouse Name</th>

                                        {{-- <th>Product Type</th> --}}
                                        <th>Added By</th>
                                        <th>Remarks</th>



                                        @if (auth()->user()->can('distribute-medicine.edit') || auth()->user()->can('distribute-medicine.delete'))
                                        <th>Action</th>
                                        @endif
                                    </tr>
	                            </thead>
	                            <tbody>
                                    @foreach ($warehousereturns as $warehousereturn)
                                    {{-- @php
                                        $data = App\Models\MedicineDistributeDetail::where('medicine_distribute_id',$productPurchase->id)->get();
                                    @endphp --}}
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        @if ( $warehousereturn->outlet_id == null)

                                            <td> N/A </td>
                                        @elseif ( $warehousereturn->outlet_id
                                            != null)

                                            <td>{{ $warehousereturn->outlet->outlet_name }}</td>
                                        @endif

                                          @if ( $warehousereturn->warehouse_id == null)

                                            <td> N/A </td>
                                        @elseif ( $warehousereturn->warehouse_id
                                            != null)

                                            <td>{{ $warehousereturn->warehouse->warehouse_name }}</td>
                                        @endif


                                        <td>{{ Auth::user()->name }}</td>
                                        <td>{{ $warehousereturn->remarks }}</td>






                                        <td class="form-inline uniqueClassName">
                                            @can('warehouse-return.edit')
                                                <a href="{{ route('warehouse-return.edit', $warehousereturn->id) }}"
                                                    class="btn btn-success btn-xs" title="Pay Now" style="margin-right:10px; "><i class="fa-light fa-money-bill"></i>Edit</a>
                                            @endcan

                                            {{-- @can('product_purchase.print')
                                            <a href="{{ route('medicine-purchase.show', $productPurchase->id) }}" class="btn btn-info btn-xs"  title="Print Invoice" target="__blank" style="margin-right:3px"><i class="fas fa-print"></i></a>
                                            @endcan --}}

                                            @can('warehouse-return.delete')
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['warehouse-return.destroy', $warehousereturn->id]]) !!}
                                                {{ Form::button('Delete', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'id' => 'delete', 'title' => 'Delete']) }}
                                                {!! Form::close() !!}
                                            @endcan


                                        </td>

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



















