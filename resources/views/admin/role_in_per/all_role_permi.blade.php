@extends('layouts.admin.master')

@section('title')All Role Permission

@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
			<h3>All Role Permission</h3>
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
	                    <div class="table-responsive">
	                        <table class="display data-table" id="basic-1">
	                            <thead>
	                                <tr>
                                        <th>SI</th>
	                                    <th>Name</th>
                                        <th>Permission</th>


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
        serverSide: true,
        ajax: "{{ route('allrolepermission') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name', name: 'name'},

            {data: 'permission', name: 'permission', orderable: false, searchable: false},

            {data: 'action', name: 'action', orderable: false, searchable: false ,className: "uniqueClassName"},
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
