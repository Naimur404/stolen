@extends('layouts.admin.master')
@section('title')Due Payment
@endsection

@push('css')
<style>
    /* ===== Due Payment Enhanced Styles ===== */

    /* Base Styles & Variables */
    :root {
        --primary-color: #4361ee;
        --primary-light: #eef2ff;
        --primary-dark: #172d7b;
        --success-color: #2ecc71;
        --danger-color: #e74c3c;
        --warning-color: #f39c12;
        --info-color: #3498db;
        --gray-light: #f8f9fa;
        --gray-medium: #e9ecef;
        --gray-dark: #6c757d;
        --border-radius: 8px;
        --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        --transition: all 0.3s ease;
    }

    body {
        background-color: #f5f7fb;
    }

    /* Card Styling */
    .card {
        border-radius: var(--border-radius) !important;
        box-shadow: var(--box-shadow) !important;
        border: none !important;
        margin-bottom: 1.5rem !important;
        transition: var(--transition);
    }

    .card:hover {
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08) !important;
    }

    .card-header {
        padding: 1rem !important;
        background-color: var(--primary-color) !important;
        color: white !important;
        border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
        font-weight: 600;
        letter-spacing: 0.5px;
        border-bottom: none !important;
    }

    .card-body {
        padding: 1.5rem !important;
        background-color: white;
    }

    /* Form Controls */
    .form-control {
        border-radius: 6px !important;
        padding: 10px 14px !important;
        border: 1px solid #dee2e6 !important;
        transition: var(--transition);
        height: auto !important;
    }

    .form-control:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15) !important;
    }

    input[readonly], input:disabled {
        background-color: var(--gray-light) !important;
        opacity: 0.8;
    }

    /* Buttons */
    .btn {
        border-radius: 6px !important;
        padding: 8px 16px !important;
        font-weight: 500 !important;
        letter-spacing: 0.3px !important;
        transition: var(--transition) !important;
        box-shadow: none !important;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
    }

    .btn-warning {
        background-color: var(--warning-color) !important;
        border-color: var(--warning-color) !important;
        color: white !important;
    }

    .btn-warning:hover {
        background-color: #e67e22 !important;
        border-color: #e67e22 !important;
    }

    /* Tables */
    .table {
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100% !important;
        margin-bottom: 1.5rem !important;
    }

    .table th {
        background-color: var(--gray-light) !important;
        color: #495057 !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        font-size: 0.75rem !important;
        letter-spacing: 0.5px !important;
        padding: 12px 15px !important;
    }

    .table td {
        padding: 12px 15px !important;
        vertical-align: middle !important;
        border-bottom: 1px solid var(--gray-medium) !important;
    }

    .table-bordered {
        border-radius: var(--border-radius) !important;
        overflow: hidden !important;
    }

    .table-bordered th:first-child,
    .table-bordered td:first-child {
        border-left: none !important;
    }

    .table-bordered th:last-child,
    .table-bordered td:last-child {
        border-right: none !important;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02) !important;
    }

    .table-borderless th,
    .table-borderless td {
        border: none !important;
    }

    /* Invoice Header Styling */
    .service_invoice_header {
        background-color: var(--primary-light);
        border-radius: var(--border-radius);
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid var(--primary-color);
    }

    .service_invoice_header .row {
        margin-bottom: 0.5rem;
    }

    .invoice-info-label {
        color: var(--primary-dark);
        font-weight: 500;
    }

    .invoice-info-value {
        font-weight: 600;
    }

    /* Summary Panel */
    .summary-panel {
        background-color: var(--gray-light);
        border-radius: var(--border-radius);
        padding: 1.25rem;
        height: 100%;
        border-left: 4px solid var(--primary-color);
    }

    .summary-panel table {
        margin-bottom: 0;
    }

    .summary-panel td:first-child {
        color: var(--primary-dark);
        font-weight: 500;
    }

    .summary-panel td:last-child {
        font-weight: 600;
        text-align: right;
    }

    /* Payment Form */
    .payment-form {
        background-color: var(--primary-light);
        border-radius: var(--border-radius);
        padding: 1.25rem;
        height: 100%;
        border-left: 4px solid var(--primary-color);
    }

    .payment-form table {
        margin-bottom: 0;
    }

    .payment-form th {
        color: var(--primary-dark);
        font-weight: 600;
        background-color: transparent !important;
        padding: 10px 5px !important;
    }

    .payment-form td {
        padding: 10px 5px !important;
    }

    /* Section Titles */
    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--primary-light);
    }

    /* Status Badges */
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius);
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .status-paid {
        background-color: var(--success-color);
        color: white;
    }

    .status-due {
        background-color: var(--danger-color);
        color: white;
    }

    /* Due History Section */
    .due-history-section {
        margin-top: 2rem;
        margin-bottom: 2rem;
    }

    .due-history-section h5 {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--primary-light);
    }

    /* Amount Highlighting */
    .amount-highlight {
        font-weight: 700;
        color: var(--primary-color);
    }

    .amount-due {
        font-weight: 700;
        color: var(--danger-color);
    }

    .amount-paid {
        font-weight: 700;
        color: var(--success-color);
    }
</style>
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <div class="row">
                <div class="col-sm-6">
                    <h3>Due Payment</h3>
                </div>
            </div>
        @endslot
    @endcomponent

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fa fa-credit-card me-2"></i> Process Due Payment
                            </h5>
                            <div>
                                @if ($outletInvoice->due_amount > 0)
                                    <span class="status-badge status-due">Due: {{ $outletInvoice->due_amount }}</span>
                                @else
                                    <span class="status-badge status-paid">Paid</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        {!! Form::open(['route' => ['paymentDue'], 'method' => 'POST', 'class' => 'needs-validation']) !!}

                        <!-- Invoice Header Information -->
                        <div class="service_invoice_header">
                            <div class="row">
                                <div class="col-md-4">
                                    <span class="invoice-info-label">Invoice No:</span>
                                    <span class="invoice-info-value">{{ $outletInvoice->id }}</span>
                                </div>

                                <div class="col-md-4">
                                    <span class="invoice-info-label">Purchase Date:</span>
                                    <span class="invoice-info-value">{{ \Carbon\Carbon::parse($outletInvoice->sale_date)->format('d-m-Y') }}</span>
                                </div>
                                
                                <div class="col-md-4">
                                    <span class="invoice-info-label">Sale By:</span>
                                    <span class="invoice-info-value">{{ Auth::user()->name }}</span>
                                </div>
                            </div>

                            <input type="hidden" name="outlet_invoice_id" value="{{ $outletInvoice->id }}">
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <span class="invoice-info-label">Customer Mobile:</span>
                                    <span class="invoice-info-value">
                                        @if ($outletInvoice->customer_id == null)
                                            N/A
                                        @elseif ($outletInvoice->customer_id != null)
                                            {{ $outletInvoice->customer->mobile }}
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="col-md-4">
                                    <span class="invoice-info-label">Customer Name:</span>
                                    <span class="invoice-info-value">
                                        @if ($outletInvoice->customer_id == null)
                                            Walking Customer
                                        @elseif ($outletInvoice->customer_id != null)
                                            {{ $outletInvoice->customer->name }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="section-title">
                            <i class="fa fa-shopping-cart me-2"></i> Invoice Items
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5%">SL</th>
                                        <th width="35%">Name Of Medicine</th>
                                        <th width="10%">Size</th>
                                        <th width="10%">Quantity</th>
                                        <th width="12%">Price</th>
                                        <th width="12%">Discount</th>
                                        <th width="16%">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($outletInvoiceDetails as $data)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $data->medicine->medicine_name }}</td>
                                            <td>{{ $data->size }}</td>
                                            <td class="text-center">{{ $data->quantity }}</td>
                                            <td class="text-end">{{ $data->rate }}</td>
                                            <td class="text-end">{{ $data->discount }}</td>
                                            <td class="text-end amount-highlight">{{ ($data->quantity*$data->rate) - $data->discount}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Due Payment History -->
                        @if (count($saledetails) != 0)
                            <div class="due-history-section">
                                <h5><i class="fa fa-history me-2"></i> Payment History</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="20%">Invoice Id</th>
                                                <th width="30%">Customer Name</th>
                                                <th width="17%">Amount</th>
                                                <th width="17%">Paid</th>
                                                <th width="16%">Due</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($saledetails as $data)
                                                <tr>
                                                    <td>{{ $data->invoice_id }}</td>
                                                    <td>{{ $data->customer->name }}</td>
                                                    <td class="text-end">{{ $data->amount }}</td>
                                                    <td class="text-end amount-paid">{{ $data->pay }}</td>
                                                    <td class="text-end amount-due">{{ $data->due }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <div class="row mt-4">
                            <!-- Payment Form -->
                            <div class="col-md-7">
                                @if ($outletInvoice->due_amount > 0)
                                    <div class="payment-form">
                                        <h5 class="section-title mb-4">
                                            <i class="fa fa-money-bill-wave me-2"></i> Process Payment
                                        </h5>
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="35%">Payable Amount</th>
                                                <td width="65%">
                                                    {{ Form::number('due_amount', $outletInvoice->due_amount, ['class' => 'form-control', 'readonly', 'id' => 'due_amount']) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Flat Discount</th>
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-text">৳</span>
                                                        <input class="form-control" type="number" name="discount" id="discount" value="" 
                                                            onkeyup="prevent_paid_amount()" placeholder="0.00"
                                                            onchange="prevent_paid_amount()">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Grand Total</th>
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-text">৳</span>
                                                        <input class="form-control fw-bold text-primary" type="number" name="total" id="total" value="0" step="any" readonly>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Pay Now</th>
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-text">৳</span>
                                                        <input class="form-control fw-bold text-success" type="number" name="paid_amount" id="paid_amount" value="" step="any" placeholder="0.00" required>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Due After Payment</th>
                                                <td>
                                                    <div class="input-group">
                                                        <span class="input-group-text">৳</span>
                                                        <input class="form-control fw-bold text-danger" type="number" name="due" id="due" value="0"
                                                            onkeyup="prevent_due_amount()" placeholder="0.00" step="any"
                                                            onchange="prevent_due_amount()" readonly>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Payment Method</th>
                                                <td>
                                                    {{ Form::select('payment_method_id', $payment, null, [
                                                        'class' => 'form-control', 
                                                        'required',
                                                        'placeholder' => 'Select Payment Method'
                                                    ]) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" class="text-end">
                                                    {{ Form::button('<i class="fa fa-check-circle me-1"></i> Process Payment', [
                                                        'type' => 'submit', 
                                                        'class' => 'btn btn-warning'
                                                    ]) }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="status-badge status-paid mb-3" style="font-size: 1.5rem;">PAID</div>
                                        <p class="mb-0">This invoice has been fully paid.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Invoice Summary -->
                            <div class="col-md-5">
                                <div class="summary-panel">
                                    <h5 class="section-title mb-4">
                                        <i class="fa fa-file-invoice-dollar me-2"></i> Invoice Summary
                                    </h5>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="50%">Sub Total</td>
                                            <td width="50%" class="text-end">৳ {{ $outletInvoice->sub_total }}</td>
                                        </tr>
                                        <tr>
                                            <td>VAT</td>
                                            <td class="text-end">৳ {{ $outletInvoice->vat }}</td>
                                        </tr>
                                        <tr>
                                            <td>Discount</td>
                                            <td class="text-end">৳ {{ $outletInvoice->total_discount }}</td>
                                        </tr>
                                        <tr>
                                            <td>Delivery Charge</td>
                                            <td class="text-end">৳ {{ $outletInvoice->delivery_charge }}</td>
                                        </tr>
                                        <tr class="border-top">
                                            <td class="fw-bold">Grand Total</td>
                                            <td class="text-end fw-bold">৳ {{ $outletInvoice->grand_total + $outletInvoice->delivery_charge }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-success">Paid Amount</td>
                                            <td class="text-end text-success fw-bold">৳ {{$outletInvoice->delivery_charge > 0 ? 
                                                ($outletInvoice->total_with_charge - $outletInvoice->due_amount) : 
                                                ($outletInvoice->grand_total - $outletInvoice->due_amount)}}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-danger">Due Amount</td>
                                            <td class="text-end text-danger fw-bold">৳ {{ $outletInvoice->due_amount }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
<script type="text/javascript">
    function prevent_paid_amount() {
        var discount = $("#discount").val();
        var due_amount = $("#due_amount").val();
        if (parseFloat(discount) > parseFloat(due_amount)) {
            Swal.fire({
                icon: 'warning',
                title: 'Discount Limit Exceeded',
                text: 'You cannot discount more than the due amount.',
                confirmButtonColor: '#4361ee'
            });
            $("#discount").val("");
        }
    }

    $(document).ready(function () {
        let subTotal = $("#due_amount").val();
        $("#total").val(subTotal);

        $("#discount").bind('keypress keyup keydown mouseup', function () {
            let discount = $(this).val() || 0;
            let subTotal = $("#due_amount").val();
            let finalpay = (subTotal - discount);
            finalpay = finalpay.toFixed(2);
            $("#total").val(finalpay);
            $("#due").val(finalpay);
            
            // Update paid amount field if it has a value
            let paidAmount = $("#paid_amount").val() || 0;
            if (paidAmount > 0) {
                let finaldue = (finalpay - paidAmount);
                finaldue = finaldue.toFixed(2);
                $("#due").val(finaldue);
            }
        });
        
        $("#paid_amount").bind('keypress keyup keydown mouseup', function () {
            let total = $(this).val() || 0;
            let subTotal = $("#total").val();
            let finaldue = (subTotal - total);
            finaldue = finaldue.toFixed(2);
            $("#due").val(finaldue);
            
            // Validate that paid amount is not more than total
            if (parseFloat(total) > parseFloat(subTotal)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Payment Exceeds Total',
                    text: 'Paid amount cannot be more than the total amount.',
                    confirmButtonColor: '#4361ee'
                });
                $(this).val(subTotal);
                $("#due").val(0);
            }
        });
    });
</script>

@if (Session()->get('success'))
<script type="text/javascript">
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