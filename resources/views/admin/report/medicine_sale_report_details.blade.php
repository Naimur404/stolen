@extends('layouts.admin.public_layouts')
@section('title','Medicine Sales Report')
@section('main-content')
<style>
    .space{
        line-height: 3px;
    }
    .all-content{
            margin-top: 60px !important;
            min-width: 1000px;
            max-width: 1000px;
            margin: 0 auto;
        }
    .table tbody tr td{
            /* border-top: none; */
            border: 1px solid #070707;
            padding: 2px;
        }
    .table thead tr th{
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
                                <th>Purchase Date</th>
                                <th>Payment Method</th>
                                <th>Purchased By</th>
                                <th>Total</th>
                                <th>Pay</th>
                                <th>Due</th>
                                <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                $grand_total = 0;
                $total_due = 0;
                $total_pay = 0;
                @endphp
                     @foreach ($productSales as $productPurchase)
                     <tr>
                         <td>{{ $loop->index + 1 }}</td>
                         @if ($productPurchase->outlet_id == null )

                         <td> N/A </td>
                     @elseif ($productPurchase->outlet_id != null )
                         <td>{{ $productPurchase->outlet->outlet_name }}</td>

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

                         <td class="">

                            <a href="{{ route('sale-report-details1', $productPurchase->id) }}" target="_blank"
                               class="btn btn-danger btn-xs" title="Print" style="margin-right:3px"><i
                                    class="fa fa-print" aria-hidden="true"></i></a>



                        </td>
                     </tr>
                     @php
                     $grand_total = $grand_total + $productPurchase->grand_total;
                     $total_due = $total_due + $productPurchase->paid_amount;
                     $total_pay = $total_pay + $productPurchase->due_amount;
                     @endphp
                 @endforeach


            </tbody>
        </table>

        <p class="text-center">Grand Total {{ $grand_total }} | Total Pay {{ $total_pay }} | Total Due {{ $total_due }}</p>

    </div>
</div>


@endsection
