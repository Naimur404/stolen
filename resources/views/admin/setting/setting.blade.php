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

		<div class="row">
			<div class="col-sm-12">

                <div class="card">
					<div class="card-header pb-0">
						<h5>Logo & Favicon</h5>
					</div>
                    {!! Form::open(['route'=>'updatesetting', 'method'=>'POST', 'files' => true, 'role' => 'form']) !!}
                      {!! Form::token(); !!}

						<div class="card-body">
							<div class="row">
								<div class="col">
                                    <div class="mb-3 row">
                                        {!! Form::label('logo', 'Existing Logo', array('class' => 'col-sm-3 col-form-label')) !!}
										{{-- <label class="col-sm-3 col-form-label">Existing Logo</label> --}}
										<div class="col-sm-9">
                                            <div>
                                                <img src="{{ asset('uploads/'.$data->logo) }}" alt="" class="w_300" width="200" height="200" >
                                            </div>
										</div>
									</div>
									<div class="mb-3 row">
										<label class="col-sm-3 col-form-label">Change Logo</label>
										<div class="col-sm-9">
                                            {!! Form::file('logo',['class'=>'form-control btn-pill' ]) !!}
											{{-- <input class="form-control btn-pill" type="file"  name="logo"/> --}}
                                            @error('logo')
                                            <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                                                {{ $message }}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                              </div>
                                            @enderror

										</div>
									</div>


								</div>
							</div>
                            <div class="row">
								<div class="col">
                                    <div class="mb-3 row">
                                        {!! Form::label('favicon', 'Existing Favicon', array('class' => 'col-sm-3 col-form-label')) !!}

										<div class="col-sm-9">
                                            <div>
                                                <img src="{{ asset('uploads/'.$data->favicon) }}" alt="" class="w_300" width="200" height="200">
                                            </div>
										</div>
									</div>
									<div class="mb-3 row">
										<label class="col-sm-3 col-form-label">Change Favicon</label>
										<div class="col-sm-9">
                                            {!! Form::file('favicon',['class'=>'form-control btn-pill' ]) !!}

                                            @error('favicon')
                                            <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                                                {{ $message }}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                              </div>
                                            @enderror

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
{{--
										<input class="form-control btn-pill" id="exampleFormControlInput5" type="text" value="{{ $data->app_name }}" name="app_name" /> --}}
                                        {!! Form::label('app_name', 'App Name:', array('class' => 'form-label','for' => 'exampleFormControlInput5')) !!}
                                        {!! Form::text('app_name',$data->app_name,['class'=>'form-control btn-pill', 'placeholder'=>'App Name', 'id' => 'exampleFormControlInput5' ]) !!}
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<div class="mb-3">

                                        {!! Form::label('phone_no', 'Phone Number:', array('class' => 'form-label','for' => 'exampleInputPassword6')) !!}
                                        {!! Form::text('phone_no',$data->phone_no,['class'=>'form-control btn-pill', 'placeholder'=>'Phone Number', 'id' => 'exampleInputPassword6' ]) !!}

                                        <x-input-error :messages="$errors->get('phone_no')" class="mt-2" />
									</div>

								</div>
							</div>
                            <div class="row">
								<div class="col">
									<div class="mb-3">

                                        {!! Form::label('address', 'Address:', array('class' => 'form-label','for' => 'exampleInputPassword6')) !!}
                                        {!! Form::text('address',$data->address,['class'=>'form-control btn-pill', 'placeholder'=>'Address', 'id' => 'exampleInputPassword6' ]) !!}
									</div>
								</div>
							</div>

                            <div class="row">
								<div class="col">
									<div class="mb-3">

                                        {!! Form::label('website', 'Website:', array('class' => 'form-label','for' => 'exampleInputPassword6')) !!}
                                        {!! Form::text('website',$data->website,['class'=>'form-control btn-pill', 'placeholder'=>'Website', 'id' => 'exampleInputPassword6' ]) !!}
                                        <x-input-error :messages="$errors->get('website')" class="mt-2" />
									</div>
								</div>
							</div>


							<div class="row">
								<div class="col">
									<div>

                                        {!! Form::label('footer_text', 'Footer Text:', array('class' => 'form-label','for' => 'exampleInputPassword6')) !!}
                                        {!! Form::textarea('footer_text',$data->footer_text,['class'=>'form-control btn-pill', 'placeholder'=>'Website', 'id' => 'exampleInputPassword6', 'rows' =>'2' ]) !!}
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer text-end">
                            {!!  Form::submit('Update',['class'=> 'btn btn-primary']); !!}
							

						</div>
                        {!! Form::close() !!}
				</div>

			</div>
		</div>
	</div>

	@push('scripts')
    <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
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

	@endpush


@endsection
