@extends('layouts.admin.master')
@section('title')Edit Distribute Product to Outlet
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
    
    .btn-danger {
        background-color: #9B2C2C;
        border-color: #9B2C2C;
    }
    
    .btn-danger:hover {
        background-color: #822727;
        border-color: #822727;
    }
    
    .delete-link {
        color: white;
        text-decoration: none;
    }
    
    .delete-link:hover {
        color: white;
        text-decoration: none;
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
        background-color:rgb(13, 109, 43);
        color: #276749;
    }
    
    .badge-stock-medium {
        background-color:rgb(163, 161, 109);
        color: #975A16;
    }
    
    .badge-stock-low {
        background-color:rgb(217, 83, 83);
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
                <h3 class="mb-0">Edit Distribute Products to Outlet</h3>
                <a href="{{ route('distribute-medicine.index') }}" class="btn btn-primary">
                    <i class="fa fa-arrow-left me-2"></i>Back to List
                </a>
            </div>
		@endslot
	@endcomponent

    <div class="container-fluid px-0">
        {!! Form::open(['route' => ['distribute-medicine.update', $data->id], 'method' => 'PUT', 'class' => 'needs-validation', 'novalidate'=> '', 'autocomplete' => 'off', 'id' => 'product_purchase']) !!}
        
        <!-- Information Sections -->
        <div class="row mb-4">
            <!-- Source Information -->
            <div class="col-md-6">
                <h5 class="section-header mb-0">Source Information</h5>
                <div class="form-section">
                    <div class="mb-3">
                        <label for="warehouse_id" class="form-label">Warehouse <span class="text-danger">*</span></label>
                        {{ Form::select('warehouse_id', $warehouse, $data->warehouse_id, ['class' => 'form-select', 'required', 'id' => 'warehouse_id']) }}
                        <div class="invalid-feedback">Please select a warehouse</div>
                        @error('warehouse_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-0">
                        <label for="purchase_date" class="form-label">Distribution Date <span class="text-danger">*</span></label>
                    <input class="form-control" type="date" data-language="en" name="purchase_date" value="{{ Carbon\Carbon::parse($data->date)->format('d-m-Y') }}" required>
                    </div>
                </div>
            </div>

        
            
            <!-- Destination Information -->
            <div class="col-md-6">
                <h5 class="section-header mb-0">Destination Information</h5>
                <div class="form-section">
                    <div class="mb-3">
                        <label for="outlet_id" class="form-label">Outlet <span class="text-danger">*</span></label>
                        @if(!is_null($data->outlet_id))
                            @php
                                $data2 = App\Models\Outlet::where('id',$data->outlet_id)->first();
                                $id = $data2->id;
                                $name = $data2->outlet_name
                            @endphp
                            {{ Form::select('outlet_id', [$id => $name], $id, ['class' => 'form-select', 'required', 'id' => 'supplier_id']) }}
                        @else
                            {{ Form::select('outlet_id', [], null, ['class' => 'form-select', 'placeholder' => 'Select Outlet', 'required', 'id' => 'supplier_id']) }}
                        @endif
                        <div class="invalid-feedback">Please select an outlet</div>
                        @error('outlet_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-0">
                        <label for="remarks" class="form-label">Remarks</label>
                        {!! Form::text('remarks', $data->remarks, ['class'=>'form-control', 'id' => 'remarks', 'placeholder'=>'Enter remarks or additional notes']) !!}
                        @error('remarks')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
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
                    <div class="text-muted small mt-1">You can add more products to this distribution</div>
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
                        <th>MRP</th>
                        <th width="80">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($medicinedetails as $details)
                    <tr class="item-row">
                        <td>
                            <div class="fw-bold">{{ $details->medicine_name }}</div>
                            <input class="form-control pr_id" type="hidden" name="product_id[]" value="{{ $details->medicine_id }}">
                            <input class="form-control stock_id" type="hidden" name="stock_id[]" value="{{ $details->warehouse_stock_id }}">
                            <input class="form-control product_name" type="hidden" name="product_name[]" value="{{ $details->medicine_name }}">
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $details->barcode_text }}</span>
                            <input class="form-control barcode" type="hidden" name="barcode[]" value="{{ $details->barcode_text }}">
                        </td>
                        <td>
                            <span>{{ $details->size }}</span>
                            <input class="form-control size" type="hidden" name="size[]" value="{{ $details->size }}">
                        </td>
                        <td>
                            <input class="form-control qty" type="number" name="quantity[]" min="1" value="{{ $details->quantity }}" required>
                        </td>
                        <td>
                            <span class="badge badge-stock-medium">N/A</span>
                            <input class="form-control" type="hidden" name="stock[]" value="">
                        </td>
                        <td>
                            <input class="form-control" name="box_mrp[]" type="number" step="any" value="{{ $details->rate }}" required>
                            <input class="form-control" name="create_date[]" type="hidden" value="{{ $details->create_date }}">
                        </td>
                        <td class="text-center">
                            <a href="{{ route('delete.medicineDistributeDetailDelete', [$details->medicine_id, $details->medicine_distribute_id]) }}" 
                               class="btn btn-sm btn-danger delete-link" 
                               onclick="return confirm('Are you sure you want to delete this item?')">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Save Button -->
        <div class="text-end mb-4">
            <button type="submit" class="btn btn-success" id="save_purchase">
                <i class="fa fa-save me-2"></i>Update Distribution
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
        $("#warehouse_id, #supplier_id").select2({
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
        
        // Initialize outlet selector with Select2 and AJAX
        $("#supplier_id").select2({
            ajax: {
                url: "{!! url('get-outlet') !!}",
                type: "get",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        search: params.term,
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
        
        // Handle warehouse selection and product search
        function initializeProductSearch(warehouse_id) {
            if (warehouse_id) {
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
                                _token: $('meta[name="csrf-token"]').attr('content'),
                                search: params.term,
                            };
                        },
                        processResults: function(response) {
                            return {
                                results: response
                            };
                        },
                        cache: true
                    }
                }).prop('disabled', false);
            } else {
                $("#medicine_id").select2({
                    placeholder: "First select a warehouse",
                    width: '100%'
                }).html('').prop('disabled', true);
            }
        }
        
        // Initialize product search on page load if warehouse is selected
        let warehouse_id = $("#warehouse_id").val();
        if (warehouse_id) {
            initializeProductSearch(warehouse_id);
        }
        
        // Update product search when warehouse changes
        $("#warehouse_id").on('change', function() {
            warehouse_id = $(this).val();
            initializeProductSearch(warehouse_id);
        });
        
        // Handle medicine selection
        let medicine_id = '';
        $("#medicine_id").on('change', function() {
            medicine_id = $(this).val();
        });
        
        // Initialize delete buttons for existing products
        $(document).on('click', '.delete-new-item', function(e) {
            e.preventDefault();
            $(this).closest('tr').remove();
        });
        
        /**
         * Check if a product already exists in the table based on product_id and size
         * @param {Object} productData - The product data to check
         * @return {Object} - {exists: boolean, row: jQuery object if found}
         */
        function checkProductExists(productData) {
            let result = {
                exists: false,
                row: null
            };
            
            $(".item-row").each(function() {
                const existingProductId = $(this).find(".pr_id").val();
                const existingProductSize = $(this).find(".size").val();
                
                // Check if product ID and size match
                if (existingProductId == productData.medicine_id && existingProductSize == productData.size) {
                    result.exists = true;
                    result.row = $(this);
                    return false; // Break the .each() loop
                }
            });
            
            return result;
        }
        
        // Add product row handler
        $("#addProductRow").on('click', function() {
            if (!warehouse_id) {
                showNotification('warning', 'Please select a warehouse first');
                return;
            }
            
            if (!medicine_id) {
                showNotification('warning', 'Please select a product to add');
                return;
            }
            
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
                        // Check if product with same ID AND size already exists
                        const duplicateCheck = checkProductExists(data);
                        
                        if (duplicateCheck.exists) {
                            // A duplicate was found - highlight the row
                            const row = duplicateCheck.row;
                            const productName = data.medicine_name;
                            
                            // Highlight the existing row temporarily
                            row.addClass('bg-warning');
                            setTimeout(function() {
                                row.removeClass('bg-warning');
                            }, 3000);
                            
                            // Notify user about duplicate
                            showNotification('info', `Product "${productName}" with size "${data.size}" is already in the list`);
                        } else {
                            // No duplicate found, add the product
                            addProductToTable(data);
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
            // Make sure quantity is a valid number, default to 0 if not
            const stockQuantity = parseInt(data.quantity) || 0;
            let stockClass = stockQuantity > 10 ? 'badge-stock-high' : (stockQuantity > 5 ? 'badge-stock-medium' : 'badge-stock-low');
            
            let newRow = `
                <tr class="item-row">
                    <td>
                        <div class="fw-bold">${data.medicine_name}</div>
                        <input class="form-control pr_id" type="hidden" name="product_id[]" value="${data.medicine_id}">
                        <input class="form-control stock_id" type="hidden" name="stock_id[]" value="${data.id}">
                        <input class="form-control product_name" type="hidden" name="product_name[]" value="${data.medicine_name}">
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">${data.barcode_text || 'N/A'}</span>
                        <input class="form-control barcode" type="hidden" name="barcode[]" value="${data.barcode_text || ''}">
                    </td>
                    <td>
                        <span>${data.size || 'N/A'}</span>
                        <input class="form-control size" type="hidden" name="size[]" value="${data.size || ''}">
                    </td>
                    <td>
                        <input class="form-control qty" type="number" name="quantity[]" min="1" max="${stockQuantity}" value="1" required>
                    </td>
                    <td>
                        <span class="badge ${stockClass}">${stockQuantity}</span>
                        <input class="form-control" type="hidden" name="stock[]" value="${stockQuantity}">
                    </td>
                    <td>
                        <input class="form-control" name="box_mrp[]" type="number" step="any" value="${data.price || 0}" required>
                        <input class="form-control" name="create_date[]" type="hidden" value="${Math.floor(Math.random() * 99999)}">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger delete-new-item">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            $("#purchaseTable tbody").append(newRow);
        }
        
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