@extends('layouts.admin.master')

@section('title')Edit Role
 {{ $title }}
@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-4">
			<h3>Edit Role</h3>
        </div>
        <div class="col-sm-8">
            <a href="{{ route('permission') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Back</a>
        </div>
        </div>
		@endslot
		<li class="breadcrumb-item">DashBoard</li>
		<li class="breadcrumb-item">Edit Role</li>

	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header pb-0">
						<h5>Edit Role</h5>
					</div>
					<form class="form theme-form" method="POST" action="{{ route('update_role') }}">
                        @csrf
                        <input type="hidden" value="{{ $role->id }}" name="id"/>
						<div class="card-body">
                            <div class="row">

								<div class="col">
									<div class="mb-3">
										<label class="form-label" for="exampleFormControlInput1">Name</label>
										<input class="form-control" id="exampleFormControlInput1" type="text"  name="name"  value="{{ $role->name }}"/>
									</div>
								</div>
							</div>
							<div class="row">

								<div class="col">
									<div class="mb-3">
										<label class="form-label" for="exampleFormControlInput1">Guard Name</label>
										<input class="form-control" id="exampleFormControlInput1" type="text" placeholder="Web"  name="guard_name" value="{{ $role->guard_name }}"/>
									</div>
								</div>
							</div>




						</div>
						<div class="card-footer text-end">
							<button class="btn btn-primary" type="submit">Save Changes</button>

						</div>
					</form>
				</div>







			</div>
		</div>
	</div>


	@push('scripts')
	@endpush

@endsection
