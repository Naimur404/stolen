@extends('layouts.admin.master')


@section('title','Add Product')

@push('css')

@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <div class="row">
                <div class="col-sm-5">
                    <h3>Add Product</h3>
                </div>

            </div>

        @endslot

        @slot('button')
            <a href="{{ route('medicine.index') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn"
               title="">Back</a>

        @endslot

    @endcomponent


    <div class="container-fluid">


        {!! Form::open(['route'=>'medicine.index', 'method'=>'POST', 'role' => 'form','class' => 'needs-validation', 'novalidate'=> '','files' => true]) !!}
        @include('admin.medicine.fields')

        <div class="card-footer text-end">
            {{ Form::reset('Reset', ['class' => 'btn btn-warning']) }}
            {!!  Form::submit('Create',['class'=> 'btn btn-primary']); !!}
        </div>

        {{ Form::close(); }}
    </div>


    @push('scripts')
        <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
        <script type="text/javascript">


            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $(document).ready(function () {

                $("#sel_emp1").select2({
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

                $("#sel_emp2").select2({
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

                $("#sel_emp3").select2({
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

                $("#sel_emp4").select2({
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
