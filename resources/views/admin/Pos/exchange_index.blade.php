@extends('layouts.admin.master')

@section('title')All Exchange

@endsection

@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <div class="row">
                <div class="col-sm-6">
                    <h3>All Exchange</h3>
                </div>

            </div>


        @endslot

        @slot('button')

                <a id="btn-add" href="{{route('exchanges.create')}}" class="btn btn-primary ">Exchange</a>

        @endslot
    @endcomponent

    <div class="container-fluid list-products">
        <div class="row">
            <!-- Individual column searching (text inputs) Starts-->
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display data-table center" id="basic-1">
                                <thead>
                                <tr>
                                    <th>SI</th>
                                    <th>Exchange Id</th>
                                    <th>Invoice Id</th>
                                    <th>Grand Total</th>
                                    <th>Customer</th>
                                    <th>Date</th>

                                    <th>Action</th>

                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>

                    </div>
                </div>
            </div>

            <!-- Individual column searching (text inputs) Ends-->
        </div>
    </div>
    </div>


    @push('scripts')
        <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
        <script type="text/javascript">
            $(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('exchange.index') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'id', name: 'exchange_id'},
                        {data: 'original_invoice_id', name: 'invoice_id'},
                        {data: 'grand_total', name: 'grand_total'},
                        {data: 'customer.name', name: 'customer'},
                        {
                            data: 'created_at',
                            name: 'date',
                            render: function (data, type, row) {
                                // Format the date using native JavaScript
                                return new Date(data).toLocaleDateString(); // Example using native JavaScript
                            }
                        },

                        {data: 'action', name: 'action', orderable: false, searchable: false, className: "uniqueClassName"},
                    ],

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
