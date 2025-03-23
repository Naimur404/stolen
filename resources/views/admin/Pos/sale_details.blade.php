@extends('layouts.admin.master')
@section('title')Invoice Details
@endsection
@push('css')
<style>
    /* Enhanced styling for tables and sections */
    .invoice-container {
        background-color: #fff;
        border-radius: 5px;
    }
    
    .invoice-header {
        padding: 15px;
        border-bottom: 1px solid #eee;
        margin-bottom: 20px;
    }
    
    .invoice-meta-item {
        margin-bottom: 8px;
    }
    
    .invoice-meta-label {
        font-weight: 500;
        display: inline-block;
        min-width: 120px;
    }
    
    .invoice-meta-value {
        font-weight: 600;
    }
    
    .invoice-title {
        color: #333;
        margin-bottom: 0;
        font-size: 1.25rem;
        font-weight: 600;
    }
    
    .products-table {
        border: 1px solid #dee2e6;
        margin-top: 1rem;
    }
    
    .products-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        vertical-align: middle;
    }
    
    .products-table td {
        vertical-align: middle;
        padding: 0.5rem;
    }
    
    .products-table .amount-col {
        text-align: right;
        font-weight: 500;
    }
    
    .payment-section {
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid #eee;
    }
    
    .payment-table th {
        font-weight: 600;
        background-color: #f8f9fa;
    }
    
    .payment-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: #333;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #eee;
    }
    
    .summary-table {
        margin-top: 1rem;
    }
    
    .summary-table td {
        padding: 0.5rem;
        border: none;
    }
    
    .summary-table td:first-child {
        font-weight: 600;
        width: 50%;
    }
    
    .summary-table td:last-child {
        text-align: right;
        font-weight: 500;
    }
    
    .summary-row-total {
        font-weight: 700;
        font-size: 1.1rem;
        border-top: 1px solid #dee2e6;
    }
    
    .summary-box {
        background-color: #f8f9fa;
        border-radius: 5px;
        padding: 15px;
    }
    
    .due-amount {
        color: #dc3545;
    }
    
    .zero-due {
        color: #28a745;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .invoice-meta-label {
            min-width: 100px;
            font-size: 0.9rem;
        }
        
        .invoice-meta-value {
            font-size: 0.9rem;
        }
        
        .products-table th,
        .products-table td,
        .payment-table th,
        .payment-table td {
            font-size: 0.9rem;
            padding: 0.4rem;
        }
    }
</style>
@endpush
@section('content')
@component('components.breadcrumb')
    @slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
                <h3>Invoice Details</h3>
            </div>
        </div>
    @endslot

    @slot('button')
        <a href="{{ route('invoice.index') }}" class="btn btn-primary">
            <i class="fa fa-arrow-left me-2"></i> Back to Invoices
        </a>
    @endslot
@endcomponent

<div class="col-md-12 col-lg-12">
    <div class="card invoice-container">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="invoice-title">
                    <i class="fa fa-file-invoice me-2"></i> Invoice #{{ $salesReturn->id }}
                </h5>
            </div>
        </div>

        <div class="card-body">
            <!-- Invoice Information -->
            <div class="invoice-header">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="invoice-meta-item">
                                    <span class="invoice-meta-label">Invoice No:</span>
                                    <span class="invoice-meta-value">{{ $salesReturn->id }}</span>
                                </div>
                                <div class="invoice-meta-item">
                                    <span class="invoice-meta-label">Return Date:</span>
                                    <span class="invoice-meta-value">{{ \Carbon\Carbon::parse($salesReturn->sale_date)->format('d M Y') }}</span>
                                </div>
                                <div class="invoice-meta-item">
                                    <span class="invoice-meta-label">Customer:</span>
                                    <span class="invoice-meta-value">{{ $salesReturn->customer->mobile }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="invoice-meta-item">
                                    <span class="invoice-meta-label">Outlet:</span>
                                    <span class="invoice-meta-value">{{ $salesReturn->outlet->outlet_name }}</span>
                                </div>
                                <div class="invoice-meta-item">
                                    <span class="invoice-meta-label">Status:</span>
                                    <span class="invoice-meta-value">
                                        @if($salesReturn->due_amount > 0)
                                            <span class="badge bg-warning">Partial Payment</span>
                                        @else
                                            <span class="badge bg-success">Paid</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="table-responsive">
                <table class="table table-bordered products-table">
                    <thead>
                        <tr>
                            <th width="5%">SL</th>
                            <th width="35%">Product</th>
                            <th width="10%">Size</th>
                            <th width="12%">Purchase Qty</th>
                            <th width="12%">Return Qty</th>
                            <th width="13%">Unit Price</th>
                            <th width="13%">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salesreturndetails as $data)
                            <tr>
                                <td class="text-center">{{ $loop->index + 1 }}</td>
                                <td>{{ $data->medicine_name }}</td>
                                <td>{{ $data->size }}</td>
                                <td class="text-center">{{ $data->quantity }}</td>
                                @php
                                    $data1 = App\Models\SalesReturn::where('invoice_id', $data->outlet_invoice_id ?? false)->first();
                                    $return = App\Models\SalesReturnDetails::where('sales_return_id', $data1->id ?? false)
                                        ->where('medicine_id', $data->medicine_id)
                                        ->where('size','=', $data->size)
                                        ->first();
                                @endphp
                                <td class="text-center">{{ $return->return_qty ?? '0' }}</td>
                                <td class="amount-col">{{ number_format($data->rate, 2) }}</td>
                                <td class="amount-col">{{ number_format($data->total_price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Due Payment Details Section -->
            @if (count($saledetails) != 0)
                <div class="payment-section">
                    <h6 class="payment-title">
                        <i class="fa fa-money-bill-wave me-2"></i> Payment History
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-bordered payment-table">
                            <thead>
                                <tr>
                                    <th>Invoice ID</th>
                                    <th>Customer</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-end">Paid</th>
                                    <th class="text-end">Due</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($saledetails as $data)
                                    <tr>
                                        <td>{{ $data->invoice_id }}</td>
                                        <td>{{ $data->customer->name }}</td>
                                        <td class="text-end">{{ number_format($data->amount, 2) }}</td>
                                        <td class="text-end">{{ number_format($data->pay, 2) }}</td>
                                        <td class="text-end {{ $data->due > 0 ? 'text-danger fw-bold' : '' }}">
                                            {{ number_format($data->due, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Invoice Summary -->
            <div class="row mt-4">
                <div class="col-md-7">
                    <!-- Any additional information could go here -->
                </div>
                <div class="col-md-5">
                    <div class="summary-box">
                        <table class="table summary-table">
                            <tr>
                                <td>Sub Total:</td>
                                <td>{{ number_format($salesReturn->sub_total, 2) }}</td>
                            </tr>
                            <tr>
                                <td>VAT:</td>
                                <td>{{ number_format($salesReturn->vat, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Discount:</td>
                                <td>{{ number_format($salesReturn->total_discount, 2) }}</td>
                            </tr>
                            <tr class="summary-row-total">
                                <td>Grand Total:</td>
                                <td>{{ number_format($salesReturn->grand_total, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Paid Amount:</td>
                                <td>{{ number_format($salesReturn->grand_total - $salesReturn->due_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Due Amount:</td>
                                <td class="{{ $salesReturn->due_amount > 0 ? 'due-amount' : 'zero-due' }}">
                                    {{ number_format($salesReturn->due_amount, 2) }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
@if (Session()->get('success'))
<script>
    $.notify('<i class="fa fa-bell-o"></i><strong>{{ Session()->get('success') }}</strong>', {
        type: 'theme',
        allow_dismiss: true,
        delay: 2000,
        showProgressbar: true,
        timer: 300
    });
</script>
@endif
@if (Session()->get('error'))
<script>
    $.notify('<i class="fa fa-bell-o"></i><strong>{{ Session()->get('error') }}</strong>', {
        type: 'theme',
        allow_dismiss: true,
        delay: 2000,
        showProgressbar: true,
        timer: 300
    });
</script>
@endif
@endpush
@endsection