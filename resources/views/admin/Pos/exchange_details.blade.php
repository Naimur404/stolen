@extends('layouts.admin.master')
@section('title')Exchange Details
@endsection
@push('css')
<style>
    /* Enhance table styling */
    .table-exchange thead th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .exchange-table-returned thead {
        background-color: #ffebee;
    }
    
    .exchange-table-new thead {
        background-color: #e8f5e9;
    }
    
    .amount-col {
        text-align: right;
        font-weight: 500;
    }
    
    .total-row {
        background-color: #f5f5f5;
        font-weight: 600;
    }
    
    .invoice-header {
        padding: 15px;
        border-bottom: 1px solid #dee2e6;
        margin-bottom: 20px;
    }
    
    .invoice-meta {
        margin-bottom: 20px;
    }
    
    .invoice-meta-item {
        margin-bottom: 8px;
    }
    
    .invoice-meta-label {
        font-weight: 500;
        display: inline-block;
        min-width: 150px;
    }
    
    .invoice-meta-value {
        font-weight: 600;
    }
    
    .card-title-exchange {
        color: #333;
        margin-bottom: 0;
        font-size: 20px;
    }
    
    .exchange-section {
        margin-bottom: 30px;
    }
    
    .section-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 2px solid #eee;
    }
    
    .returned-products-heading {
        color: #e53935;
    }
    
    .new-products-heading {
        color: #43a047;
    }
    
    .exchange-summary {
        background-color: #f8f9fa;
        border-radius: 4px;
        padding: 15px;
        margin-top: 20px;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }
    
    .summary-label {
        font-weight: 500;
    }
    
    .summary-value {
        font-weight: 600;
        text-align: right;
    }
    
    .balance-positive {
        color: #43a047;
    }
    
    .balance-negative {
        color: #e53935;
    }

    /* Make responsive */
    @media (max-width: 768px) {
        .invoice-meta-label {
            min-width: 120px;
        }
    }
</style>
@endpush

@section('content')
@component('components.breadcrumb')
    @slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
                <h3>Exchange Details</h3>
            </div>
        </div>
    @endslot

    @slot('button')
        <a href="{{ route('exchange.index') }}" class="btn btn-primary">
            <i class="fa fa-arrow-left me-2"></i> Back to Exchanges
        </a>
    @endslot
@endcomponent

<div class="col-md-12 col-lg-12">
    <div class="card">
        <div class="card-header py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title-exchange">
                    <i class="fa fa-exchange-alt me-2"></i> Exchange #{{ $exchange->id }}
                </h5>
                
                <div>
                    <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                        <i class="fa fa-print me-1"></i> Print
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Invoice Information -->
            <div class="invoice-header">
                <div class="row">
                    <div class="col-md-8">
                        <div class="invoice-meta">
                            <div class="invoice-meta-item">
                                <span class="invoice-meta-label">Invoice No:</span>
                                <span class="invoice-meta-value">{{ $exchange->original_invoice_id }}</span>
                            </div>
                            <div class="invoice-meta-item">
                                <span class="invoice-meta-label">Exchange Date:</span>
                                <span class="invoice-meta-value">{{ \Carbon\Carbon::parse($exchange->created_at)->format('d M Y, h:i A') }}</span>
                            </div>
                            <div class="invoice-meta-item">
                                <span class="invoice-meta-label">Customer:</span>
                                <span class="invoice-meta-value">{{ $exchange->customer->mobile }}</span>
                                @if($exchange->customer->name)
                                    <span class="text-muted ms-2">({{ $exchange->customer->name }})</span>
                                @endif
                            </div>
                            <div class="invoice-meta-item">
                                <span class="invoice-meta-label">Outlet:</span>
                                <span class="invoice-meta-value">{{ $exchange->outlet->outlet_name }}</span>
                            </div>
                            <div class="invoice-meta-item">
                                <span class="invoice-meta-label">Processed By:</span>
                                <span class="invoice-meta-value">{{ $exchange->addedBy->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="bg-primary p-3 rounded">
                            <div class="h5 mb-2">Exchange Summary</div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Returned Value:</span>
                                <span class="text-danger fw-bold">{{ number_format($exchange->grand_total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Additional Payment:</span>
                                <span class="fw-bold">{{ number_format($exchange->paid_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Returned Products Section -->
            <div class="exchange-section">
                <h6 class="section-title returned-products-heading">
                    <i class="fa fa-arrow-left me-2"></i> Returned Products
                </h6>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-exchange exchange-table-returned">
                        <thead>
                            <tr>
                                <th width="5%">SL</th>
                                <th width="35%">Product</th>
                                <th width="10%">Size</th>
                                <th width="10%">Quantity</th>
                                <th width="15%">Unit Price</th>
                                <th width="15%">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $returnedTotal = 0;
                                $returnedItems = $exchangeDetails->where('is_exchange', 1);
                            @endphp
                            
                            @if($returnedItems->count() > 0)
                                @foreach($returnedItems as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->medicine_name }}</td>
                                        <td>{{ $item->size }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="amount-col">{{ number_format($item->rate, 2) }}</td>
                                        <td class="amount-col">{{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                    @php $returnedTotal += $item->total_price; @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">No products returned</td>
                                </tr>
                            @endif
                            
                            <tr class="total-row">
                                <td colspan="5" class="text-end">Total Returned Value:</td>
                                <td class="amount-col">{{ number_format($returnedTotal, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- New Products Section -->
            <div class="exchange-section">
                <h6 class="section-title new-products-heading">
                    <i class="fa fa-arrow-right me-2"></i> New Products
                </h6>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-exchange exchange-table-new">
                        <thead>
                            <tr>
                                <th width="5%">SL</th>
                                <th width="35%">Product</th>
                                <th width="10%">Size</th>
                                <th width="10%">Quantity</th>
                                <th width="15%">Unit Price</th>
                                <th width="15%">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $newTotal = 0;
                                $newItems = $exchangeDetails->where('is_exchange', 0);
                            @endphp
                            
                            @if($newItems->count() > 0)
                                @foreach($newItems as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->medicine_name }}</td>
                                        <td>{{ $item->size }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="amount-col">{{ number_format($item->rate, 2) }}</td>
                                        <td class="amount-col">{{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                    @php $newTotal += $item->total_price; @endphp
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">No new products added</td>
                                </tr>
                            @endif
                            
                            <tr class="total-row">
                                <td colspan="5" class="text-end">Total New Products Value:</td>
                                <td class="amount-col">{{ number_format($newTotal, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Exchange Summary -->
            <div class="exchange-summary">
                <h6 class="mb-3">Exchange Balance</h6>
                
                <div class="summary-row">
                    <div class="summary-label">Total Returned Value:</div>
                    <div class="summary-value">{{ number_format($returnedTotal, 2) }}</div>
                </div>
                
                <div class="summary-row">
                    <div class="summary-label">Total New Products Value:</div>
                    <div class="summary-value">{{ number_format($newTotal, 2) }}</div>
                </div>
                
                <div class="summary-row">
                    <div class="summary-label">Balance:</div>
                    @php
                        $balance = $newTotal - $returnedTotal;
                    @endphp
                    <div class="summary-value {{ $balance >= 0 ? 'balance-positive' : 'balance-negative' }}">
                        @if($balance > 0)
                            Customer Paid: {{ number_format($balance, 2) }}
                        @elseif($balance < 0)
                            Store Refunded: {{ number_format(abs($balance), 2) }}
                        @else
                            Even Exchange
                        @endif
                    </div>
                </div>
                
                @if($exchange->notes)
                <div class="mt-3 pt-3 border-top">
                    <div class="summary-label mb-2">Notes:</div>
                    <div>{{ $exchange->notes }}</div>
                </div>
                @endif
            </div>
        </div>
        
        <div class="card-footer text-end">
            <a href="{{ route('exchange.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-list me-1"></i> All Exchanges
            </a>
            
            {{-- <a href="{{ route('print-invoice-exchange', $exchange->id) }}" target="_blank" class="btn btn-primary ms-2">
                <i class="fa fa-print me-1"></i> Print Invoice
            </a> --}}
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