@extends('layouts.admin.master')
@section('title')
    Add Distribute Product to Outlet Checkin
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
                    <h3> Add Distribute Product to Outlet Checkin</h3>
                </div>

            </div>

        @endslot


        @slot('button')
        @if (Auth::user()->hasRole(['Super Admin', 'Admin']))
        @if($productPurchase->has_sent == 1)
        <a href="javscript:void()"
        class="btn btn-danger btn-xs" title="Recived"
        style="margin-right:5px"><i class="fa fa-check"
        aria-hidden="true"></i>All This Product Distribute Done</a>
        @else
        {!! Form::open(['route' => 'all-in-one' ,'class' => 'needs-validation testform2', 'novalidate'=> '']) !!}
        <input type="hidden" name="disid" value="{{ $productPurchase->id }}">
        <input type="hidden" name="warehouse_id" value="{{ $productPurchase->warehouse_id }}">
        <button type="submit" class="btn btn-success dristribute" tabindex="19" id="dristribute">
            All Dristribute
        </button>
        {!! Form::close() !!}
        @endif
@else

@if($productPurchase->has_received == 1)
<a href="javscript:void()"
class="btn btn-danger btn-xs" title="Recived"
style="margin-right:5px"><i class="fa fa-check"
aria-hidden="true"></i>All This Product Recieved Done</a>
@else
{!! Form::open(['route' => 'all-in-one-outlet' ,'class' => 'needs-validation testform3', 'novalidate'=> '']) !!}
<input type="hidden" name="medicine_distribute_id" value="{{ $productPurchase->id }}">
<input type="hidden" name="warehouse_id" value="{{ $productPurchase->warehouse_id }}">
<input type="hidden" name="outlet_id" value="{{ $productPurchase->outlet_id }}">
<button type="submit" class="btn btn-success received" tabindex="19" id="received">
    All Recieved
</button>
{!! Form::close() !!}
@endif
@endif
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
                        <th>Name Of Product</th>
                        <th>Quantity</th>
                        <th>Barcode</th>
                        <th>Price</th>
                        <th>Size</th>
                        <th>Action</th>
                    </tr>

                    @foreach ($productPurchaseDetails as $data)
                        @if (Auth::user()->hasRole(['Super Admin', 'Admin']))
                            {!! Form::open(['route' => ['warehouse-stock.update', $productPurchase->warehouse_id ] ,'class' => 'needs-validation testfrom', 'novalidate'=> '' ,'method'=>'PUT',]) !!}
                            <tr>

                                <input type="hidden" name="outlet_id" value="{{ $productPurchase->outlet_id }}">

                                <input type="hidden" name="warehouse_id" value="{{ $productPurchase->warehouse_id }}">
                                <input type="hidden" name="medicine_distribute_id" value="{{ $productPurchase->id }}">

                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $data->medicine_name }}</td>
                                <td>{{ Form::number('quantity', $data->quantity, ['class' => 'form-control', 'readonly']) }}
                                    <input type="hidden" name="stock_id"
                                    value="{{ $productPurchase->warehouse_stock_id }}">
                                </td>
                                <td>{{ Form::number('barcode', $data->barcode_text, ['class' => 'form-control', 'readonly']) }}
                                </td>
                                <td>{{ Form::number('price', $data->rate, ['class' => 'form-control', 'readonly']) }}
                                </td>

                                <td>{{ Form::text('size', $data->size, ['class' => 'form-control', 'readonly']) }}
                                    <input type="hidden" name="medicine_id" value="{{$data->medicine_id }}">
                                    <input type="hidden" name="create_date" value="{{$data->create_date }}">
                                </td>
                                <td>

                                    @if ($data->has_sent == 0)
                                        <button type="submit" class="btn btn-success save_purchase_btn" tabindex="19" id="save_purchase">
                                            Product Distribute
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-danger" tabindex="19" id="save_purchase"
                                                disabled>
                                            Already Distribute
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            {!! Form::close() !!}
                        @else
                            {!! Form::open(['route' => 'outlet-stock.store' ,'class' => 'needs-validation testform', 'novalidate'=> '']) !!}
                            <tr>

                                <input type="hidden" name="outlet_id" value="{{ $productPurchase->outlet_id }}">

                                <input type="hidden" name="warehouse_id" value="{{ $productPurchase->warehouse_id }}">
                                <input type="hidden" name="medicine_distribute_id" value="{{ $productPurchase->id }}">
                                <input type="hidden" name="medicine_id" value="{{$data->medicine_id }}">
                                <input type="hidden" name="create_date" value="{{$data->create_date }}">
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $data->medicine_name }}</td>
                                <td>{{ Form::number('quantity', $data->quantity, ['class' => 'form-control', 'readonly']) }}
                                    <input type="hidden" name="stock_id" value="{{ $data->warehouse_stock_id ?? null }}">
                                </td>
                                <td>{{ Form::number('barcode', $data->barcode_text, ['class' => 'form-control', 'readonly']) }}
                                </td>
                                <td>{{ Form::number('price', $data->rate, ['class' => 'form-control', 'readonly']) }}
                                </td>
                                <td>{{ Form::text('size', $data->size, ['class' => 'form-control', 'readonly']) }}

                                </td>
                                <td>

                                    @if ($data->has_received == 0)
                                        <button type="submit" class="btn btn-success save_purchase_btn" tabindex="19" id="save_purchase">
                                            Checkin
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-danger" tabindex="19" id="save_purchase"
                                                disabled>
                                            CheckIn Done
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            {!! Form::close() !!}
                        @endif
                    @endforeach


                </table>


            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $(document).ready(function() {
          $('.save_purchase_btn').on('click', function() {
            var $form = $(this).closest('.testform');
            if (confirm("Are you sure you want to Click ?")) {
              $form.submit(); // Submit the form
            }else{
              return false;
            }
          });
          $('.dristribute').on('click', function() {
            var $form = $(this).closest('.testform2');
            if (confirm("Are you sure you want to Click ?")) {
              $form.submit(); // Submit the form
            }else{
              return false;
            }
             });
          $('.received').on('click', function() {
            var $form = $(this).closest('.testform3');
            if (confirm("Are you sure you want to Click ?")) {
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
