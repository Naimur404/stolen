@extends('layouts.admin.master')
@isset($title)
@section('title',$title)
@endisset

@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/buttons.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/responsive.bootstrap4.min.css')}}">
@endpush
@section('content')
@component('components.breadcrumb')
@slot('breadcrumb_title')

@endslot

@slot('button')

@endslot

@endcomponent
@php
$grand_total = 0;
$total_due = 0;
$total_pay = 0;
@endphp
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="card ">
                <div class="card-header py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4>Medicine Purchase report
                            @isset($start_date)
                                  From : {{ \Carbon\Carbon::parse($start_date)->format('d-m-Y') }} To {{ \Carbon\Carbon::parse($end_date)->format('d-m-Y') }}
                            @endisset
                        </h4>
                    </div>
                </div>
                <div class="card-body">

                    {!! Form::open(['url' => 'purchase-report-submit', 'method' => 'POST', 'class' => 'form-horizontal', 'files' => true]) !!}
                     <div class="row">
                         <div class="col-md-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <label class="input-group-text" for="inputGroupSelect01" style="margin-right: 10px">Start Date </label>
                                </div>
                               {!! Form::date('start_date', '', ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=> 'start date']) !!}
                              </div>
                         </div>
                         <div class="col-md-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                  <label class="input-group-text" for="inputGroupSelect01" style="margin-right: 10px">End Date</label>
                                </div>
                               {!! Form::date('end_date', '', ['class'=>'form-control', 'autocomplete'=>'off', 'placeholder'=> 'end date']) !!}
                              </div>
                         </div>
                         <div class="col-md-2">
                            <button type="submit" class="btn btn-info">
                                Search
                            </button>
                         </div>
                     </div>
                     {!! Form::close() !!}


                    <div class="table-responsive">
                        <table class="table display table-bordered table-striped table-hover custom-table"
                        id="product_purchase">
                        <thead>
                            <tr>
                                <th>sl</th>


                                <th>Supplier</th>
                                <th>Purchase Date</th>
                                <th>Payment Method</th>
                                <th>Purchased By</th>
                                <th>Total</th>
                                <th>Pay</th>
                                <th>Due</th>
                            </tr>
                        </thead>
                        <tbody>

                            @isset($productSales)

                            @foreach ($productSales as $productPurchase)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    @if ( $productPurchase->supplier_id == null)


                                        <td> N/A </td>
                                    @elseif ($productPurchase->supplier_id != null)

                                        <td>{{ $productPurchase->supplier->supplier_name }}</td>
                                    @endif

                                    <td>{{ \Carbon\Carbon::parse($productPurchase->purchase_date)->format('d-m-Y') }}
                                    </td>
                                    <td>{{ $productPurchase->payment->method_name }}</td>


                                    <td>{{ $productPurchase->user->name }}</td>



                                    <td>{{ $productPurchase->grand_total }}</td>
                                    <td>{{ $productPurchase->paid_amount }}</td>
                                    @if ($productPurchase->due_amount > 0)
                                        <td> {{ $productPurchase->due_amount }} </td>
                                    @else
                                        <td>Paid</td>
                                    @endif
                                </tr>
                                @php
                                $grand_total = $grand_total + $productPurchase->grand_total;
                                $total_due = $total_due + $productPurchase->paid_amount;
                                $total_pay = $total_pay + $productPurchase->due_amount;
                                @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>

                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><b>Total Amount</b></td>
                                <td><b>৳ {{ $grand_total }}</b></td>
                                <td><b>৳ {{ $total_due }}</b></td>
                                <td><b>৳ {{ $total_pay }}</b></td>
                            </tr>
                        </tfoot>
                        @endisset
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
{{-- <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script> --}}
<script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.4/js/buttons.print.min.js"></script>
<script type="text/javascript">

$(document).ready(function() {
    $('#product_purchase').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible'
                }
            },

            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ ':visible' ]
                }
            },
            'colvis'
        ]
    } );
} );


</script>
@endpush
@endsection
