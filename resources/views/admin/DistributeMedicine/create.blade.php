@extends('layouts.admin.master')
@section('title')
    Distribute Products to Outlet
@endsection

@push('css')
<style>
    /* Main container */
    .container-fluid {
        padding: 1.5rem;
    }
    
    /* Card styling */
    .card {
        border: none;
        border-radius: 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }
    
    /* Section headers */
    .section-header {
        background-color: #234E52; /* Dark teal */
        color: white;
        padding: 0.8rem 1.2rem;
        font-weight: 500;
        font-size: 1.1rem;
        border: none;
        margin: 0;
    }
    
    .sub-section-header {
        background-color: #E2E8F0; /* Lighter gray */
        color: #2D3748;
        padding: 0.8rem 1.2rem;
        font-weight: 500;
        border: none;
        margin: 0;
    }
    
    /* Form inputs */
    .form-control, .form-select {
        border-radius: 0;
        padding: 0.6rem 0.8rem;
        border: 1px solid #CBD5E0;
        height: 42px;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #234E52;
        box-shadow: 0 0 0 0.15rem rgba(35, 78, 82, 0.25);
    }
    
    /* Labels */
    .form-label {
        font-weight: 500;
        color: #2D3748;
        margin-bottom: 0.5rem;
    }
    
    /* Form sections */
    .form-section {
        background-color: #FFF;
        padding: 1.5rem;
        border: 1px solid #E2E8F0;
    }
    
    /* Buttons */
    .btn {
        border-radius: 0;
        padding: 0.6rem 1.2rem;
        font-weight: 500;
        height: 42px;
    }
    
    .btn-primary {
        background-color: #234E52;
        border-color: #234E52;
    }
    
    .btn-primary:hover {
        background-color: #1D3E42;
        border-color: #1D3E42;
    }
    
    .btn-success {
        background-color: #1A4731;
        border-color: #1A4731;
    }
    
    .btn-success:hover {
        background-color: #15362A;
        border-color: #15362A;
    }
    
    /* Table */
    .table {
        margin-bottom: 0;
    }
    
    .table th {
        background-color: #F7FAFC;
        font-weight: 600;
        padding: 0.75rem;
        border-bottom: 2px solid #E2E8F0;
    }
    
    .table td {
        vertical-align: middle;
        padding: 0.75rem;
    }
    
    /* Product search section */
    .product-search-section {
        background-color: #F7FAFC;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid #E2E8F0;
    }
    
    /* Delete button */
    .delete-btn {
        color: #E53E3E;
        background: transparent;
        border: none;
        font-size: 1.2rem;
        padding: 0.3rem 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .delete-btn:hover {
        color: #C53030;
    }
    
    /* Stock badge */
    .badge-stock {
        padding: 0.3rem 0.5rem;
        font-weight: 500;
        border-radius: 0.25rem;
    }
    
    .badge-stock-high {
        background-color: #C6F6D5;
        color: #276749;
    }
    
    .badge-stock-medium {
        background-color: #FEFCBF;
        color: #975A16;
    }
    
    .badge-stock-low {
        background-color: #FED7D7;
        color: #9B2C2C;
    }
    
    /* Form spacing */
    .row {
        margin-bottom: 0;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    /* Table responsive */
    .table-responsive {
        border: 1px solid #E2E8F0;
    }
    
    /* Error text */
    .invalid-feedback {
        display: block;
        color: #E53E3E;
        margin-top: 0.25rem;
    }
    
    /* Utility classes */
    .mb-0 { margin-bottom: 0 !important; }
    .mb-2 { margin-bottom: 0.5rem !important; }
    .mb-3 { margin-bottom: 1rem !important; }
    .mb-4 { margin-bottom: 1.5rem !important; }
    
    /* Fix for Select2 to match form styling */
    .select2-container--default .select2-selection--single {
        height: 42px !important;
        border-radius: 0 !important;
        border: 1px solid #CBD5E0 !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 42px !important;
        padding-left: 0.8rem !important;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 5px !important;
    }
</style>
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <div class="d-flex align-items-center justify-content-between">
                <h3 class="mb-0">Distribute Products to Outlet</h3>
                <a href="{{ route('distribute-medicine.index') }}" class="btn btn-primary">
                    <i class="fa fa-arrow-left me-2"></i>Back to List
                </a>
            </div>
        @endslot
    @endcomponent

    <div class="container-fluid px-0">
        {!! Form::open(['route' => 'distribute-medicine.store', 'class' => 'needs-validation', 'novalidate'=> '', 'autocomplete' => 'off', 'id' => 'product_purchase']) !!}
        
        <!-- Information Sections -->
        <div class="row mb-4">
            <!-- Source Information -->
            <div class="col-md-6">
                <h5 class="section-header mb-0">Source Information</h5>
                <div class="form-section">
                    <div class="mb-3">
                        <label for="warehouse" class="form-label">Warehouse <span class="text-danger">*</span></label>
                        {{ Form::select('warehouse_id', $warehouse, null, ['class' => 'form-select', 'required', 'placeholder' => 'Select Warehouse', 'id' => 'warehouse']) }}
                        <div class="invalid-feedback">Please select a warehouse</div>
                        @error('warehouse_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-0">
                        <label for="purchase_date" class="form-label">Distribution Date <span class="text-danger">*</span></label>
                        <input class="form-control datepicker-here" type="text" data-language="en" name="purchase_date" value="{{ Carbon\Carbon::today()->format('d-m-Y') }}" required>
                    </div>
                </div>
            </div>
            
            <!-- Destination Information -->
            <div class="col-md-6">
                <h5 class="section-header mb-0">Destination Information</h5>
                <div class="form-section">
                    <div class="mb-3">
                        <label for="outlet_id" class="form-label">Outlet <span class="text-danger">*</span></label>
                        {{ Form::select('outlet_id', $outlets, null, ['class' => 'form-select', 'required', 'placeholder' => 'Select Outlet']) }}
                        <div class="invalid-feedback">Please select an outlet</div>
                        @error('outlet_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-0">
                        <label for="remarks" class="form-label">Remarks</label>
                        {!! Form::text('remarks', null, ['class'=>'form-control', 'id' => 'remarks', 'placeholder'=>'Enter remarks or additional notes']) !!}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Distribution Section -->
        <h5 class="section-header mb-0">Product Distribution</h5>
        
        <!-- Product Search Section -->
        <div class="product-search-section">
            <div class="row">
                <div class="col-12">
                    <label for="medicine_id" class="form-label">Search Product <span class="text-danger">*</span></label>
                    {{ Form::select('medicine_id', [], null, ['class' => 'form-select', 'placeholder' => 'Type to search products', 'id' => 'medicine_id']) }}
                    <div class="text-muted small mt-1">First select a warehouse to search available products</div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 col-md-4 col-lg-3">
                    <button type="button" class="btn btn-primary w-100 addProductRow" id="addProductRow">
                        <i class="fa fa-plus-circle me-2"></i>Add Product
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Products Table -->
        <div class="table-responsive mb-3">
            <table class="table table-hover" id="purchaseTable">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Barcode</th>
                        <th>Size</th>
                        <th width="100">Quantity</th>
                        <th width="80">Stock</th>
                        <th>MSRP</th>
                        <th>MRP</th>
                        <th>Total</th>
                        <th width="60">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Products will be added here dynamically -->
                    <tr id="noProductRow">
                        <td colspan="9" class="text-center py-4 text-muted">
                            <i class="fa fa-box-open fa-2x mb-2"></i>
                            <p>No products added yet. Select a warehouse and search for products to add.</p>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="7" class="text-end fw-bold">Total:</td>
                        <td colspan="2">
                            <span id="total-amount" class="fw-bold">0.00</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <!-- Save Button -->
        <div class="text-end mb-4">
            <button type="submit" class="btn btn-success" id="save_purchase">
                <i class="fa fa-save me-2"></i>Save Distribution
            </button>
        </div>
        
        {!! Form::close() !!}
    </div>
@endsection

@push('scripts')
<script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
<script src="{{ asset('assets/js/outletstock.js') }}"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2 for better dropdowns
        $("#warehouse, #outlet_id").select2({
            placeholder: "Select an option",
            allowClear: true,
            width: '100%'
        });
        
        // Initialize datepicker
        $('.datepicker-here').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true
        });
        
        // Warehouse change handler
        let warehouse_id = '';
        $("#warehouse").on('change', function() {
            warehouse_id = $(this).val();
            
            if (warehouse_id) {
                // Initialize product selector with Select2 and AJAX
                $("#medicine_id").select2({
                    placeholder: "Type to search products",
                    allowClear: true,
                    width: '100%',
                    ajax: {
                        url: `{!! url('get-warehouse-Stock') !!}/${warehouse_id}`,
                        type: "get",
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                search: params.term,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            };
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            };
                        },
                        cache: true
                    }
                });
            } else {
                $("#medicine_id").select2({
                    placeholder: "First select a warehouse",
                    width: '100%'
                }).html('').prop('disabled', true);
            }
        });
        
        // Add product row handler
        let medicine_id = '';
        $("#medicine_id").on('change', function() {
            medicine_id = $(this).val();
        });
        
        $("#addProductRow").on('click', function() {
            if (!warehouse_id) {
                showNotification('warning', 'Please select a warehouse first');
                return;
            }
            
            if (!medicine_id) {
                showNotification('warning', 'Please select a product to add');
                return;
            }
            
            // Remove no products message if visible
            $("#noProductRow").hide();
            
            // Get product details and add to table
            $.ajax({
                url: `{!! url('get-medicine-details-warehouse') !!}/${medicine_id}/${warehouse_id}`,
                type: "GET",
                dataType: "json",
                beforeSend: function() {
                    $("#addProductRow").html('<i class="fa fa-spinner fa-spin me-2"></i>Loading...').prop('disabled', true);
                },
                success: function(data) {
                    if (data) {
                        // Check if product with same ID AND size already exists in table
                        let exists = false;
                        $(".product-row").each(function() {
                            const existingProductId = $(this).find(".product-id").val();
                            const existingProductSize = $(this).find("input[name='size[]']").val();
                            
                            if (existingProductId == data.medicine_id && existingProductSize == data.size) {
                                exists = true;
                                showNotification('info', 'This product with the same size is already added to the list');
                                return false; // break the loop
                            }
                        });
                        
                        if (!exists) {
                            addProductToTable(data);
                            calculateTotals();
                        }
                    } else {
                        showNotification('error', 'Product details not found!');
                    }
                },
                error: function() {
                    showNotification('error', 'Failed to fetch product details');
                },
                complete: function() {
                    $("#addProductRow").html('<i class="fa fa-plus-circle me-2"></i>Add Product').prop('disabled', false);
                    // Clear selection
                    $("#medicine_id").val(null).trigger('change');
                    medicine_id = '';
                }
            });
        });
        
        // Function to add product to table
        function addProductToTable(data) {
            let stockClass = data.quantity > 10 ? 'badge-stock-high' : (data.quantity > 5 ? 'badge-stock-medium' : 'badge-stock-low');
            
            let newRow = `
                <tr class="product-row">
                    <td>
                        <div class="fw-bold">${data.medicine_name}</div>
                        <input type="hidden" name="product_id[]" class="product-id" value="${data.medicine_id}">
                        <input type="hidden" name="product_name[]" value="${data.medicine_name}">
                        <input type="hidden" name="stock_id[]" value="${data.id}">
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">${data.barcode_text}</span>
                        <input type="hidden" name="barcode[]" value="${data.barcode_text}">
                    </td>
                    <td>
                        <span>${data.size || 'N/A'}</span>
                        <input type="hidden" name="size[]" value="${data.size}">
                    </td>
                    <td>
                        <input type="number" name="quantity[]" class="form-control qty" min="1" max="${data.quantity}" value="1" required>
                    </td>
                    <td>
                        <span class="badge ${stockClass}">${data.quantity}</span>
                        <input type="hidden" name="stock[]" class="stock" value="${data.quantity}">
                    </td>
                    <td>
                        <span>${data.purchase_price}</span>
                        <input type="hidden" name="box_manu_price[]" class="manu_price" value="${data.purchase_price}">
                    </td>
                    <td>
                        <span>${data.price}</span>
                        <input type="hidden" name="box_mrp[]" class="price" value="${data.price}">
                    </td>
                    <td>
                        <span class="total">${data.price}</span>
                    </td>
                    <td class="text-center">
                        <button type="button" class="delete-btn delete" title="Remove Item">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            $("#purchaseTable tbody").append(newRow);
            $("#noProductRow").hide();
            
            // Add event listeners for the new row
            initRowEvents();
        }
        
        // Initialize row events for quantity changes and delete
        function initRowEvents() {
            // Quantity change handler
            $(".qty").off('input').on('input', function() {
                let qty = parseInt($(this).val()) || 0;
                let max = parseInt($(this).attr('max')) || 0;
                
                // Validate quantity
                if (qty <= 0) {
                    $(this).val(1);
                    qty = 1;
                } else if (qty > max) {
                    $(this).val(max);
                    qty = max;
                    showNotification('warning', 'Quantity cannot exceed available stock');
                }
                
                // Calculate row total
                let price = parseFloat($(this).closest('tr').find('.price').val()) || 0;
                let total = qty * price;
                $(this).closest('tr').find('.total').text(total.toFixed(2));
                
                // Recalculate all totals
                calculateTotals();
            });
            
            // Delete button handler
            $(".delete").off('click').on('click', function() {
                $(this).closest('tr').remove();
                calculateTotals();
                
                // Show no products message if no rows left
                if ($("#purchaseTable tbody tr.product-row").length === 0) {
                    $("#noProductRow").show();
                }
            });
        }
        
        // Calculate totals for all products
        function calculateTotals() {
            let totalAmount = 0;
            
            $(".total").each(function() {
                totalAmount += parseFloat($(this).text()) || 0;
            });
            
            $("#total-amount").text(totalAmount.toFixed(2));
        }
        
        // Form submission validation
        $("#product_purchase").on('submit', function(e) {
            if ($("#purchaseTable tbody tr.product-row").length === 0) {
                e.preventDefault();
                showNotification('error', 'Please add at least one product to distribute');
            }
        });
        
        // Notification helper
        function showNotification(type, message) {
            $.notify({
                icon: type === 'success' ? 'fa fa-check' : 'fa fa-bell',
                message: message
            }, {
                type: type === 'success' ? 'success' : (type === 'error' ? 'danger' : type),
                allow_dismiss: true,
                delay: 3000,
                z_index: 9999,
                placement: {
                    from: 'top',
                    align: 'right'
                },
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                }
            });
        }
    });
</script>

@if (Session()->get('success'))
<script>
    $.notify({
        icon: 'fa fa-check',
        message: '{{ Session()->get('success') }}'
    }, {
        type: 'success',
        allow_dismiss: true,
        delay: 3000,
        z_index: 9999,
        placement: {
            from: 'top',
            align: 'right'
        },
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        }
    });
</script>
@endif

@if (Session()->get('error'))
<script>
    $.notify({
        icon: 'fa fa-exclamation-triangle',
        message: '{{ Session()->get('error') }}'
    }, {
        type: 'danger',
        allow_dismiss: true,
        delay: 3000,
        z_index: 9999,
        placement: {
            from: 'top',
            align: 'right'
        },
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        }
    });
</script>
@endif
@endpush