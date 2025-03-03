@extends('layouts.admin.master')

@section('title',' All Outlet')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <div class="row">
                <div class="col-sm-6">
                    <h3>All Outlet</h3>
                </div>

            </div>
        @endslot

        @slot('button')
            <a href="{{ route('outlet.create') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn"
               title=""> Add Outlet</a>
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
                                    <th>SL</th>
                                    <th>Outlet Name</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Is Active</th>
                                    <th>Courier Gateway</th>
                                    <th>Action</th>
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
                    order: [[0, 'desc']],

                    ajax: "{{ route('outlet.index') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'outlet_name', name: 'outlet name', orderable: false},
                        {data: 'mobile', name: 'phone'},
                        {data: 'address', name: 'address'},
                        {data: 'active', name: 'is active', orderable: false, searchable: false},
                        {data: 'is_active_courier_gateway', name: 'is_active_courier_gateway', orderable: false, searchable: false, 
            render: function(data, type, row) {
                return data == 1 
                    ? '<span class="badge bg-success">Active</span>' 
                    : '<span class="badge bg-danger">Inactive</span>';
            }},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
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
