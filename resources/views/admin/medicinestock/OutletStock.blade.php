@extends('layouts.admin.master')

@section('title','Outlet Medicine Stock')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <div class="row">
                <div class="col-sm-8">
                    <h3>Outlet Medicine Stock</h3>
                </div>

            </div>
        @endslot

        @slot('button')
            <div class="row">

                <div class="col-md-8" style="  display: inline-block;">
                    @can('outlet_value')
                        <p style="display:inline;">
                            <span style="font-size: 1.5rem; color:red">Total Value =</span>
                            <span id="hello2" style="font-size: 1.5rem ;color:red"></span>
                            <span style="font-size: 1.5rem ;color:red">TK</span>
                        </p>
                    @endcan
                </div>

                <div class="col-md-4">
                    {{ Form::select('outlet_id', $outlet, null, ['class' => 'form-control', 'id' => 'supplier_id']) }}
                </div>
            </div>
        @endslot
    @endcomponent

    <div class="container-fluid list-products">
        <div class="row">
            <!-- Individual column searching (text inputs) Starts-->
            <div class="col-sm-12">
                <div class="card">

                    <div class="card-body">
                        <div class="table-responsive product-table">
                            <table class="display data-table" id="basic-1">
                                <thead>
                                <tr>
                                    <th>SI</th>
                                    <th>Medicine Name</th>
                                    <th>Category</th>
                                    <th>Manufacturer Name</th>
                                    <th>Expiry Date</th>
                                    <th>Manufacture Price</th>
                                    <th>Sale Price</th>
                                    <th>Stock</th>
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

        <script type="text/javascript">
            $(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                let outlet_id = $('#supplier_id').val();
                var table = $('.data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    "filter": true,
                    order: [[0, 'desc']],


                    "ajax": {
                        "url": "/get-outlet-stock/" + outlet_id,
                        "type": "GET",   // you can probably remove this
                        "datatype": 'json',


                        // you can probably remove this
                    },

                    columns: [
                        {data: 'id', name: 'si'},
                        {data: 'medicine_name', name: 'medicine Name'},
                        {data: 'category', name: 'category'},
                        {data: 'manufacturer_name', name: 'Manufacturer Name'},
                        {data: 'expiry_date', name: 'Expiry Date'},
                        {data: 'manufacturer_price', name: 'Manufacturer price'},
                        {data: 'price', name: 'sale price'},
                        {data: 'quantity', name: 'stock'},
                        {data: 'action', name: 'action'},

                    ],


                });

                $.ajax({
                    "url": "/get-outlet-stock2/" + outlet_id,
                    "type": "GET",
                    "datatype": 'json',
                    "success": function (data) {

                        let ok = '';
                        ok += Math.round(data.total);

                        document.getElementById('hello2').innerHTML = ok;
                    }
                });


                $('#supplier_id').on('change', function () {
                    outlet_id = $(this).val();
                    table.ajax.url("/get-outlet-stock/" + outlet_id).load();

                    $.ajax({
                        "url": "/get-outlet-stock2/" + outlet_id,
                        "type": "GET",
                        "datatype": 'json',
                        "success": function (data) {

                            let ok = '';
                            ok += Math.round(data.total);

                            document.getElementById('hello2').innerHTML = ok;
                        }
                    });
                });
            });

        </script>



        {{-- <script src="{{asset('assets/js/ecommerce.js')}}"></script> --}}
        {{-- <script src="{{asset('assets/js/product-list-custom.js')}}"></script> --}}
    @endpush

@endsection
