


@extends('layouts.admin.master')

@section('title',' All Distribute Medicine to Outlet')

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-8">
			<h3>All Distribute Medicine to Outlet</h3>
        </div>

        </div>
		@endslot

        @slot('button')
        <a href="{{ route('distribute-medicine.create') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Distribute Medicine</a>
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
                                    @foreach ($medicinedistributes as $productPurchase)
                                    {{-- @php
                                        $data = App\Models\MedicineDistributeDetail::where('medicine_distribute_id',$productPurchase->id)->get();
                                    @endphp --}}
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        @if ( $productPurchase->outlet_id == null)

                                            <td> N/A </td>
                                        @elseif ( $productPurchase->outlet_id
                                            != null)

                                            <td>{{ $productPurchase->outlet->outlet_name }}</td>
                                        @endif

                                          @if ( $productPurchase->warehouse_id == null)

                                            <td> N/A </td>
                                        @elseif ( $productPurchase->warehouse_id
                                            != null)

                                            <td>{{ $productPurchase->warehouse->warehouse_name }}</td>
                                        @endif


                                        <td>{{ Auth::user()->name }}</td>
                                        <td>{{ $productPurchase->remarks }}</td>





                                        @if (auth()->user()->can('distribute-medicine.edit') || auth()->user()->can('distribute-medicine.delete'))
                                        <td class="">
                                            @can('medchine_purchase.edit')
                                                <a href="{{ route('distribute-medicine.edit', $productPurchase->id) }}"
                                                    class="btn btn-success btn-xs mt-2" title="Pay Now" style="margin-right:3px"><i class="fa-light fa-money-bill"></i>Edit</a>
                                            @endcan

                                            {{-- @can('product_purchase.print')
                                            <a href="{{ route('medicine-purchase.show', $productPurchase->id) }}" class="btn btn-info btn-xs"  title="Print Invoice" target="__blank" style="margin-right:3px"><i class="fas fa-print"></i></a>
                                            @endcan --}}

                                            @can('distribute-medicine.delete')
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['distribute-medicine.destroy', $productPurchase->id]]) !!}
                                                {{ Form::button('Delete', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs mt-2', 'id' => 'delete', 'title' => 'Delete']) }}
                                                {!! Form::close() !!}
                                            @endcan

                                            <a href="{{ route('distribute-medicine.index', $productPurchase->id) }}"
                                                class="btn btn-info btn-xs mt-2" title="Pay Now" style="margin-left:3px"><i class="fa-light fa-money-bill"></i>Check In</a>

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
	<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
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



















