@extends('layouts.admin.master')
@section('title')Add User In Outlet
@endsection
@push('css')
@endpush
@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
			<h3>Add User In Outlet</h3>
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
					<div class="card-header pb-0">
						<h5>Add User In Outlet</h5>
					</div>
                    {!! Form::open(['route'=>'storeuser', 'method'=>'POST', 'role' => 'form', 'class' => 'form theme-form']) !!}

                      {!! Form::hidden('id',$id) !!}
						<div class="card-body">

              <div class="row">
			<div class="col">
              <div class="col-sm-12 pb-0 mt-2">

                </div>

                {{-- {{App\Models\HealthOrganization::userHasOrganization($role,$data,$id)  }} --}}
                @foreach ($data as $datas)


                @php

                $bol =    (App\Models\Outlet::userHasoutlet($outlet,$datas,$id)) ? true : false;
                @endphp


              <div class="checkbox checkbox-primary">
                {!! Form::checkbox('user[]',$datas->id,$bol,['id'=>'checkbox-primary-1'.$datas->id ]) !!}
                {{-- <input id="checkbox-primary-1{{ $data->id }}" type="checkbox" value="{{ $data->id }}" name="permission[]"> --}}
                {!! Form::label('checkbox-primary-1'.$datas->id,$datas->name) !!}
                {{-- <label for="checkbox-primary-1{{ $data->id }}" >{{ $data->name }}</label> --}}
                </div>
                <hr>
                @endforeach

                </div>
              </div>
						</div>
						<div class="card-footer text-end">
                            {!!  Form::submit('Add',['class'=> 'btn btn-primary']); !!}

						</div>
                        {!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>


	@push('scripts')


    <script type="text/javascript">
        // CSRF Token


$('#checkbox-primary-1-All').click(function(){
  if ($(this).is(':checked')){
    $('input[type = checkbox]').prop('checked',true);
  }else{
    $('input[type = checkbox]').prop('checked',false);
  }
})

        </script>
	@endpush

@endsection
