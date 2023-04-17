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
                    @can('purchase.report')
                    {!! Form::open(array('url'=> 'report2/purchase-report-submit', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                    <div class="row form-group">
                        <div class="col-md-2 mt-4">
                            <b>Medicine Purchase Report</b>
                        </div>
                         <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                        <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>

                        <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                    </div>
                    {!! Form::close() !!}
                    @endcan
                    @can('sale_report_details')
                    {!! Form::open(array('url'=> 'report2/sale-report-details', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                    <div class="row form-group">
                        <div class="col-md-2 mt-4">
                            <b>Sales Report Details</b>
                        </div>
                        <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                        <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>

                        <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                    </div>
                    {!! Form::close() !!}
                    @endcan
                    @can('sale_return_report')

                    {!! Form::open(array('url'=> 'report2/sale-return-report', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                    <div class="row form-group">
                        <div class="col-md-2 mt-4">
                            <b>Sales Return Report</b>
                        </div>
                         <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                        <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>


                        <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                    </div>
                    {!! Form::close() !!}
                    @endcan


                       @can('category-wise-report-outlet')

                    {!! Form::open(array('url'=> 'report2/category-wise-report', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                    <div class="row form-group">
                        <div class="col-md-2 mt-4">
                            <b>Category Wise Stock Report</b>
                        </div>
                        <div class="col-md-2">Outlet Name {{ Form::select('outlet_id', $outlet, null, ['class' => 'form-control', 'placeholder' => 'Select Outlet', 'id' => 'outlet']) }}
                       </div>

                        <div class="col-md-2">Category {{ Form::select('category_id', $category, null, ['class' => 'form-control', 'placeholder' => 'Select Category', 'id' => '' ,'required']) }}
                       </div>
                        <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                    </div>
                    {!! Form::close() !!}
                    @endcan

                    @can('category-wise-report-warehouse')


                    {!! Form::open(array('url'=> 'report2/category-wise-report', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                    <div class="row form-group">
                        <div class="col-md-2 mt-4">
                            <b>Category Wise Stock Report</b>
                        </div>
                        <div class="col-md-2">Warehouse Name {{ Form::select('warehouse_id', $warehouse, null, ['class' => 'form-control', 'placeholder' => 'Select Warehouse', 'id' => 'outlet']) }}
                       </div>

                        <div class="col-md-2">Category {{ Form::select('category_id', $category, null, ['class' => 'form-control', 'placeholder' => 'Select Category', 'id' => '' ,'required']) }}
                       </div>
                        <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                    </div>
                    {!! Form::close() !!}
                    @endcan






                    @can('warehouse-stock.report')
                    {!! Form::open(array('url'=> 'report2/warehouse-stock-submit', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                    <div class="row form-group">
                        <div class="col-md-2 mt-4">
                            <b>Warehouse Stock Report</b>
                        </div>
                         <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                        <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
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
                         <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                        <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
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
                         <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                        <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                        <div class="col-md-3">Customer  {{ Form::select('customer_id', [], null, ['class' => 'form-control add', 'placeholder' => 'All Customer', 'id' => 'user_id']) }}
                            </div>
                        <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                    </div>
                    {!! Form::close() !!}
                    @endcan

@can('sale_report_by_user')
                    {!! Form::open(array('url'=> 'report2/sale-report-user', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                    <div class="row form-group">
                        <div class="col-md-2 mt-4">
                            <b>Sales Report By User</b>
                        </div>
                         <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                        <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                        <div class="col-md-3">User Name  {{ Form::select('user_id', $Users, null, ['class' => 'form-control add', 'id' => 'user_id2']) }}
                        </div>

                        <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                    </div>
                    {!! Form::close() !!}

@endcan

@can('sale_report_by_payment')


                        {!! Form::open(array('url'=> 'report2/sale-report-payment', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                    <div class="row form-group">
                        <div class="col-md-2 mt-4">
                            <b>Sales Report By Payment Method</b>
                        </div>
                         <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                        <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                        <div class="col-md-3">Payment Method  {{ Form::select('payment_id', $payment_method, null, ['class' => 'form-control add', 'id' => 'user_id2']) }}
                        </div>

                        <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                    </div>
                    {!! Form::close() !!}
                    @endcan
                    @can('stock_request_report_for_warehouse')


                    {!! Form::open(array('url'=> 'report2/stock-request-report2', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                    <div class="row form-group">
                        <div class="col-md-2 mt-4">
                            <b>Stock Request Report</b>
                        </div>
                         <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                        <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>

                        <div class="col-md-3">Medicine Name {{ Form::select('medicine_id', [], null, ['class' => 'form-control', 'placeholder' => 'Select medicine', 'id' => 'medicine_id25']) }}
                       </div>
                        <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                    </div>
                    {!! Form::close() !!}

                    @endcan
                    @can('profit_loss')
                    {!! Form::open(array('url'=> 'report2/profit-loss-report', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                    <div class="row form-group">
                        <div class="col-md-2 mt-4">
                            <b>Profit & Loss Report</b>
                        </div>
                         <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                        <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>


                        <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                    </div>
                    {!! Form::close() !!}

@endcan


@can('expiry-wise-report-warehouse')


{!! Form::open(array('url'=> 'report2/expiry-wise-report', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
<div class="row form-group">
    <div class="col-md-2 mt-4">
        <b>Expiry Wise Report</b>
    </div>
     <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>

    <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
</div>
{!! Form::close() !!}

@endcan

@can('expiry-wise-report-outlet')
{!! Form::open(array('url'=> 'report2/expiry-wise-report1', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
<div class="row form-group">
    <div class="col-md-2 mt-4">
        <b>Expiry Wise Report</b>
    </div>
     <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>

    <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
</div>
{!! Form::close() !!}

@endcan


{!! Form::open(array('url'=> 'report2/best-selling', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
<div class="row form-group">
    <div class="col-md-2 mt-4">
        <b>Fast/Best selling product</b>
    </div>
     <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
     <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
    <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
</div>
{!! Form::close() !!}



@can('best_selling')

{!! Form::open(array('url'=> 'report2/slow-selling', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
<div class="row form-group">
    <div class="col-md-2 mt-4">
        <b>Slow selling product</b>
    </div>
     <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
     <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
    <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
</div>
{!! Form::close() !!}

@endcan

@can('slow_selling')

{!! Form::open(array('url'=> 'report2/expiry-wise-report1', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
<div class="row form-group">
    <div class="col-md-2 mt-4">
        <b>Expiry Wise Report</b>
    </div>
     <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>

    <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
</div>
{!! Form::close() !!}
@endcan











                    @can('distribute_medicine_report_for_warehouse')

                    {{-- for warehouse manager --}}
                                         {!! Form::open(array('url'=> 'report2/distribute-medicine-report2', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                                         <div class="row form-group">
                                             <div class="col-md-2 mt-4">
                                                 <b>Distribute Medicine Report</b>
                                             </div>
                                             <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                        <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>

                                             <div class="col-md-3">Medicine Name {{ Form::select('medicine_id', [], null, ['class' => 'form-control', 'placeholder' => 'Select medicine', 'id' => 'medicine_id24']) }}
                                            </div>
                                             <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                                         </div>
                                         {!! Form::close() !!}

                      @endcan

                    @can('distribute_medicine_report_for_outlet')


                     {!! Form::open(array('url'=> 'report2/distribute-medicine-report', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                     <div class="row form-group">
                         <div class="col-md-2 mt-4">
                             <b>Distribute Medicine Report</b>
                         </div>
                         <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                         <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                         <div class="col-md-2">Outlet Name {{ Form::select('outlet_id', $outlet, null, ['class' => 'form-control', 'placeholder' => 'Select Outlet', 'id' => 'outlet']) }}
                        </div>
                         <div class="col-md-2">Medicine Name {{ Form::select('medicine_id', [], null, ['class' => 'form-control', 'placeholder' => 'Select medicine', 'id' => 'medicine_id23']) }}
                        </div>
                         <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                     </div>
                     {!! Form::close() !!}
                     @endcan


  @can('stock_request_report_for_outlet')

                     {!! Form::open(array('url'=> 'report2/stock-request-report', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                     <div class="row form-group">
                         <div class="col-md-2 mt-4">
                             <b>Stock Request Report</b>
                         </div>
                         <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                         <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                         <div class="col-md-2">Outlet Name {{ Form::select('outlet_id', $outlet, null, ['class' => 'form-control', 'placeholder' => 'Select Outlet', 'id' => 'outlet']) }}
                        </div>
                         <div class="col-md-2">Medicine Name {{ Form::select('medicine_id', [], null, ['class' => 'form-control', 'placeholder' => 'Select medicine', 'id' => 'medicine_id22']) }}
                        </div>
                         <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                     </div>
                     {!! Form::close() !!}
                     @endcan
{{-- for warehouse manager --}}

                     @can('return_medicine_report_for_outlet')

                     {!! Form::open(array('url'=> 'report2/return-meidicine-report', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                     <div class="row form-group">
                         <div class="col-md-2 mt-4">
                             <b>Return Medicine Report</b>
                         </div>
                         <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                         <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                         <div class="col-md-2">Outlet Name {{ Form::select('outlet_id', $outlet, null, ['class' => 'form-control', 'placeholder' => 'Select Outlet', 'id' => 'outlet']) }}
                        </div>
                         <div class="col-md-2">Medicine Name {{ Form::select('medicine_id', [], null, ['class' => 'form-control', 'placeholder' => 'Select medicine', 'id' => 'medicine_id26']) }}
                        </div>
                         <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                     </div>
                     {!! Form::close() !!}
                     @endcan
                     {{-- for warehouse --}}
                     @can('return_medicine_report_for_warehouse')
                     {!! Form::open(array('url'=> 'report2/return-meidicine-report2', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                     <div class="row form-group">
                         <div class="col-md-2 mt-4">
                             <b>Return Medicine Report</b>
                         </div>
                         <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                         <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>

                         <div class="col-md-2">Medicine Name {{ Form::select('medicine_id', [], null, ['class' => 'form-control', 'placeholder' => 'Select medicine', 'id' => 'medicine_id27']) }}
                        </div>
                         <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                     </div>
                     {!! Form::close() !!}
                     @endcan
                     @can('supplier_wise_sale_report')
                     {!! Form::open(array('url'=> 'report2/supplier-wise-report', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}

                                          <div class="row form-group">
                                             <div class="col-md-2 mt-4">
                                                 <b>Supplier Wise Sale Report</b>
                                             </div>
                                             <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                                             <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>

                                             <div class="col-md-2">Supplier Name {{ Form::select('supplier_id', $supplier, null, ['class' => 'form-control', 'id' => 'supplier']) }}
                                             </div>
                                             <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                                         </div>
                                         {!! Form::close() !!}
                                         @endcan
                                    @can('supplier_wise_stock_report_outlet')

                                         {!! Form::open(array('url'=> 'report2/supplier-stock-report-outlet', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                                         <div class="row form-group">
                                            <div class="col-md-2 mt-4">
                                                <b>Supplier Wise Stock Report</b>
                                            </div>
                                            <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                                             <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                                             <div class="col-md-2">Outlet Name {{ Form::select('outlet_id', $outlet, null, ['class' => 'form-control', 'placeholder' => 'Select Outlet', 'id' => 'outlet','required']) }}
                                            </div>
                                            <div class="col-md-2">Supplier Name {{ Form::select('supplier_id', $supplier, null, ['class' => 'form-control', 'id' => 'supplier']) }}
                                            </div>
                                            <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                                        </div>
                                        {!! Form::close() !!}
                                        @endcan

                                        @can('supplier_wise_stock_report_warehouse')
                                        {!! Form::open(array('url'=> 'report2/supplier-stock-report-warehouse', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                                        <div class="row form-group">
                                            <div class="col-md-2 mt-4">
                                                <b>Supplier Wise Stock Report</b>
                                            </div>
                                            <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                                             <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                                             <div class="col-md-2">Warehouse Name {{ Form::select('warehouse_id', $warehouse, null, ['class' => 'form-control', 'placeholder' => 'Select Warehouse', 'id' => 'outlet','required']) }}
                                            </div>
                                            <div class="col-md-2">Supplier Name {{ Form::select('supplier_id', $supplier, null, ['class' => 'form-control', 'id' => 'supplier']) }}
                                            </div>
                                            <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                                        </div>
                                        {!! Form::close() !!}
                                        @endcan



                                        @can('manufacturer_wise_stock_report_outlet')

                                        {!! Form::open(array('url'=> 'report2/manufacturer-stock-report-outlet', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                                        <div class="row form-group">
                                           <div class="col-md-2 mt-4">
                                               <b>Manufacturer Wise Stock Report</b>
                                           </div>
                                           <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                                            <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                                            <div class="col-md-2">Outlet Name {{ Form::select('outlet_id', $outlet, null, ['class' => 'form-control', 'placeholder' => 'Select Outlet', 'id' => 'outlet','required']) }}
                                           </div>
                                           <div class="col-md-2">Manufacturer Name {{ Form::select('manufacturer_id', [], null, ['class' => 'form-control', 'placeholder' => 'Select Manufacturer', 'id' => 'manufacturer1']) }}
                                           </div>
                                           <div class="col-md-2"><br>{{ Form::submit('Search', array('class' => 'btn btn-primary')) }}</div>
                                       </div>
                                       {!! Form::close() !!}
                                       @endcan

                                       @can('manufacturer_wise_stock_report_warehouse')
                                       {!! Form::open(array('url'=> 'report2/manufacturer-stock-report-warehouse', 'method' => 'POST', 'class'=>'form-horizontal', 'target' => '_blank')) !!}
                                       <div class="row form-group">
                                           <div class="col-md-2 mt-4">
                                               <b>Manufacturer Wise Stock Report</b>
                                           </div>
                                           <div class="col-md-2">Start Date <strong class="text-danger">*</strong> {{ Form::text('start_date', null, ['class'=>'datepicker-here form-control digits', 'required','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                                            <div class="col-md-2">End Date {{ Form::text('end_date', \Carbon\Carbon::today()->format('d-m-Y'), ['class'=>'datepicker-here form-control digits','data-language'=>'en','placeholder'=>'dd-mm-yyyy']) }}</div>
                                            <div class="col-md-2">Warehouse Name {{ Form::select('warehouse_id', $warehouse, null, ['class' => 'form-control', 'placeholder' => 'Select Warehouse', 'id' => 'outlet','required']) }}
                                           </div>
                                           <div class="col-md-2">Manufacturer Name {{ Form::select('manufacturer_id', [], null, ['class' => 'form-control', 'placeholder' => 'Select Manufacturer', 'id' => 'manufacturer2']) }}
                                           </div>
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




$( "#manufacturer1" ).select2({

ajax: {
  url: "{{route('get-manufacturer')}}",
  type: "post",
  dataType: 'json',

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



$( "#manufacturer2" ).select2({

ajax: {
  url: "{{route('get-manufacturer')}}",
  type: "post",
  dataType: 'json',

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


                    $("#medicine_id22").select2({
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


                    $("#medicine_id24").select2({
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
                    $("#medicine_id25").select2({
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
                    $("#medicine_id23").select2({
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
                    $("#medicine_id26").select2({
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

                    $("#medicine_id27").select2({
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
