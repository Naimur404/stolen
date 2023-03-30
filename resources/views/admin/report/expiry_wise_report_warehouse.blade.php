@extends('layouts.admin.public_layouts')
@section('title','Expiry Wise Report')
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
    <p align="center"><b> Outlet Stock -
        @if ($start_date !=null )
            {{Carbon\Carbon::parse($start_date)->format('d-m-Y')}}

       @endif
   </b></p>
    <div class="col-md-12">
        <table class="table table-hover table-bordered">
            <thead class="">
                <tr>
                    <th>SL</th>

                                <th>Warehouse Name</th>
                                <th>Medicine Name</th>
                                <th>Expiry Date</th>

                                <th>Price</th>
                                <th>Quantity</th>

                </tr>
            </thead>
            <tbody>
                @php
                $grand_quantity = 0;
                $total_price = 0;

                @endphp
                    @foreach ($productSales as $productPurchase)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        @if ( $productPurchase->warehouse_id == null)


                            <td> N/A </td>
                        @elseif ($productPurchase->warehouse_id != null)

                            <td>{{ $productPurchase->warehouse->warehouse_name }}</td>
                        @endif


                        <td>{{ $productPurchase->medicine->medicine_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($productPurchase->expiry_date)->format('d-m-Y') }}
                        </td>





                        <td>{{ $productPurchase->price }}</td>
                        <td>{{ $productPurchase->quantity }}</td>


                    </tr>
                    @php
                    $grand_quantity = $grand_quantity + $productPurchase->quantity;

                    $total_price = $total_price + $productPurchase->price * $productPurchase->quantity;
                    @endphp
                @endforeach


            </tbody>
        </table>

        <p class="text-center">Total Quantity {{ $grand_quantity }} | Total Price {{ $total_price }}</p>
        <p class="text-center" style="font-size: 12px">Thank You ❤ Software by Pigeon Soft</p>

    </div>
</div>

@section('custom-js')
<script>
    setTimeout(function() { window.print(); }, 1000);
</script>

@endsection
@endsection
