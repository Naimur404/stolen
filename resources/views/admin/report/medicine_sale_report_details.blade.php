@extends('layouts.admin.public_layouts')
@section('title','Product Sales Report')
@section('main-content')
    <style>
        .space {
            line-height: 3px;
        }

        .all-content {
            margin-top: 60px !important;
            min-width: 1000px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .table tbody tr td {
            /* border-top: none; */
            border: 1px solid #070707;
            padding: 2px;
        }

        .table thead tr th {
            /* border-top: none; */
            border: 1px solid #070707;
            padding: 2px;
            text-align: center;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/fontawesome.css')}}">
    @php
        $total_amount = 0;
    @endphp

    <div class="row">
        <div class="col-md-5">
            <img src="{{asset('uploads/'.$app_setting->logo)}}" alt="" style="max-height: 150px">
        </div>
        <div class="col-md-7 text-right">
            <br>
            <h4>{{ $title }}</h4>
            {{-- <p>{{ $business->address }} <br> {{ $business->mobile }}</p> --}}
        </div>
    </div>

    <div class="row all-content">
        <p align="center"><b> Medicine Sales Report -
                @if ($start_date && $end_date !=null )
                    From {{Carbon\Carbon::parse($start_date)->format('d-m-Y')}}
                    To {{Carbon\Carbon::parse($end_date)->format('d-m-Y') }}
                @endif
            </b></p>
        <div class="col-md-12">
            <table class="table table-hover table-bordered">
                <thead class="">
                <tr>
                    <th>SL</th>
                    <th>Outlet Name</th>
                    <th>Sale Date</th>
                    <th>Method</th>
                    <th>Sold By</th>
                    <th>Sub Total</th>
                    <th>Discount</th>
                    <th>Vat</th>
                    <th>Grand Total</th>
                    <th>Pay</th>
                    <th>Due</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $grand_total = 0;
                    $sub_total = 0;
                    $total_due = 0;
                    $total_discount = 0;
                    $total_pay = 0;
                @endphp
                @foreach ($productSales as $productSale)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $productSale->outlet->outlet_name ?? '' }}</td>
                        <td>{{ \Carbon\Carbon::parse($productSale->sale_date)->format('d-m-Y') }}
                        </td>
                        <td>{{ $productSale->payment->method_name ?? '' }}</td>


                        <td>{{ $productSale->user->name ?? '' }}</td>


                        <td>{{ $productSale->sub_total }}</td>
                        <td>{{ $productSale->total_discount }}</td>
                        <td>{{ $productSale->vat }}</td>
                        <td>{{ $productSale->grand_total }}</td>
                        <td>{{ $productSale->paid_amount }}</td>
                        <td>
                            {{ $productSale->due_amount > 0.5 ? $productSale->due_amount : 'Paid' }}
                        </td>

                        <td class="">

                            <a href="{{ route('sale-report-details1', $productSale->id) }}" target="_blank"
                               class="btn btn-danger btn-xs" title="Print" style="margin-right:3px"><i
                                    class="fa fa-print" aria-hidden="true"></i></a>


                        </td>
                    </tr>
                    @php
                        $sub_total = $sub_total + $productSale->sub_total;
                        $grand_total =  $grand_total + $productSale->grand_total;
                        $total_due = $total_due + $productSale->due_amount > 0.5 ? $productSale->due_amount : 0;
                        $total_discount = $total_discount + $productSale->total_discount;
                        $total_pay = $total_pay + $productSale->paid_amount;
                    @endphp
                @endforeach


                </tbody>
            </table>

            <p class="text-center">Sub Total {{ $sub_total }} | Total Discount {{ $total_discount }} | Grand Total {{ $grand_total }} |  Total Pay {{ $total_pay }} | Total
                Due {{ $total_due }}

                </p>
                <p class="text-center" style="font-size: 12px">Thank You ❤</p>

        </div>
    </div>
    @if ($app_setting->print == 1)
    <script>
        setTimeout(function() { window.print(); }, 1000);
    </script>
    @endif
@endsection
