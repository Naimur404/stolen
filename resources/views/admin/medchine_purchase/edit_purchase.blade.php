@extends('layouts.admin.master')
@section('title')Edit Add Product To Warehouse
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
.invalid-feedback {
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
</style>
@endpush
@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="blade-box">EDIT PURCHASE</h3>
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
                <i class="fa fa-pencil mr-2"></i> EDIT PRODUCT PURCHASE
            </div>
            <div class="card-body">
                {!! Form::open(['route' => 'purchase-update', 'method' => 'POST', 'class' => 'needs-validation', 'novalidate'=> '']) !!}
                
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

                <!-- Hidden Fields -->
                <input type="hidden" name="pid" value="{{ $productPurchase->id }}">
                <input type="hidden" name="pdid" value="{{ $data->id }}">

                <div class="table-responsive mt-4">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>PRODUCT NAME</th>
                                <th>SIZE</th>
                                <th>QUANTITY</th>
                                <th>PRICE</th>
                                <th>MRP</th>
                                <th>TOTAL AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="product-name">{{ $data->medicine_name }}</td>
                                <td>
                                    <input class="form-control" type="text" name="size" value="{{ $data->size }}" required>
                                </td>
                                <td>
                                    <input type="number" name="qty" id="qty" value="{{ $data->quantity }}" class="form-control" required>
                                </td>
                                <td>
                                    <input type="number" name="price" id="price" value="{{ $data->manufacturer_price }}" step="any" class="form-control" required>
                                </td>
                                <td>
                                    <input type="number" name="box_mrp" id="box_mrp" value="{{ $data->box_mrp }}" step="any" class="form-control" required>
                                </td>
                                <td>
                                    <input type="number" name="final" id="final" value="{{ $data->quantity*$data->manufacturer_price }}" class="form-control" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="form-group mt-4 text-right">
                    {{ Form::button('<i class="fa fa-save mr-1"></i> UPDATE', ['type' => 'submit', 'class' => 'btn btn-warning']) }}
                </div>
                
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            // Update total when quantity or price changes
            $("#qty, #price").bind('keypress keyup keydown mouseup', function () {
                let qty = $("#qty").val() || 0;
                let price = $("#price").val() || 0;
                let total = qty * price;
                $("#final").val(total.toFixed(2));
            });
        });
    </script>

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