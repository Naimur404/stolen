@extends('layouts.admin.master')



@section('title','Add Medicine')

@push('css')

@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-5">
			<h3>Add Medicine</h3>
        </div>

        </div>

		@endslot

        @slot('button')
        <a href="{{ route('medicine.index') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">back</a>

        @endslot

	@endcomponent


	<div class="container-fluid">
        @if ($errors->any())

        @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
            {{ $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endforeach

@endif
{!! Form::model($medicine,['route'=>['medicine.update',$medicine->id], 'method'=>'PUT', 'role' => 'form','class' => 'form theme-form','files' => true]) !!}
@include('admin.medchine.fields')

<div class="card-footer text-end">
    {{ Form::reset('Reset', ['class' => 'btn btn-warning']) }}
    {!!  Form::submit('Update',['class'=> 'btn btn-primary']); !!}
</div>

{{ Form::close(); }}
    </div>


@push('scripts')

    <script type="text/javascript">



        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function(){

          $( "#sel_emp1" ).select2({
             ajax: {
               url: "{{route('get-manufacturer')}}",
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

          $( "#sel_emp2" ).select2({
             ajax: {
               url: "{{route('get-unit')}}",
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

          $( "#sel_emp3" ).select2({
             ajax: {
               url: "{{route('get-type')}}",
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

          $( "#sel_emp4" ).select2({
             ajax: {
               url: "{{route('get-category')}}",
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
