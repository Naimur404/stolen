@extends('layouts.admin.master')

@section('title')Edit Customer
 {{ $title }}
@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
			<h3>Edit Customer</h3>
		@endslot
        @slot('button')


             <a href="{{route('get-customer',$id = 'all')}}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Back</a>

             @endslot

	@endcomponent

	<div class="container-fluid">
	    <div class="edit-profile">
	        <div class="row">
                <div class="col-xl-2">
                </div>
	            <div class="col-xl-8">
	                <div class="card">
	                    <div class="card-header pb-0">

	                        <div class="card-options">
	                            <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
	                        </div>
	                    </div>
	                    <div class="card-body">
                            {!! Form::open(['route'=>['customer.update', $customerManagement->id], 'method'=>'PUT', 'role' => 'form', 'class' => 'needs-validation', 'novalidate'=> '']) !!}
                             {!! Form::hidden('id',$customerManagement->id) !!}
                            {{-- {!! Form::hidden('name',$Profile->name) !!} --}}


	                            <div class="mb-3">
                                    {{ Form::label('name', 'Customer Name *') }}
                                    {{ Form::text('name',$customerManagement->name, ['class' => 'form-control' ,'required', 'placeholder'=>'Enter Customer Name']) }}
                                    @error('name')
                                    <div class="invalid-feedback2"> {{ $message }}</div>
                                @enderror
	                                {{-- <label class="form-label">Email-Address</label>
	                                <input class="form-control" placeholder=""  readonly value="{{ $Profile->email }}"/> --}}
	                            </div>
	                            <div class="mb-6 mt-6">
                                    {!! Form::label('mobile', 'Mobile Number *', array('class' => 'form-label')) !!}
                                        {!! Form::number('mobile',$customerManagement->mobile,['class'=>'form-control', 'placeholder'=>'Enter Mobile Number', 'id' => 'mobile' ,'required']) !!}
                                        @error('mobile')
                                        <div class="invalid-feedback2"> {{ $message }}</div>
                                    @enderror
	                                {{-- <label class="form-label">Password</label>
	                                <input class="form-control" type="password" value="password" /> --}}
	                            </div>
                                <div class="mb-6 mt-3">
                                    {!! Form::label('address', 'Customer Address', array('class' => 'form-label')) !!}
                                    {!! Form::text('address',$customerManagement->address,['class'=>'form-control', 'placeholder'=>'Enter Customer Address', 'id' => 'address' ]) !!}
                                    @error('address')
                                    <div class="invalid-feedback2"> {{ $message }}</div>
                                @enderror
	                            </div>
	                            <div class="mb-3 mt-3">
                                    {!! Form::label('points', 'Customer Points', array('class' => 'form-label')) !!}
                                    {!! Form::number('points',$customerManagement->points,['class'=>'form-control', 'placeholder'=>'Enter Customer Points', 'id' => 'points' ]) !!}
	                            </div>
                                <div class="mb-3 mt-4">
                                    @if(Request::route()->named('customer.edit'))
                                    @php
                                    $datas =  App\Models\Outlet::find($customerManagement->outlet_id);

                                    @endphp
  @if (!is_null($datas))
  @php
     $data = $datas->id;
     $name = $datas->outlet_name;
  @endphp
  @endif
                                    {!! Form::label('outlet', 'Select Outlet', array('class' => 'form-label')) !!}
                                    {{ Form::select('outlet_id', [$data=>$name], null, ['class' => 'form-control', 'id' => 'supplier_id','required']) }}
                                    <div class="invalid-feedback">Please Add Outlet</div>
                                    @error('outlet_id')
                                    <div class="invalid-feedback2"> {{ $message }}</div>
                                    @enderror
                                    @else
                                    {!! Form::label('outlet', 'Select Outlet', array('class' => 'form-label')) !!}
                                    {{ Form::select('outlet_id', [$data=>$name], null, ['class' => 'form-control', 'placeholder' => 'Select Outlet', 'id' => 'supplier_id','required']) }}
                                    <div class="invalid-feedback">Please Add Outlet</div>
                                    @error('outlet_id')
                                    <div class="invalid-feedback2"> {{ $message }}</div>

                                         @enderror
                                @endif
	                            </div>
	                            <div class="form-footer">
                                    {!!  Form::submit('Submit',['class'=> 'btn btn-primary btn-block mt-4']); !!}

	                            </div>
                                {{ Form::close(); }}
	                    </div>
	                </div>
	            </div>
                <div class="col-xl-2">
                </div>

	        </div>
	    </div>
	</div>


	@push('scripts')
    <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
<script>

let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function() {


            $("#supplier_id").select2({
                ajax: {
                    url: "{!! url('get-outlet') !!}",
                    type: "get",
                    dataType: 'json',
                    //   delay: 250,
                    data: function(params) {
                        return {
                            _token: CSRF_TOKEN,
                            search: params.term,
                        };
                    },
                    processResults: function(response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }

            });

        });</script>
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
