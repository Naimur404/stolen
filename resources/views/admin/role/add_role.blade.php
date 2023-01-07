@extends('layouts.admin.master')

@section('title')Add Role
 {{ $title }}
@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-4">
			<h3>Add Role</h3>
        </div>
        <div class="col-sm-8">
            <a href="{{ route('role') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Back</a>
        </div>
        </div>
		@endslot
		<li class="breadcrumb-item">DashBoard</li>
		<li class="breadcrumb-item">Add Role</li>

	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header pb-0">
						<h5>Add Role</h5>
					</div>

                    {!! Form::open(['route'=>'store_role', 'method'=>'POST', 'role' => 'form', 'class' => 'form theme-form']) !!}
                    {!! Form::token(); !!}

						<div class="card-body">
                            <div class="row">

								<div class="col">
									<div class="mb-3">
                                        {!! Form::label('exampleFormControlInput1', 'Role', array('class' => 'form-label')) !!}
                                        {!! Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Role', 'id' => 'exampleFormControlInput1' ]) !!}

									</div>
								</div>
							</div>

						</div>
						<div class="card-footer text-end">
                            {!!  Form::submit('Submit',['class'=> 'btn btn-primary']); !!}

						</div>
					{!! Form::close() !!}
				</div>

			</div>
		</div>
	</div>

	@push('scripts')
	@endpush

@endsection
