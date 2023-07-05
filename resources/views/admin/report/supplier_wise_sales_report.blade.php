@extends('layouts.admin.public_layouts')
@section('title','Supplier Wise Product Sales Report')
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

                    {{-- <th>Outlet Name</th> --}}
                    <th>Purchase Date</th>
                    <th>Medicine Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Total</th>

                </tr>
                </thead>
                <tbody>
                @php
                    $grand_total = 0;
                    $total_quantity = 0;
                    $total_discount = 0;
                @endphp
                @foreach ($productSales as $productPurchase)


                    @foreach($manu as $check)
                        @if ($check->manufacturer_id == $productPurchase->manufacturer_id)



                            <td>{{ \Carbon\Carbon::parse($productPurchase->created_at)->format('d-m-Y') }}
                            </td>
                            <td>{{ $productPurchase->medicine_name }}</td>


                            <td>{{ $productPurchase->quantity }}</td>



                            <td>{{ $productPurchase->rate }}</td>
                            <td>{{ $productPurchase->discount }}</td>
                            <td>{{ $productPurchase->total_price }}</td>




                            @else
                                @continue
                            @endif
                            @php
                                $grand_total = $grand_total + $productPurchase->total_price;
                                $total_quantity = $total_quantity + $productPurchase->quantity;
                                $total_discount = $total_discount + $productPurchase->discount;
                            @endphp

                            @endforeach
                            </tr>

                            @endforeach


                </tbody>
            </table>

            <p class="text-center">Grand Total {{ $grand_total }} | Total Quantrity {{ $total_quantity }} | Total
                Discount {{ $total_discount }}</p>

        </div>
    </div>
    @if ($app_setting->print == 1)
        <script>
            setTimeout(function () {
                window.print();
            }, 1000);
        </script>
    @endif
@endsection
