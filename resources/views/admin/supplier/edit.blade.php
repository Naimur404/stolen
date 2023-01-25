@extends('layouts.admin.master')



@section('title','Edit Supplier')

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/date-picker.css')}}">
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-5">
			<h3>Edit Supplier</h3>
            @if ($errors->any())

            @foreach ($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                {{ $error }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endforeach

@endif
        </div>

        </div>

		@endslot

        @slot('button')
        <a href="{{ route('supplier.index') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">back</a>
        @endslot

	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header pb-0">
						<h5>Edit Supplier</h5>
                        @if ($errors->any())

                                @foreach ($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                                    {{ $error }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  </div>
                                @endforeach

                    @endif
					</div>

                        {!! Form::model($supplier,['route'=>['supplier.update',$supplier->id], 'method'=>'PUT', 'role' => 'form','class' => 'form theme-form']) !!}

                        @include('admin.supplier.fields')

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

           $(document).ready(function() {
            $("#manufacturer_id").select2();
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
