
@extends('layouts.admin.master')
@section('title')Exchange Details
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
                    <h3>Exchange Details</h3>
                </div>

            </div>

        @endslot

        @slot('button')
            <a href="{{ route('exchange.index') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Back</a>
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
                        <div class="col-md-4">Invoice No : <b>{{ $exchange->original_invoice_id }}</b></div>
                        <div class="col-md-4">
                            <p class="text-center"></p>
                        </div>

                        <div class="row">
                            <div class="col-md-3">Exchange Date :
                                <b>{{ \Carbon\Carbon::parse($exchange->created_at)->format('d-m-Y') }}</b></div>



                        </div>

                        <div class="row">
                            <div class="col-md-3">Customer Name : <b>{{ $exchange->customer->mobile }}</b></div>



                        </div>
                        <div class="row">
                            <div class="col-md-3">Outlet Name : {{ $exchange->outlet->outlet_name }}</div>





                        </div>

                    </div>


                    <table class="table table-bordered mt-2">
                        <tr>
                            <th>SL</th>
                            <th>Name Of Product</th>
                            <th>Size</th>
                            <th>Purchase Quantity</th>
                            <th>Exchange Quantity</th>
                            <th>Price</th>
                            <th>Amount</th>
                        </tr>

                        @foreach ($exchangeDetails as $data)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td>

                                <td>{{ $data->medicine_name }}</td>
                                <td>{{ $data->size }}</td>
                                <td>{{ $data->is_exchange == '0' ? $data->quantity :'N/A' }}</td>
                                <td>{{ $data->is_exchange == '1' ? $data->quantity :'N/A' }}</td>

                                <td>{{ $data->rate }}</td>
                                <td>{{ $data->total_price }}</td>
                            </tr>
                        @endforeach

                    </table>

                    <div class="row">
                        <div class="col-md-7">




                        </div>

                    </div>



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
