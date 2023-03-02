@extends('layouts.admin.master')
@section('title','All Report')
@section('content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">All Report</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                        {{-- @hasrole('Admin|Manager') --}}
                                            {{-- date wise attendance report --}}
                    {{-- {!! Form::open(array('url'=> 'date-wise-attendance', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!} --}}
                    {{-- <div class="row form-group">
                        <div class="col-md-2 mt-4">
                            <b>Date Wise Attendance Report</b>
                        </div>
                        <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::date('sdate', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'form-control', 'required']) }}</div>
                        <div class="col-md-2">End Date {{ Form::date('edate', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'form-control ']) }}</div>
                        <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                    </div> --}}
                    {{-- {!! Form::close() !!} --}}

                    {{-- attendance report form --}}
                    @can('warehouse-stock.report')
                    {!! Form::open(array('url'=> 'report2/warehouse-stock-submit', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                    <div class="row form-group">
                        <div class="col-md-2 mt-4">
                            <b>Warehouse Stock Report</b>
                        </div>
                        <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::date('start_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'form-control', 'required']) }}</div>
                        <div class="col-md-2">End Date {{ Form::date('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'form-control ']) }}</div>
                        <div class="col-md-3">Medicine Name {{ Form::select('medicine_id', [], null, ['class' => 'form-control', 'placeholder' => 'Select medicine', 'id' => 'medicine_id']) }}
                            </div>
                        <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                    </div>
                    {!! Form::close() !!}
                    @endcan
                    @can('outlet-stock.report')
                    {!! Form::open(array('url'=> 'report2/outlet-stock-submit', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                    <div class="row form-group">
                        <div class="col-md-2 mt-4">
                            <b>Outlet Stock Report</b>
                        </div>
                        <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::date('start_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'form-control', 'required']) }}</div>
                        <div class="col-md-2">End Date {{ Form::date('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'form-control ']) }}</div>
                        <div class="col-md-3">Medicine Name {{ Form::select('medicine_id', [], null, ['class' => 'form-control', 'placeholder' => 'Select medicine', 'id' => 'medicine_id2']) }}
                            </div>
                        <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                    </div>
                    {!! Form::close() !!}
                    @endcan




                    @can('sale.report')
                    {!! Form::open(array('url'=> 'report2/sale-report-submit', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                    <div class="row form-group">
                        <div class="col-md-2 mt-4">
                            <b>Medicine Sales Report</b>
                        </div>
                        <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::date('start_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'form-control', 'required']) }}</div>
                        <div class="col-md-2">End Date {{ Form::date('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'form-control ']) }}</div>
                        <div class="col-md-3">Medicine Name  {{ Form::select('customer_id', [], null, ['class' => 'form-control add', 'placeholder' => 'All Customer', 'id' => 'user_id']) }}
                            </div>
                        <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                    </div>
                    {!! Form::close() !!}
                    @endcan
                    @can('purchase.report')
                    {!! Form::open(array('url'=> 'report2/purchase-report-submit', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                    <div class="row form-group">
                        <div class="col-md-2 mt-4">
                            <b>Medicine Purchase Report</b>
                        </div>
                        <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::date('start_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'form-control', 'required']) }}</div>
                        <div class="col-md-2">End Date {{ Form::date('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'form-control ']) }}</div>

                        <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                    </div>
                    {!! Form::close() !!}
                    @endcan
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script type="text/javascript">

        $(document).ready(function() {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $("#medicine_id").select2({
                        ajax: {
                            url: "{!! url('get-all-medicine') !!}",
                            type: "get",
                            dataType: 'json',
                            //   delay: 250,
                            data: function(params) {
                                return {
                                    _token: CSRF_TOKEN,
                                    search: params.term, // search term
                                    // manufacturer: manufacturer_id, // search term

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

                    $("#medicine_id2").select2({
                        ajax: {
                            url: "{!! url('get-all-medicine') !!}",
                            type: "get",
                            dataType: 'json',
                            //   delay: 250,
                            data: function(params) {
                                return {
                                    _token: CSRF_TOKEN,
                                    search: params.term, // search term
                                    // manufacturer: manufacturer_id, // search term

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

                    $("#user_id").select2({
                    tags: true,
                    ajax: {
                        url: "{!! url('get-user2') !!}",
                        type: "get",
                        dataType: 'json',
                        //   delay: 250,
                        data: function (params) {
                            return {
                                _token: CSRF_TOKEN,
                                search: params.term,


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
        } );


        </script>
 @endpush


@endsection