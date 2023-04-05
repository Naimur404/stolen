@extends('layouts.admin.master')

@section('title')All warehouse Writeoff

@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-8">
			<h3>All warehouse Writeoff</h3>
        </div>

        </div>
		@endslot

        @slot('button')
        <a href="{{ route('warehouse-writeoff.create') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">New</a>
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
                                        <th>SI</th>
	                                    <th>Medicine Name</th>

                                        <th>Pre Quantity</th>

                                        <th>Quantity</th>
                                        <th>Reason</th>
                                        <th>Action</th>


                                    </tr>
	                            </thead>
	                            <tbody>
                                    @foreach ($datas as $data)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>

                                        <td>{{ $data->medicine_name }}</td>
                                        <td>{{ $data->previous_stock }}</td>
                                        <td>{{ $data->quantity}}</td>
                                        <td>{{ $data->reason}}</td>
                                        <td class="form-inline">


                                        <a href="{{ route('warehouse-writeoff.show', $data->id) }}"
                                            class="btn btn-success btn-xs" title="Show" style="margin-right:3px"><i class="fa fa-eye" aria-hidden="true"></i></a>



                                        </td>
                                        {{-- @endif --}}
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
</div>
	@push('scripts')



    <script type="text/javascript">

$(document).ready(function() {
            $('.data-table').DataTable();
        });

   </script>

    {{-- <script src="{{asset('assets/js/ecommerce.js')}}"></script> --}}
    {{-- <script src="{{asset('assets/js/product-list-custom.js')}}"></script> --}}
	@endpush

@endsection
