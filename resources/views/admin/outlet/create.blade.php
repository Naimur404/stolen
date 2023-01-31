@extends('layouts.admin.master')



@section('title','Add Outlet')

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/date-picker.css')}}">
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
			<h3>Add Outlet</h3>
        </div>

        </div>

		@endslot

        @slot('button')
        <a href="{{ route('outlet.index') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Back</a>
        @endslot

	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">


                        {!! Form::open(['route'=>'outlet.store', 'method'=>'POST', 'role' => 'form','class' => 'needs-validation', 'novalidate'=> '']) !!}

                        @include('admin.outlet.fields')

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
        // CSRF Token

        </script>

	@endpush

@endsection
