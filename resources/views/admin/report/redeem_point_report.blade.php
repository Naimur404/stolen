@extends('layouts.admin.public_layouts')
@section('title','Redeem Point Report')
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
            text-align: center;
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
        <h4>Redeem Point Report</h4>
        {{-- <p>{{ $business->address }} <br> {{ $business->mobile }}</p> --}}
    </div>
</div>

<div class="row all-content">
    <p align="center"><b> Redeem Point Report -
        @if ($start_date && $end_date !=null )
           From {{Carbon\Carbon::parse($start_date)->format('d-m-Y')}}
           To {{Carbon\Carbon::parse($end_date)->format('d-m-Y') }}
       @endif
   </b></p>
    <div class="col-md-12">
        <table class="table table-hover table-bordered">
            <thead class="">
                <tr>
                                <th>Purchase Date</th>
                                <th>Invoice ID</th>
                                <th>Customer</th>
                                <th>Invoice Amount</th>
                                <th>Redeem Point</th>


                </tr>
            </thead>
            <tbody>
                @php
                $point = 0;


                @endphp
                     @foreach ($redeemSales as $redeemSale)
                     <tr>

                         <td>{{ \Carbon\Carbon::parse($redeemSale->sale_date)->format('d-m-Y') }}
                         </td>
                         <td>{{ $redeemSale->id }}</td>


                         <td>{{ $redeemSale->customer->mobile?? ''}}</td>



                         <td>{{ $redeemSale->grand_total }}</td>
                         <td>{{ $redeemSale->redeem_point }}</td>

                     </tr>
                     @php
                    $point = $point +  $redeemSale->redeem_point;


                    @endphp
                @endforeach


            </tbody>
        </table>

        <p class="text-center">Total Redeem Point {{ $point }} </p>
        <p class="text-center" style="font-size: 12px">Thank You ❤ Software by Pigeon Soft</p>

    </div>
</div>

@if ($app_setting->print == 1)
<script>
    setTimeout(function() { window.print(); }, 1000);
</script>
@endif
@endsection
