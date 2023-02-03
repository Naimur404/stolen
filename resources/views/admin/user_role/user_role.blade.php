@extends('layouts.admin.master')

@section('title')User

@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
			<h3>All User</h3>
        </div>

        </div>
		@endslot
		<li class="breadcrumb-item">User</li>
        @slot('button')
        <a href="{{ route('add_user') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Add User</a>
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
	                                    <th>Name</th>
	                                    <th>Email</th>
                                        <th>Type</th>

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
        ajax: "{{ route('user') }}",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'type', name: 'type', orderable: false, searchable: false},

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
