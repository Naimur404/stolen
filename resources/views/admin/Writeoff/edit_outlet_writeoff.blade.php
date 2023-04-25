@extends('layouts.admin.master')

@section('title')Show Outlet Writeoff

@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
			<h3>Show Outlet Writeoff</h3>
		@endslot
        @slot('button')
        <a href="{{ route('outlet-writeoff.index') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Back</a>
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
	                    <div class="card-body p-4">

                            <div class="mb-3 mt-2">
                                <p style="text-align: left"><b>Outlet Name:&nbsp;&nbsp; </b> {{ $outletWriteoff->outlet->outlet_name }}</p>
                            </div>
                            <div class="mb-3 mt-2">
                                <p style="text-align: left"><b>Medicine Name:&nbsp;&nbsp; </b> {{ $outletWriteoff->medicine_name }}</p>
                            </div>

                            <div class="mb-3 mt-2">


                                <p style="text-align: left"><b>Expiry Date:&nbsp;&nbsp;</b> {{ $outletWriteoff->stock->expiry_date }}</p>
                            </div>
                            <div class="mb-3 mt-2">
                                <p style="text-align: left"><b>Outlet Name:&nbsp;&nbsp;</b> {{ $outletWriteoff->stock->outlet->outlet_name }}</p>
                        </div>
                            <div class="mb-3 mt-2">
                                <p style="text-align: left"><b>Previous Quantity :&nbsp;&nbsp;</b> {{ $outletWriteoff->previous_stock }}</p>
                        </div>
                        <div class="mb-3 mt-2">
                            <p style="text-align: left"><b>Writeoff Quantity :&nbsp;&nbsp;</b> {{ $outletWriteoff->quantity }}</p>
                        </div>
                        <div class="mb-3 mt-2">
                            <p style="text-align: left"><b>Writeoff Type :&nbsp;&nbsp;</b> {{ $outletWriteoff->type == 'sub' ? 'Subtraction': 'Addition'}}</p>
                        </div>


                    <div class="mb-3 mt-2">
                        <p style="text-align: left"><b>Writeoff Date :&nbsp;&nbsp;</b> {{ \Carbon\Carbon::parse($outletWriteoff->created_at)->format('d-m-Y') }}</p>
                </div>
                <div class="mb-3 mt-2">
                    <p style="text-align: left"><b>Reason :&nbsp;&nbsp;</b> {{ $outletWriteoff->reason }}</p>
            </div>
            <div class="mb-3 mt-2">
                <p style="text-align: left"><b>Remarks :&nbsp;&nbsp;</b>@if($outletWriteoff->remarks != '') {{ $outletWriteoff->remarks }} @else N/A
                    @endif</p>
        </div>
        <div class="mb-3 mt-2">
            <p style="text-align: left"><b>Added By :&nbsp;&nbsp;</b> {{ $outletWriteoff->user->name }}</p>
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
