@extends('layouts.admin.master')

@section('title')Show Warehouse Writeoff

@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
			<h3>Show Warehouse Writeoff</h3>
		@endslot
        @slot('button')
        <a href="{{ route('warehouse-writeoff.index') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Back</a>
          @endslot
	@endcomponent

	<div class="container-fluid">
	    <div class="edit-profile">
	        <div class="row">
                <div class="col-xl-2">
                </div>
	            <div class="col-xl-8">
	                <div class="card">
	                    <div class="card-header pb-0">

	                        <div class="card-options">
	                            <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
	                        </div>
	                    </div>
	                    <div class="card-body">

                            <div class="mb-3 mt-2">
                                <p style="text-align: left"><b>Warehouse Name:&nbsp;&nbsp; </b> {{ $warehouseWriteoff->warehouse->warehouse_name }}</p>
                            </div>
                            <div class="mb-3 mt-2">
                                <p style="text-align: left"><b>Medicine Name:&nbsp;&nbsp; </b> {{ $warehouseWriteoff->medicine_name }}</p>
                            </div>

                            <div class="mb-3 mt-2">


                                <p style="text-align: left"><b>Expiry Date:&nbsp;&nbsp;</b> {{ $warehouseWriteoff->stock->expiry_date }}</p>
                            </div>
                            <div class="mb-3 mt-2">
                                <p style="text-align: left"><b>warehouse Name:&nbsp;&nbsp;</b> {{ $warehouseWriteoff->stock->warehouse->warehouse_name }}</p>
                        </div>
                            <div class="mb-3 mt-2">
                                <p style="text-align: left"><b>Previous Quantity :&nbsp;&nbsp;</b> {{ $warehouseWriteoff->previous_stock }}</p>
                        </div>
                        <div class="mb-3 mt-2">
                            <p style="text-align: left"><b>Writeoff Quantity :&nbsp;&nbsp;</b> {{ $warehouseWriteoff->quantity }}</p>
                        </div>

                        <div class="mb-3 mt-2">
                            <p style="text-align: left"><b>Writeoff Type :&nbsp;&nbsp;</b> {{ $warehouseWriteoff->type == 'sub' ? 'Subtraction': 'Addition'}}</p>
                        </div>
                    <div class="mb-3 mt-2">
                        <p style="text-align: left"><b>Writeoff Date :&nbsp;&nbsp;</b> {{ \Carbon\Carbon::parse($warehouseWriteoff->created_at)->format('d-m-Y') }}</p>
                </div>
                <div class="mb-3 mt-2">
                    <p style="text-align: left"><b>Reason :&nbsp;&nbsp;</b> {{ $warehouseWriteoff->reason }}</p>
            </div>
            <div class="mb-3 mt-2">
                <p style="text-align: left"><b>Remarks :&nbsp;&nbsp;</b>@if($warehouseWriteoff->remarks != '') {{ $warehouseWriteoff->remarks }}  @else N/A
                    @endif</p>
        </div>
        <div class="mb-3 mt-2">
            <p style="text-align: left"><b>Added By :&nbsp;&nbsp;</b> {{ $warehouseWriteoff->user->name }}</p>
    </div>
	                    </div>
	                </div>
	            </div>
                <div class="col-xl-2">
                </div>

	        </div>
	    </div>
	</div>

            </div>
        </div>
    </div>



@endsection
