@extends('layouts.admin.public_layouts')
@section('title','Manufacturer Sales Report')
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
<div class="row">
    <div class="col-md-5">
        <img src="{{asset('uploads/'.$app_setting->logo)}}" alt="" style="max-height: 150px">
    </div>
    <div class="col-md-7 text-right">
        <br>
        <h4>Manufacturer Sales Report - {{ $manufacturer_name[0] }}</h4>
        {{-- <p>{{ $business->address }} <br> {{ $business->mobile }}</p> --}}
    </div>
</div>

<div class="row all-content">
    <p align="center"><b> Manufacturer Sales Report -
        @if ($start_date && $end_date !=null )
        From {{Carbon\Carbon::parse($start_date)->format('d-m-Y')}}
        To {{Carbon\Carbon::parse($end_date)->format('d-m-Y') }}
    @endif
   </b></p>
    <div class="col-md-12">
        <table class="table table-hover table-bordered">
            <thead class="">
                <tr>
                                <th>Date</th>
                                <th>Manuf. Name</th>
                                <th>Medicine</th>
                                <th>Sell Qty</th>
                                <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @php
                $total_qty = 0;
                $total_amount = 0;
                @endphp
                    @foreach ($manufacturer_sales as $manufacturer_sale)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($manufacturer_sale->created_at)->format('d-m-Y') }}
                        <td> {{ $manufacturer_name[0] }}</td>
                        <td>{{ $manufacturer_sale->medicine_name }}</td>
                        <td>{{ $manufacturer_sale->quantity }}</td>
                        <td>{{ $manufacturer_sale->total_price}}
                        </td>

                    </tr>
                    @php
                    $total_qty = $total_qty + $manufacturer_sale->quantity;
                    $total_amount = $total_amount + $manufacturer_sale->total_price;

                    @endphp
                @endforeach

            </tbody>
        </table>

        <p class="text-center">Total QTY {{ $total_qty }}/- | Total Amount {{ $total_amount }}</p>
        <p class="text-center" style="font-size: 12px">Thank You ❤ Software by Pigeon Soft</p>

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
