@extends('layouts.admin.master')



@section('title','Edit Outlet')

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/date-picker.css')}}">
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-5">
			<h3>Edit Outlet</h3>
        </div>

        </div>

		@endslot

        @slot('button')
        <a href="{{ route('outlet.index') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">back</a>
        @endslot

	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header pb-0">
						<h5>Edit Outlet</h5>
                        @if ($errors->any())

                                @foreach ($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                                    {{ $error }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>
                                @endforeach

                    @endif
					</div>

                        {!! Form::model($outlet, ['route'=>['outlet.update', $outlet->id], 'method'=>'PUT', 'role' => 'form','class' => 'form theme-form']) !!}

                        @include('admin.outlet.fields')

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
