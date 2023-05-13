@extends('layouts.admin.master')

@section('title','Warehouse Medicine Stock')



@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-8">
			<h3>Warehouse Medicine Stock</h3>
        </div>

        </div>
		@endslot

        @slot('button')
<div class="row">
    <div class="col-md-8">
        @can('warehouse_value')
        <p style="display:inline;">
            <span style="font-size: 1.5rem; color:red">Total Value =</span>
            <span id="hello2" style="font-size: 1.5rem ;color:red" ></span>
            <span style="font-size: 1.5rem ;color:red" >TK</span>
     </p>
     @endcan
    </div>
    <div class="col-md-4">
            {{ Form::select('warehouse_id', $warehouse, null, ['class' => 'form-control', 'id' => 'supplier_id']) }}
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
    let warehouse_id = $('#supplier_id').val();
    var table = $('.data-table').DataTable({
        responsive: true,
        autoWidth: false,
        processing: true,
        serverSide: true,
        order: [[0, 'desc']],
        ajax: "/get-warehouse-stock/" + warehouse_id,
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

        ]

    });

    $.ajax({
    "url": "/get-warehouse-stock2/" + warehouse_id,
    "type": "GET",
    "datatype": 'json',
    "success": function (data) {

        let ok = '';
        ok +=  data.total;
        document.getElementById('hello2').innerHTML = ok;
    }
});
    $('#supplier_id').on('change', function(){
    warehouse_id = $(this).val();
    table.ajax.url("/get-warehouse-stock/" + warehouse_id).load();

    $.ajax({
    "url": "/get-warehouse-stock2/" + warehouse_id,
    "type": "GET",
    "datatype": 'json',
    "success": function (data) {

        let ok = '';
         ok +=  data.total;

        document.getElementById('hello2').innerHTML = ok;
    }
});
});
});

   </script>

	@endpush

@endsection
