
@extends('layouts.admin.master')

@section('title','Medicine Request To Warehouse')

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-8">
			<h3>Medicine Request To Warehouse</h3>
        </div>

        </div>
		@endslot

        @slot('button')
        <a href="{{ route('stock-request.create') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Stock Request</a>
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
                                        <th>Sent Status</th>
                                        <th>Request Status</th>

                                        {{-- <th>Product Type</th> --}}
                                        <th>Added By</th>
                                        <th>Remarks</th>




                                        <th>Action</th>
                                       
                                    </tr>
	                            </thead>
	                            <tbody>
                                    @foreach ($stockrequets as $stockrequet)
                                    {{-- @php
                                        $data = App\Models\MedicineDistributeDetail::where('medicine_distribute_id',$productPurchase->id)->get();
                                    @endphp --}}
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        @if ( $stockrequet->outlet_id == null)

                                            <td> N/A </td>
                                        @elseif ( $stockrequet->outlet_id
                                            != null)

                                            <td>{{ $stockrequet->outlet->outlet_name }}</td>
                                        @endif

                                          @if ( $stockrequet->warehouse_id == null)

                                            <td> N/A </td>
                                        @elseif ( $stockrequet->warehouse_id
                                            != null)

                                            <td>{{ $stockrequet->warehouse->warehouse_name }}</td>
                                        @endif

                                        <td>@if ($stockrequet->has_sent == 1)

                                            <div class="media-body text-end icon-state">

                                                <label class="switch">
                                                    <a href="javascript:void(0)">
                                                  <input type="checkbox" checked><span class="switch-state"></span>
                                                </a>
                                                </label>

                                              </div>
                                              @elseif ($stockrequet->has_sent == 0)
                                              <div class="media-body text-end icon-state">

                                                <label class="switch">
                                                    <a href="javascript:void(0)">
                                                  <input type="checkbox"><span class="switch-state"></span>
                                                </a>
                                                </label>

                                              </div>
                                            @endif</td>


                                        <td>@if ($stockrequet->has_accepted == 1)

                                            <div class="media-body text-end icon-state">

                                                <label class="switch">
                                                    <a href="javascript:void(0)">
                                                  <input type="checkbox" checked><span class="switch-state"></span>
                                                </a>
                                                </label>

                                              </div>
                                              @elseif ($stockrequet->has_accepted == 0)
                                              <div class="media-body text-end icon-state">

                                                <label class="switch">
                                                    <a href="javascript:void(0)">
                                                  <input type="checkbox"><span class="switch-state"></span>
                                                </a>
                                                </label>

                                              </div>
                                            @endif</td>
                                        <td>{{ Auth::user()->name }}</td>
                                        <td>{{ $stockrequet->remarks }}</td>






                                        <td class="form-inline">
                                            @if($stockrequet->has_accepted == 0)
                                            @can('sent_stock_request')
                                                <a href="{{ route('stock-request.edit', $stockrequet->id) }}"
                                                    class="btn btn-success btn-xs" title="Edit" style="margin-right:10px"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                            @endcan
                                            @endif

                                            {{-- @can('product_purchase.print')
                                            <a href="{{ route('medicine-purchase.show', $productPurchase->id) }}" class="btn btn-info btn-xs"  title="Print Invoice" target="__blank" style="margin-right:3px"><i class="fas fa-print"></i></a>
                                            @endcan --}}

                                            @can('sent_stock_request')
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['stock-request.destroy', $stockrequet->id]]) !!}
                                                {{ Form::button('<i class="fa fa-trash"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'id' => 'delete', 'title' => 'Delete']) }}
                                                {!! Form::close() !!}
                                            @endcan
                                            @can('sent_stock_request')
                                            <a href="{{ route('stock-request.details', $stockrequet->id) }}"
                                                class="btn btn-info btn-xs" title="Details" style="margin-left: 10px"> <i class="fa fa-eye" aria-hidden="true"></i></a>
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
