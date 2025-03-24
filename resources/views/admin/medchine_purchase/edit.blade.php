@extends('layouts.admin.master')
@section('title')Due Payment
@endsection
@push('css')
<style>
/* Blade Style Purchase Form */

/* Global elements */
body {
  background-color: #f7f9fc;
  color: #333;
  font-family: 'Inter', 'Segoe UI', Roboto, sans-serif;
}

/* Card styling */
.card {
  border-radius: 0;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
  margin-bottom: 24px;
  border: none;
  background: #ffffff;
}

.card-header {
  background: #000 !important;
  color: white;
  font-weight: 500;
  border-radius: 0 !important;
  padding: 16px 20px;
  text-transform: uppercase;
  letter-spacing: 1px;
  font-size: 14px;
}

.card-body {
  padding: 24px;
}

/* Info cards */
.info-card {
  background-color: #f9f9f9;
  border-left: 3px solid #000;
  padding: 15px;
  margin-bottom: 15px;
}

.info-label {
  font-weight: 600;
  text-transform: uppercase;
  font-size: 11px;
  letter-spacing: 0.5px;
  color: #666;
  margin-bottom: 5px;
}

.info-value {
  font-weight: 600;
  font-size: 15px;
  color: #333;
}

/* Form styling */
.form-control {
  border-radius: 0;
  padding: 12px 15px;
  border: 1px solid #e0e0e0;
  transition: all 0.2s ease;
  height: auto;
  background-color: #f9f9f9;
}

.form-control:focus {
  border-color: #000;
  box-shadow: none;
  background-color: #fff;
  border-left: 3px solid #000;
}

/* Table styling */
.table {
  border: none;
  width: 100%;
}

.table th {
  background-color: #000;
  color: #fff;
  padding: 12px 15px;
  font-weight: 500;
  text-transform: uppercase;
  font-size: 12px;
  letter-spacing: 0.5px;
  border: none;
}

.table td {
  padding: 12px 15px;
  vertical-align: middle;
  border: 1px solid #eee;
}

/* Table without borders */
.table-clean {
  border: none;
}

.table-clean th, .table-clean td {
  border: none;
  padding: 10px 15px;
  background: transparent;
}

.table-clean tr {
  border-bottom: 1px solid #f0f0f0;
}

.table-clean tr:last-child {
  border-bottom: none;
}

/* Summary box */
.summary-box {
  background-color: #f9f9f9;
  border: 1px solid #e0e0e0;
  padding: 20px;
}

.summary-box table {
  margin-bottom: 0;
}

.summary-title {
  font-weight: 600;
  text-transform: uppercase;
  font-size: 14px;
  letter-spacing: 0.5px;
  margin-bottom: 15px;
  padding-bottom: 10px;
  border-bottom: 2px solid #000;
}

/* Button styling */
.btn {
  border-radius: 0;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
  padding: 10px 25px;
  transition: all 0.2s ease;
  box-shadow: none;
}

.btn-warning {
  background: #1a1a1a;
  color: white;
  border-color: #1a1a1a;
}

.btn-warning:hover {
  background: #333;
  border-color: #333;
  color: white;
}

.btn-success, .btn-danger {
  border-radius: 0;
  font-size: 12px;
  padding: 7px 12px;
}

.btn-success {
  background: #1a1a1a;
  border-color: #1a1a1a;
}

.btn-success:hover {
  background: #333;
  border-color: #333;
}

.btn-danger {
  background: #333;
  border-color: #333;
}

.btn-danger:hover {
  background: #555;
  border-color: #555;
}

/* Animation for buttons */
.btn {
  position: relative;
  overflow: hidden;
}

.btn:after {
  content: '';
  position: absolute;
  width: 100%;
  height: 100%;
  top: 0;
  left: -100%;
  background: rgba(255,255,255,0.2);
  transition: all 0.3s ease;
}

.btn:hover:after {
  left: 100%;
}

/* Custom validations */
.invalid-feedback2 {
  display: block;
  color: #ff0000;
  font-weight: 500;
  font-size: 12px;
  margin-top: 5px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

/* Blade-box styles */
.blade-box {
  position: relative;
}

.blade-box:after {
  content: '';
  position: absolute;
  left: 0;
  bottom: -5px;
  height: 2px;
  width: 100%;
  background: #000;
}

/* Paid badge */
.paid {
  display: inline-block;
  background: #1a1a1a;
  color: white;
  padding: 8px 25px;
  font-size: 18px;
  text-transform: uppercase;
  letter-spacing: 2px;
  font-weight: 600;
  transform: rotate(-5deg);
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  margin-top: 20px;
}

/* Total values */
.total-row td {
  background-color: #f9f9f9;
  font-weight: 600;
}

.grand-total {
  font-size: 16px;
  font-weight: 700;
}

.payment-form-container {
  background-color: #f9f9f9;
  border-left: 3px solid #000;
  padding: 20px;
}

.payment-form-title {
  font-weight: 600;
  text-transform: uppercase;
  font-size: 14px;
  letter-spacing: 0.5px;
  margin-bottom: 15px;
  padding-bottom: 10px;
  border-bottom: 2px solid #000;
}
</style>
@endpush
@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="blade-box">DUE PAYMENT</h3>
                </div>
            </div>
		@endslot

        @slot('button')
            <a href="{{ route('product-purchase.index') }}" class="btn btn-primary btn">
                <i class="fa fa-arrow-left mr-2"></i> BACK
            </a>
        @endslot
	@endcomponent

    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <i class="fa fa-money mr-2"></i> PROCESS PAYMENT
            </div>

            <div class="card-body">
                {!! Form::open(['route' => ['product-purchase.update', $productPurchase->id], 'method' => 'PUT', 'class' => 'needs-validation', 'novalidate'=> '']) !!}

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label">INVOICE ID</div>
                            <div class="info-value">{{ $productPurchase->id }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label">INVOICE NO</div>
                            <div class="info-value">{{ $productPurchase->invoice_no }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label">PURCHASE DATE</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($productPurchase->purchase_date)->format('d-m-Y') }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="info-card">
                            <div class="info-label">SUPPLIER NAME</div>
                            <div class="info-value">
                                @if ($productPurchase->supplier_id == null)
                                    N/A
                                @else
                                    {{ $productPurchase->supplier->supplier_name }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive mt-4">
                    <table class="table">
                        <thead>
                            <tr>
                                <th width="5%">SL</th>
                                <th width="35%">PRODUCT NAME</th>
                                <th width="15%">QUANTITY</th>
                                <th width="15%">PRICE</th>
                                <th width="15%">AMOUNT</th>
                                <th width="15%">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productPurchaseDetails as $data)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $data->medicine_name }}</td>
                                    <td>{{ $data->quantity }}</td>
                                    <td>{{ $data->manufacturer_price }}</td>
                                    <td>{{ $data->quantity * $data->manufacturer_price }}</td>
                                    <td>
                                        <a href="{{ route('edit-purchase', $data->id) }}" class="btn btn-success btn-sm">
                                            <i class="fa fa-pencil-square-o"></i>
                                        </a>
                                        <a href="{{ route('purchase-delete', $data->id) }}" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row mt-4">
                    <div class="col-md-7">
                        @if ($productPurchase->due_amount > 0)
                            <div class="payment-form-container">
                                <div class="payment-form-title">Payment Information</div>
                                <div class="form-group row mb-3">
                                    <label class="col-md-3 col-form-label">PAYABLE AMOUNT</label>
                                    <div class="col-md-9">
                                        {{ Form::number('due_amount', $productPurchase->due_amount, ['class' => 'form-control', 'readonly']) }}
                                    </div>
                                </div>
                                <div class="form-group row mb-3">
                                    <label class="col-md-3 col-form-label">PAY NOW</label>
                                    <div class="col-md-9">
                                        {{ Form::number('paid_amount', $productPurchase->due_amount, ['class' => 'form-control', 'required']) }}
                                        @error('paid_amount')
                                            <div class="invalid-feedback2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-9 offset-md-3">
                                        {{ Form::button('<i class="fa fa-save mr-1"></i> UPDATE PAYMENT', ['type' => 'submit', 'class' => 'btn btn-warning']) }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center mt-4">
                                <h2 class="paid">PAID</h2>
                            </div>
                        @endif
                    </div>
                    
                    <div class="col-md-5">
                        <div class="summary-box">
                            <div class="summary-title">PURCHASE SUMMARY</div>
                            <table class="table table-clean">
                                <tr>
                                    <td width="50%">Sub Total</td>
                                    <td width="50%" class="text-right">{{ $productPurchase->sub_total }}</td>
                                </tr>
                                <tr>
                                    <td>VAT</td>
                                    <td class="text-right">{{ $productPurchase->vat }}</td>
                                </tr>
                                <tr>
                                    <td>Discount</td>
                                    <td class="text-right">{{ $productPurchase->total_discount }}</td>
                                </tr>
                                <tr class="total-row">
                                    <td>Grand Total</td>
                                    <td class="text-right grand-total">{{ $productPurchase->grand_total }}</td>
                                </tr>
                                <tr>
                                    <td>Paid Amount</td>
                                    <td class="text-right">{{ $productPurchase->paid_amount }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Due Amount</strong></td>
                                    <td class="text-right"><strong>{{ $productPurchase->due_amount }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                {!! Form::close() !!}
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