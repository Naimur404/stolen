<!-- Created by Ariful Islam at 23/5/23 - 7:20 PM -->
@extends('layouts.admin.master')

@section('title', $medicine->medicine_name.' - History')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
    <div class="container-fluid list-products">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="text-center">{{ $medicine->medicine_name }} <i class="text-success">( {{ $medicine->category->category_name }} )</i></h5>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h6 class="text-center">Total Purchase: {{ $total_purchase }}</h6>
                        <div class="table">
                            <table class="data-table">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Purchase #</th>
                                    <th>Warehouse</th>
                                    <th>Rate</th>
                                    <th>MRP</th>
                                    <th>QTY</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($purchases as $purchase)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('d-m-Y') }}</td>
                                        <td><a href="{{ route('product-purchase.show', $purchase->medicine_purchase_id) }}" target="_blank">{{ $purchase->medicine_purchase_id }}</a></td>
                                        <td>{{ $purchase->warehouse->warehouse_name ?? '' }}</td>
                                        <td>{{ $purchase->rate }}</td>
                                        <td>{{ $purchase->box_mrp }}</td>
                                        <td>{{ $purchase->quantity }}</td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow">
                    <div class="card-body">
                        <h6 class="text-center">Total Sale: {{ $total_sale }}</h6>
                        <div class="table">
                            <table class="display data-table">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Invoice #</th>
                                    <th>Outlet Name</th>
                                    <th>Rate</th>
                                    <th>QTY</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sales as $sale)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($sale->created_at)->format('d-m-Y') }}</td>
                                        <td><a href="{{ url('print-invoice', $sale->outlet_invoice_id) }}" target="_blank">{{ $sale->outlet_invoice_id }}</a></td>
                                        <td>{{ $sale->invoice->outlet->outlet_name }}</td>
                                        <td>{{ $sale->rate }}</td>
                                        <td>{{ $sale->quantity }}</td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            $(document).ready(function (){
                var table = $('.data-table').DataTable({
                    processing: true,
                });
            });
        </script>
    @endpush

@endsection
