@extends('layouts.admin.master')
@section('title') Add Distribute Medicine to Outlet Checkin
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
            <div class="col-sm-10">
			<h3> Add Distribute Medicine to Outlet Checkin</h3>
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



            <div class="service_invoice_header">
                <div class="row">
                    <div class="col-md-4">Medicine Distribute Id : <b>{{ $productPurchase->id }}</b></div>
                    <div class="col-md-4">

                    </div>
                    <div class="col-md-4">Date :
                        <b>{{ \Carbon\Carbon::parse($productPurchase->date)->format('d-m-Y') }}</b></div>
                </div>

                <div class="row">

                    <div class="col-md-4"> Outlet Name : <b>
                            @if ($productPurchase->outlet_id == null)
                                N/A
                            @elseif ($productPurchase->outlet_id != null)
                                {{ $productPurchase->outlet->outlet_name }}
                            @endif
                        </b></div>


                </div>


            </div>


            <table class="table table-bordered mt-2">
                <tr>
                    <th>SL</th>
                    <th>Name Of Medicine</th>
                    <th>Quantity</th>
                    <th>Rack No</th>
                    <th>Price</th>
                    <th>Expiry Date</th>
                    <th>Action</th>
                </tr>

                @foreach ($productPurchaseDetails as $data)
                {!! Form::open(['route' => 'outlet-stock.store' ,'class' => 'needs-validation', 'novalidate'=> '']) !!}
                    <tr>
                        <input type="hidden" name="outlet_id" value="{{ $productPurchase->outlet_id }}">

                          <input type="hidden" name="warehouse_id" value="{{ $productPurchase->warehouse_id }}">
                          <input type="hidden" name="medicine_distribute_id" value="{{ $productPurchase->id }}">
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $data->medicine_name }}</td>
                        <td>{{ Form::number('quantity', $data->quantity, ['class' => 'form-control', 'readonly']) }}
                        </td>
                        <td>{{ Form::number('rack_no', $data->rack_no, ['class' => 'form-control', 'readonly']) }}
                            </td>
                            <td>{{ Form::number('price', $data->rate, ['class' => 'form-control', 'readonly']) }}
                            </td>
                        <td>{{ Form::date('expiry_date', $data->expiry_date, ['class' => 'form-control', 'readonly']) }}
                            <input type="hidden" name="medicine_id" value="{{$data->medicine_id }}">
                           </td>
                        <td>
                            @php
                                $data = App\Models\OutletCheckIn::where('medicine_distribute_id',$productPurchase->id)->where('medicine_id',$data->medicine_id)->first();
                            @endphp
                            @if (is_null($data))
                            <button type="submit" class="btn btn-success" tabindex="19" id="save_purchase"  >
                                Medicine Distribute
                            </button>
                            @else
                            <button type="submit" class="btn btn-danger" tabindex="19" id="save_purchase" disabled >
                              Already  Distribute
                            </button>
                            @endif
                          </td>
                    </tr>
                    {!! Form::close() !!}
                @endforeach


            </table>





        </div>
    </div>
</div>

@push('scripts')
<script>


</script>

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
