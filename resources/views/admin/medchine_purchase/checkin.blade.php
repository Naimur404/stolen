@extends('layouts.admin.master')
@section('title')Product Checkin
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
			<h3>Medicine Checkin</h3>
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
                    <div class="col-md-4">Invoice Id : <b>{{ $productPurchase->id }}</b></div>
                    <div class="col-md-4">

                    </div>
                    <div class="col-md-4">Purchase Date :
                        <b>{{ \Carbon\Carbon::parse($productPurchase->purchase_date)->format('d-m-Y') }}</b></div>
                </div>

                <div class="row">
                    <div class="col-md-3">Supplier Name : </div>
                    <div class="col-md-4"> <b>
                            @if ($productPurchase->supplier_id == null)
                                N/A
                            @elseif ($productPurchase->supplier_id != null)
                                {{ $productPurchase->supplier->supplier_name }}
                            @endif
                        </b></div>


                </div>


            </div>


            <table class="table table-bordered mt-2">
                <tr>
                    <th>SL</th>
                    <th>Name Of Medicine</th>
                    <th>Quantity</th>
                    <th>Purchase Price</th>
                    <th>Sell Price</th>
                    <th>Size</th>
                    <th>Action</th>
                </tr>

                @foreach ($productPurchaseDetails as $data)

                {!! Form::open(['route' => 'warehouse-stock.store' ,'method' => 'post' ,'class' => 'needs-validation testform', 'novalidate'=> '','id'=>'testform']) !!}
                    <tr>
                          <input type="hidden" name="warehouse_id" value="{{ $productPurchase->warehouse_id }}">
                          <input type="hidden" name="purchase_id" value="{{ $productPurchase->id }}">
                          <input type="hidden" name="medicine_id" value="{{$data->medicine_id }}">
                          <input type="hidden" name="create_date" value="{{$data->create_date }}">
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $data->medicine_name }}</td>
                        <td>{{ Form::number('quantity', $data->quantity, ['class' => 'form-control', 'readonly']) }}
                        </td>
                        <td>{{ Form::number('purchase_price', $data->manufacturer_price, ['class' => 'form-control', 'readonly']) }}
                        </td>
                        <td>{{ Form::number('price', $data->box_mrp, ['class' => 'form-control', 'readonly']) }}
                            </td>
                        <td>{{ Form::text('size', $data->size, ['class' => 'form-control', 'readonly']) }}
                           </td>
                        <td>
                            @php
                                $data = App\Models\WarehouseCheckIn::where('purchase_id',$productPurchase->id)->where('medicine_id',$data->medicine_id)->where('size','=',$data->size)->where('create_date','=',$data->create_date)->first();
                            @endphp
                            @if (is_null($data))
                            <button type="submit" class="btn btn-success save_purchase_btn" tabindex="19" id="save_purchasess">
                                Receive
                            </button>
                            @else
                            <button type="submit" class="btn btn-danger" tabindex="19" id="save_purchase" disabled >
                              Already  Receive
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
<script type="text/javascript">
  $(document).ready(function() {
    $('.save_purchase_btn').on('click', function() {
      var $form = $(this).closest('.testform');
      if (confirm("Are you sure you want to Click Receive?")) {
        $form.submit(); // Submit the form
      }else{
        return false;
      }
    });
  });
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
