@extends('layouts.admin.public_layouts')
@section('title','Invoice Report Details')
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
        <h4>{{ $title }}</h4>
        {{-- <p>{{ $business->address }} <br> {{ $business->mobile }}</p> --}}
    </div>
</div>

{{-- <div class="row all-content">
    <p align="center"><b> Medicine Sales Report -
        @if ($start_date && $end_date !=null )
           From {{Carbon\Carbon::parse($start_date)->format('d-m-Y')}}
           To {{Carbon\Carbon::parse($end_date)->format('d-m-Y') }}
       @endif
   </b></p> --}}
    <div class="col-md-12">
        <table class="table table-hover table-bordered">
            <thead class="">
                <tr>
                    <th>SL</th>



                                <th>Purchase Date</th>
                                <th>Medicine Name</th>
                                <th>Expiry Date</th>
                                <th>Quantity</th>
                                <th>Price</th>


                </tr>
            </thead>
            <tbody>
                @php
                $grand_quantity = 0;
                $total_price = 0;

                @endphp
                     @foreach ($data as $productPurchase)
                     <tr>
                        <td>{{ $loop->index + 1 }}</td>
                         <td>{{ \Carbon\Carbon::parse($productPurchase->purchase_date)->format('d-m-Y') }}
                         </td>
                         <td>{{ $productPurchase->medicine_name }}</td>


                         <td>{{ $productPurchase->expiry_date }}</td>



                         <td>{{ $productPurchase->quantity }}</td>
                         <td>{{ $productPurchase->rate }}</td>


                     </tr>
                     @php
                    $grand_quantity = $grand_quantity + $productPurchase->quantity;

                    $total_price = $total_price + $productPurchase->rate * $productPurchase->quantity;

                    @endphp
                @endforeach


            </tbody>
        </table>

        <p class="text-center">Grand Total {{ $total_price }} | Total Quantity {{ $grand_quantity }} </p>
        <p class="text-center" style="font-size: 12px">Thank You ❤ Software by Pigeon Soft</p>

    </div>
</div>
@if ($app_setting->print == 1)
<script>
    setTimeout(function() { window.print(); }, 1000);
</script>
@endif

@endsection
