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

                <th>Sale Date</th>
                <th>Invoice ID</th>
                <th>Medicine Name</th>
                <th>Sale Quantity</th>


            </tr>
            </thead>
            <tbody>
            @php
                $quantity = 0;
                $discount = 0;
                $total = 0;

            @endphp
            @foreach ($productSales as $productSale)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($productSale->created_at)->format('d-m-Y') }}
                    <td>{{ $productSale->outlet_invoice_id }}</td>
                    <td>{{ $productSale->medicine_name }}
                    </td>

                    <td>{{ $productSale->quantity ?? '' }}</td>





                </tr>
                @php
                    $quantity = $quantity + $productSale->quantity;


                @endphp
            @endforeach


            </tbody>
        </table>

        <p class="text-center"> Quantity
             {{ $quantity }}</p>

    </div>


</div>
@section('custom-js')
@if ($app_setting->print == 1)
<script>
    setTimeout(function() { window.print(); }, 1000);
</script>
@endif
@endsection

@endsection
