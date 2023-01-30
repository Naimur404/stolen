@extends('layouts.admin.master')



@section('title','Edit Manufacturer')

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/date-picker.css')}}">
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-5">
			<h3>Edit Manufacturer</h3>
        </div>

        </div>

		@endslot
		<li class="breadcrumb-item">DashBoard</li>
		<li class="breadcrumb-item">Edit Manufacturer</li>
        @slot('button')
        <a href="{{ route('manufacturer.index') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">back</a>
        @endslot

	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header pb-0">
						<h5>Edit Manufacturer</h5>
                        @if ($errors->any())

                                @foreach ($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                                    {{ $error }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>
                                @endforeach

                    @endif
					</div>

                        {!! Form::model($manufacturer, ['route'=>['manufacturer.update', $manufacturer->id], 'method'=>'PUT', 'role' => 'form','class' => 'needs-validation', 'novalidate'=> '']) !!}

                        @include('admin.manufacturer.fields')

						<div class="card-footer text-end">
                        {!!  Form::submit('Update',['class'=> 'btn btn-primary']); !!}


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
