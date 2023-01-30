@extends('layouts.admin.master')

@section('title')All Unit

@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
			<h3>All Unit</h3>
        </div>

        </div>


		@endslot
		<li class="breadcrumb-item">All Unit</li>
        @slot('button')
        @can('unit.create')
        <button id="btn-add" name="btn-add" class="btn btn-primary ">Add New Unit</button>
        @endcan
        @endslot
	@endcomponent

	<div class="container-fluid list-products">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">
	                <div class="card-header pb-0">
	                    <h5> All unit</h5>

	                </div>
	                <div class="card-body">
	                    <div class="table-responsive product-table">
	                        <table class="display data-table" id="basic-1">
	                            <thead>
	                                <tr>
	                                    <th>Unit Name</th>

	                                    <th>Action</th>

	                                </tr>
	                            </thead>
	                            <tbody>

	                            </tbody>
	                        </table>
                            <div class="modal fade" id="linkEditorModal" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">

                                            <h4 class="modal-title" id="linkEditorModalLabel">Unit</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="modalFormData" name="modalFormData" class="form-horizontal" novalidate="">

                                                <div class="form-group">
                                                    <label for="inputLink" class="col-sm-4 control-label">Unit</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="unit_name" name="unit_name"
                                                               placeholder="Enter unit" value="" required>
                                                               @error('unit_name')
                                                               <div class="invalid-feedback2"> {{ $message }}</div>

                                                           @enderror
                                                    </div>
                                                </div>


                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" id="btn-save" value="add">Submit
                                            </button>
                                            <input type="hidden" id="link_id" name="link_id" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>

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
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('unit.index') }}",
        columns: [
            {data: 'unit_name', name: 'unit_name'},

            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
$(document).ready(function($){
    ////----- Open the modal to CREATE a link -----////
    $('#btn-add').click(function () {
        $('#btn-save').val("add");
        $('#modalFormData').trigger("reset");
        $('#linkEditorModal').modal('show');
    });});



    $('body').on('click', '.open-modal', function () {
        var link_id = $(this).val();
        $.get('unit/' + link_id+ '/edit', function (data) {
            $('#link_id').val(data.id);
            $('#unit_name').val(data.unit_name);
            $('#btn-save').val("update");
            $('#linkEditorModal').modal('show');
        })
    });




    $("#btn-save").click(function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var formData = {
            unit_name: $('#unit_name').val(),

        };
        var state = $('#btn-save').val();
        var type = "POST";
        var link_id = $('#link_id').val();
        var ajaxurl = "{{ route('unit.store') }}";
        if (state == "update") {
            type = "PUT";
            ajaxurl = 'unit/' + link_id;
        }
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                if (state == "add") {
                    setTimeout(function() {
                   window.location.href = "{{ 'unit'}}";
                      }, 1000);

                    $.notify('<i class="fa fa-bell-o"></i><strong>Add successfully</strong>', {
                           type: 'theme',
                           allow_dismiss: true,
                           delay: 1000,
                           showProgressbar: true,
                            timer: 300
                            });
                } else {

                    setTimeout(function() {
                   window.location.href = "{{ 'unit'}}";
                      }, 1000);
                    $.notify('<i class="fa fa-bell-o"></i><strong>Update successfully</strong>', {
                           type: 'theme',
                           allow_dismiss: true,
                           delay: 1000,
                           showProgressbar: true,
                            timer: 300
                            });
                }
                $('#modalFormData').trigger("reset");
                $('#linkEditorModal').modal('hide')
            },
            error: function (data) {
                console.log('Error:', data);
            }
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
