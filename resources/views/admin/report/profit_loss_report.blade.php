@extends('layouts.admin.public_layouts')
@section('title', $title)
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
            text-align: center;
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


    <div class="col-md-12">
        <table class="table table-hover table-bordered">
            <thead class="">
            <tr>
                <th>SL</th>
                <th>Sales Date</th>
                <th>Medicine Name</th>
                <th>Expiry Date</th>
                <th>Purchase Price</th>
                <th>Sell Price</th>
                <th>Quantity</th>
                <th>Discount</th>
                <th>Total Price</th>
            </tr>
            </thead>
            <tbody>
            @php
                $grand_quantity = 0;
                $total_sale = 0;
                $total_discount = 0;
                $total_buy = 0;

            @endphp
            @foreach ($productSales as $sale)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->created_at)->format('d-m-Y') }}
                    </td>
                    <td>{{ $sale->medicine_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($sale->expiry_date)->format('d-m-Y') }}</td>
                    <td>{{ $sale->purchase_price }}</td>
                    <td>{{ $sale->rate }}</td>
                    <td>{{ $sale->quantity }}</td>
                    <td>{{ $sale->discount }}</td>
                    <td>{{ $sale->total_price }}</td>
                </tr>
                @php
                    $grand_quantity = $grand_quantity + $sale->quantity;
                    $total_sale = $total_sale + $sale->total_price;
                    $total_discount = $total_discount + $sale->discount;
                    $total_buy =  $total_buy + ($sale->purchase_price * $sale->quantity);
                @endphp
            @endforeach


            </tbody>
        </table>

        <p class="text-center">Total Sale(Include Discount) {{ $total_sale-$total_discount}}
            | Total Quantity {{ $grand_quantity }} | Total Profit/Loss: {{ $total_sale-$total_discount-$total_buy }} | Total Discount {{ $total_discount}}

        </p>
        <p class="text-center" style="font-size: 12px">Software by Pigeon Soft</p>

    </div>
    </div>

@endsection

