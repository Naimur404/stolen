@extends('layouts.admin.master')

@section('title',' All Medicine')

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
			<h3>All Medicine</h3>
        </div>

        </div>
		@endslot

        @slot('button')
        <a href="{{ route('medicine.create') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title=""> Add Medicine</a>
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
	                                    <th>Medicine Name</th>
                                        <th>Generic Name</th>
                                        <th>Manufacture Name</th>
                                        <th>Category</th>
                                        <th>Price</th>

                                        <th>Image</th>



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

        ajax: "{{ route('medicine.index') }}",
        columns: [
            {data: 'medicine_name', name: 'medicine name',orderable: false},
            {data: 'generic_name', name: 'generic name'},
            {data: 'manufacturer', name: 'manufacture name'},
            {data: 'category', name: 'category'},
            {data: 'price', name: 'price'},
            {data: 'image', name: 'image', orderable: false, searchable: false},


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
