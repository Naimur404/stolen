@extends('layouts.admin.master')

@section('title')Settings
 {{ $title }}
@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
			<h3>Settings</h3>
		@endslot
		<li class="breadcrumb-item">Settings</li>

	@endcomponent

	<div class="container-fluid">
        {{-- @if (session()->get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session()->get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>

        @endif --}}
		<div class="row">
			<div class="col-sm-12">

                <div class="card">
					<div class="card-header pb-0">
						<h5>Logo & Favicon</h5>
					</div>
					<form class="form theme-form" method="POST" action="{{ route('updatesetting') }}" enctype="multipart/form-data">
                        @csrf
						<div class="card-body">
							<div class="row">
								<div class="col">
                                    <div class="mb-3 row">
										<label class="col-sm-3 col-form-label">Existing Logo</label>
										<div class="col-sm-9">
                                            <div>
                                                <img src="{{ asset('font_asset/uploads/'.$data->logo) }}" alt="" class="w_300" width="200" height="200" >
                                            </div>
										</div>
									</div>
									<div class="mb-3 row">
										<label class="col-sm-3 col-form-label">Change Logo</label>
										<div class="col-sm-9">
											<input class="form-control btn-pill" type="file"  name="logo"/>

										</div>
									</div>


								</div>
							</div>
                            <div class="row">
								<div class="col">
                                    <div class="mb-3 row">
										<label class="col-sm-3 col-form-label">Existing Favicon</label>
										<div class="col-sm-9">
                                            <div>
                                                <img src="{{ asset('font_asset/uploads/'.$data->favicon) }}" alt="" class="w_300" width="200" height="200">
                                            </div>
										</div>
									</div>
									<div class="mb-3 row">
										<label class="col-sm-3 col-form-label">Change Favicon</label>
										<div class="col-sm-9">
											<input class="form-control btn-pill" type="file" name="favicon"/>
                                            <x-input-error :messages="$errors->get('favicon')" class="mt-2" />
										</div>
									</div>
								</div>
							</div>

						</div>


				</div>
				<div class="card">
					<div class="card-header pb-0">
						<h5>Other's Setting</h5>
					</div>

						<div class="card-body">
							<div class="row">
								<div class="col">
									<div class="mb-3">
										<label class="form-label" for="exampleFormControlInput5">App Name</label>
										<input class="form-control btn-pill" id="exampleFormControlInput5" type="text" value="{{ $data->app_name }}" name="app_name" />
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<div class="mb-3">
										<label class="form-label" for="exampleInputPassword6">Phone Number</label>
										<input class="form-control btn-pill" id="exampleInputPassword6" type="phone" value="{{ $data->phone_no }}" name="phone_no"/>

									</div>

								</div>
							</div>
                            <div class="row">
								<div class="col">
									<div class="mb-3">
										<label class="form-label" for="exampleInputPassword6">Address</label>
										<input class="form-control btn-pill" id="exampleInputPassword6" type="text" value="{{ $data->address }}" name="address" />
									</div>
								</div>
							</div>

                            <div class="row">
								<div class="col">
									<div class="mb-3">
										<label class="form-label" for="exampleInputPassword6">Website</label>
										<input class="form-control btn-pill" id="exampleInputPassword6" type="text" value="{{ $data->website }}" name="website" />
                                        <x-input-error :messages="$errors->get('website')" class="mt-2" />
									</div>
								</div>
							</div>


							<div class="row">
								<div class="col">
									<div>
										<label class="form-label" for="exampleFormControlTextarea9">Footer Text</label>
										<textarea class="form-control btn-pill" id="exampleFormControlTextarea9" rows="2" name="footer_text">{{ $data->footer_text }}</textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer text-end">
							<button class="btn btn-primary" type="submit">Update</button>

						</div>
					</form>
				</div>





			</div>
		</div>
	</div>


	@push('scripts')
    <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
    @if (Session()->get('success'))

    <script>
    $.notify('<i class="fa fa-bell-o"></i><strong>Update Sucessfully</strong>', {
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
