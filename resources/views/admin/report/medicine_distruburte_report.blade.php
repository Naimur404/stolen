@extends('layouts.admin.public_layouts')
@section('title','Distribute Product Report')
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
    <p align="center"><b> Distribute Medicines Report -
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
                    <th>Warehouse Name</th>
                    <th>Date</th>
                    <th>Product Name</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>


                </tr>
            </thead>
            <tbody>
                @php
                $grand_quantity = 0;
                $total_price = 0;
                $total_discount = 0;

                @endphp
                     @foreach ($productSales as $productPurchase)
                     <tr>
                        <td>{{ $loop->index + 1 }}</td>
                           @php
                         $outlet = App\Models\Outlet::where('id',$productPurchase->outlet_id)->first('outlet_name');
                       $warehouse = App\Models\Warehouse::where('id',$productPurchase->warehouse_id)->first('warehouse_name');
                           @endphp
                        <td>{{ $outlet->outlet_name }}</td>
                        <td>{{ $warehouse->warehouse_name }}</td>
                         <td>{{ \Carbon\Carbon::parse($productPurchase->date)->format('d-m-Y') }}
                         </td>
                         <td>{{ $productPurchase->medicine_name }}</td>


                         <td>{{ $productPurchase->size }}</td>



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

        <p class="text-center">Grand Total {{ $total_price }} | Total Quantity {{ $grand_quantity }}</p>
        <p class="text-center" style="font-size: 12px">Thank You ❤ Software by Pigeon Soft</p>

    </div>
</div>

@if ($app_setting->print == 1)
<script>
    setTimeout(function() { window.print(); }, 1000);
</script>
@endif
@endsection
