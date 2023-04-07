@extends('layouts.admin.master')

@section('title',' All Invoice')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <div class="row">
                <div class="col-sm-6">
                    <h3> All Invoice</h3>
                </div>

            </div>
        @endslot

        @slot('button')
            <a href="{{ route('invoice.create') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn"
               title="">New Sale</a>
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
                                    <th>ID</th>
                                    <th>Sale Date</th>
                                    <th>Outlet Name</th>
                                    <th>Customer</th>
                                    {{-- <th>Product Type</th> --}}

                                    <th>Method</th>
                                    <th>Total</th>
                                    <th>Pay</th>


                                    <th>Sold By</th>
                                    <th>Action</th>

                                    {{-- @if (auth()->user()->can('invoice.edit') || auth()->user()->can('invoice.delete'))
                                    <th>Action</th>
                                    @endif --}}
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->id }}</td>

                                        <td>{{ \Carbon\Carbon::parse($invoice->sale_date)->format('d-m-Y')}}
                                        </td>
                                        <td>{{ $invoice->outlet->outlet_name ?? 'N/A' }}</td>
                                        <td>
                                            {{ $invoice->customer->mobile ?? '' }}
                                        </td>

                                        <td>
                                            {{ $invoice->payment->method_name ?? '' }}
                                        </td>

                                        <td>{{ $invoice->grand_total }}</td>
                                        <td>{{ $invoice->paid_amount }}</td>


                                        <td>
                                            {{ $invoice->user->name ?? '' }}
                                        </td>


                                        <td class="form-inline">

                                            <a href="{{ route('print-invoice', $invoice->id) }}" target="_blank"
                                               class="btn btn-danger btn-xs" title="Print" style="margin-right:3px"><i
                                                    class="fa fa-print" aria-hidden="true"></i></a>

                                            <a href="{{ route('sale-return.show', $invoice->id) }}"
                                               class="btn btn-success btn-xs" title="Return"
                                               style="margin-right:3px"><i class="fa fa-retweet" aria-hidden="true"></i></a>
                                            @can('sales-details')
                                                <a href="{{ route('sale.details', $invoice->id) }}"
                                                   class="btn btn-primary btn-xs" title="Details"
                                                   style="margin-right:3px"><i class="fa fa-info"
                                                                               aria-hidden="true"></i>
                                                </a>
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
            $(document).ready(function () {
                $('.data-table').DataTable({
                    order: [[0, 'desc']],
                });
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
