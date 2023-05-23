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
                        <div class="table-responsive">
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

        $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ajax: "/ajax-invoice",
        order: [[0, 'desc']],
        columns: [
            {data: 'id', name: 'id'},
            {data: 'sale_date', name: 'Sale Date'},
            {data: 'outlet_name', name: 'Outlet Name'},
            {data: 'mobile', name: 'customer'},
            {data: 'payment_method_id', name: 'Method'},
            {data: 'grand_total', name: 'total'},
            {data: 'paid_amount', name: 'pay'},
            {data: 'sold_by', name: 'Sold By'},
            {data: 'action', name: 'action'},

        ]
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
