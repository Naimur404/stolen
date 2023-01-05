@extends('layouts.admin.master')

@section('title')Role
 {{ $title }}
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-4">
			<h3>Add Role</h3>
        </div>
        <div class="col-sm-8">
            <a href="{{ route('add_role') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Add Role</a>
        </div>
        </div>
		@endslot
		<li class="breadcrumb-item">Add Role</li>

	@endcomponent

	<div class="container-fluid list-products">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-header pb-0">
	                    <h5>Role</h5>

	                </div>
	                <div class="card-body">
	                    <div class="table-responsive product-table">
	                        <table class="display data-table">
	                            <thead>
	                                <tr>
	                                    <th>Name</th>
	                                    <th>Guard</th>

	                                    <th>Action</th>

	                                </tr>
	                            </thead>
	                            <tbody>
                                    {{-- @foreach ($permissions as $permission)


	                                <tr>


	                                    <td>{{ $permission->name }}</td>
	                                    <td class="font-success">{{ $permission->guard_name }}</td>

	                                    <td>
	                                        <a class="btn btn-danger btn-xs"  data-original-title="btn btn-danger btn" title="" href="{{ route('delete_permission',$permission->id) }}">Delete</a>
	                                        <a href="{{ route('edit_permission', $permission->id) }}" class="btn btn-primary btn-xs" data-original-title="btn btn-danger btn" title="">Edit</a>
	                                    </td>
	                                </tr>
                                    @endforeach --}}





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
        ajax: "{{ route('role') }}",
        columns: [
            {data: 'name', name: 'name'},
            {data: 'guard_name', name: 'guard'},

            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
   </script>
    {{-- <script src="{{asset('assets/js/ecommerce.js')}}"></script> --}}
    {{-- <script src="{{asset('assets/js/product-list-custom.js')}}"></script> --}}
	@endpush

@endsection
