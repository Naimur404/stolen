@extends('layouts.admin.master')



@section('title','Add Supplier')

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/date-picker.css')}}">
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
			<h3>Add Supplier</h3>
        </div>

        </div>

		@endslot

        @slot('button')
        <a href="{{ route('supplier.index') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Back</a>
        @endslot

	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">


                        {!! Form::open(['route'=>'supplier.store', 'method'=>'POST', 'role' => 'form','class' => 'needs-validation', 'novalidate'=> '']) !!}

                        @include('admin.supplier.fields')

						<div class="card-footer text-end">
                        {!!  Form::submit('Create',['class'=> 'btn btn-primary']); !!}


						</div>
					{{ Form::close(); }}
				</div>

			</div>
		</div>
	</div>


	@push('scripts')
    <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>

    <script type="text/javascript">

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function(){

          $( "#sel_emp2" ).select2({
             ajax: {
               url: "{{route('get-manufacturer')}}",
               type: "post",
               dataType: 'json',

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

	@endpush

@endsection
