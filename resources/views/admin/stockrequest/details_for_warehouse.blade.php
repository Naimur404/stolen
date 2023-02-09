

@extends('layouts.admin.master')
@section('title')Medicine Stock Request Details
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
			<h3>Medicine Stock Request Details</h3>
        </div>

        </div>

		@endslot

        @slot('button')

        <a href="{{ route('warehouseRequest') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Back</a>

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
                    <div class="col-md-6">Stock Request Id : <b>{{ $stockrequest->id }}</b></div>

                    <div class="col-md-4">Request Date :
                        <b>{{ \Carbon\Carbon::parse($stockrequest->date)->format('d-m-Y') }}</b></div>
                </div>

                <div class="row">
                    <div class="col-md-3 mt-2">Outlet Name :  <b> @if ($stockrequest->outlet_id == null)
                        N/A
                    @elseif ($stockrequest->outlet_id != null)
                        {{ $stockrequest->outlet->outlet_name }}
                    @endif

</b>

                </div>
                <div class="row">
                    <div class="col-md-3 mt-2">Warehouse Name :     <b>
                        @if ($stockrequest->warehouse_id == null)
                            N/A
                        @elseif ($stockrequest->warehouse_id != null)
                            {{ $stockrequest->warehouse->warehouse_name }}
                        @endif
                    </b></div>


                </div>

            </div>


            <table class="table table-bordered mt-4">
                <tr>
                    <th>SL</th>
                    <th>Name Of Investigation</th>
                    <th>Quantity</th>
                    <th>Accept Status</th>

                </tr>

                @foreach ($stockDetails as $data)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        @php
                        $medicine = App\Models\Medicine::where('id',$data->medicine_id)->first();
                    @endphp
                        <td>{{ $medicine->medicine_name }}</td>
                        <td>{{ $data->quantity }}</td>
                        <td>
                            @if ($data->has_accepted == 1)

                                            <div class="media-body text-end icon-state">

                                                <label class="switch">
                                                    <a href="{{ url('/has_accepted/'.$stockrequest->id.'/0/'.$data->medicine_id.'/medicine') }}">
                                                  <input type="checkbox" checked><span class="switch-state"></span>
                                                </a>
                                                </label>

                                              </div>
                                              @elseif ($data->has_accepted == 0)
                                              <div class="media-body text-end icon-state">

                                                <label class="switch">
                                                    <a href="{{ url('/has_accepted/'.$stockrequest->id.'/1/'.$data->medicine_id.'/medicine') }}">
                                                  <input type="checkbox"><span class="switch-state"></span>
                                                </a>
                                                </label>

                                              </div>
                                            @endif

                        </td>
                    </tr>
                @endforeach

            </table>


        </div>
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
