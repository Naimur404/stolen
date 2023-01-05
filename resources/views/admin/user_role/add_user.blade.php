@extends('layouts.admin.master')



@section('title')Add User
 {{ $title }}
@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-3">
			<h3>Add User</h3>
        </div>
        <div class="col-sm-9">
            <a href="{{ route('user') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">back</a>
        </div>
        </div>

		@endslot
		<li class="breadcrumb-item">DashBoard</li>
		<li class="breadcrumb-item">Add User</li>

	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header pb-0">
						<h5>Add User</h5>
					</div>
					<form class="form theme-form" method="POST" action="{{ route('add_user_store') }}">
                        @csrf
						<div class="card-body">
                            <div class="row">

								<div class="col">
									<div class="mb-3">
										<label class="form-label" for="exampleFormControlInput1">Name</label>
										<input class="form-control" id="exampleFormControlInput1" type="text" placeholder="name@example.com" name="name" />
									</div>
								</div>
							</div>
							<div class="row">

								<div class="col">
									<div class="mb-3">
										<label class="form-label" for="exampleFormControlInput1">Email address</label>
										<input class="form-control" id="exampleFormControlInput1" type="email" placeholder="name@example.com"  name="email"/>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<div class="mb-3">
										<label class="form-label" for="exampleInputPassword2">Password</label>
										<input class="form-control" id="exampleInputPassword2" type="password" placeholder="Password"  name="password"/>
									</div>
								</div>
							</div>
                            <div class="row">
								<div class="col">
									<div class="mb-3">
										<label class="form-label" for="exampleInputPassword2">Confirm Password</label>
										<input class="form-control" id="exampleInputPassword2" type="password" placeholder="Password" name="password_confirmation" />
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col">
									<div class="mb-3 mt-2">

										<select id="sel_emp" style="width: 1600px;" name="role">
                                            <option value="0">-- Select Role --</option>
                                         </select>
									</div>
								</div>
							</div>

						</div>
						<div class="card-footer text-end">
							<button class="btn btn-primary" type="submit">Submit</button>
							<input class="btn btn-light" type="reset" value="Cancel" />
						</div>
					</form>
				</div>







			</div>
		</div>
	</div>


	@push('scripts')


    <script type="text/javascript">
        // CSRF Token
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function(){

          $( "#sel_emp" ).select2({
             ajax: {
               url: "{{route('get_role')}}",
               type: "post",
               dataType: 'json',
               delay: 250,
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
	@endpush

@endsection
