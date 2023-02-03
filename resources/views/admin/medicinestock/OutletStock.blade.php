@extends('layouts.admin.master')

@section('title')Outlet Medicine Stock

@endsection

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
    <div class="col-md-8">
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
                                        <th>Unit</th>
                                        <th>Manufacture Price</th>
                                        <th>Sale Price</th>

                                        <th>Stock</th>




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
	<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>

    <script type="text/javascript">
    $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    let outlet_id = $('#supplier_id').val();
    var table = $('.data-table').DataTable({
        responsive: true,
        
        processing: true,
        serverSide: true,
        ajax: "/get-outlet-stock/" + outlet_id,
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'medicine_name', name: 'medicine Name'},
            {data: 'category', name: 'category'},
            {data: 'manufacturer_name', name: 'Manufacturer Name'},
            {data: 'expiry_date', name: 'Expiry Date'},
            {data: 'unit', name: 'unit'},
            {data: 'manufacturer_price', name: 'Manufacturer price'},
            {data: 'sale_price', name: 'sale price'},
            {data: 'quantity', name: 'stock'},

        ]
    });
    $('#supplier_id').on('change', function(){
    outlet_id = $(this).val();
    table.ajax.url("/get-outlet-stock/" + outlet_id).load();
});
});

   </script>
{{-- <script>

  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function(){

          $( "#supplier_id" ).select2({
             ajax: {
               url: "{{route('get-outlet')}}",
               type: "GET",
               dataType: 'json',
               delay: 250,
               data: function (params) {
                 return {
                    _token: CSRF_TOKEN,
                    search: params.term // search term
                 };
               },
               processResults: function (response) {
                 return {
                   results: response
                 };
               },
               cache: true
             }

          });







        });


</script> --}}

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
