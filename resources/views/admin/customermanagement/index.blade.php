@extends('layouts.admin.master')

@section('title')Customer Management

@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-8">
			<h3>Customer Management</h3>
        </div>

        </div>
		@endslot

        @slot('button')
<div class="row">
    <div class="col-md-8">
        <a href="{{route('customer.create')}}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Add Customer</a>
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
	                                    <th>Name</th>
                                        <th>Mobile</th>

                                        <th>Outlet Name</th>
                                        <th>Points</th>
                                        <th>Due</th>
                                        <th>Active</th>
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
    let outlet_id = $('#supplier_id').val();
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        "filter": true,

        ajax: "/get-customer/" + outlet_id,
        columns: [
            {data: 'id', name: 'si'},
            {data: 'name', name: 'Name'},
            {data: 'mobile', name: 'mobile'},
            {data: 'outlet_name', name: 'outlet name'},
            {data: 'points', name: 'Points'},
            {data: 'due', name: 'due'},
            {data: 'is_active', name: 'Is Active'},
            {data: 'action', name: 'action'},

        ]
    });
    $('#supplier_id').on('change', function(){
    outlet_id = $(this).val();
    table.ajax.url("/get-customer/" + outlet_id).load();
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
