@extends('layouts.admin.master')

@section('title')Exchange Products @endsection

@section('content')
<div class="">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Exchange Products</h3>
        </div>
        <div class="card-body">
            <!-- Invoice ID section -->
            <div class="form-group mb-4">
                <label for="invoice_id" class="form-label fw-bold">Invoice ID <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="text" class="form-control" id="invoice_id" placeholder="Enter Invoice ID" required>
                    <button class="btn btn-outline-secondary" type="button" id="fetch_invoice">
                        <i class="fa fa-search"></i> Fetch
                    </button>
                </div>
                <small id="invoice_feedback" class="form-text"></small>
            </div>

            <div class="row mt-4">
                <!-- Products to Exchange Section -->
                <div class="col-lg-6">
                    <div class="card h-100 border-danger mb-4">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0"><i class="fa fa-exchange-alt"></i> Products to Return</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="product_select" class="form-label fw-bold">Select Product to Exchange</label>
                                <select class="form-control" id="product_select" required>
                                    <option value="" disabled selected>Select Product</option>
                                    <!-- Products will be loaded dynamically -->
                                </select>
                                <div class="d-grid gap-2 mt-2">
                                    <button type="button" class="btn btn-outline-danger" onclick="addSelectedProduct()">
                                        <i class="fa fa-plus-circle"></i> Add to Exchange List
                                    </button>
                                </div>
                            </div>
                            
                            <div class="table-responsive mt-3">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Size</th>
                                            <th>Avail.</th>
                                            <th class="text-center">QTY</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-end">Total</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="selected_products_table">
                                        <!-- Selected products will be added here -->
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-secondary">
                                            <th colspan="5" class="text-end fw-bold">Total Return Value:</th>
                                            <th id="exchange_total" class="text-end fw-bold">0.00</th>
                                            <th></th>
                                        </tr>
                                        <tr class="table-secondary">
                                            <th colspan="5" class="text-end fw-bold">Original Purchase Value:</th>
                                            <th id="purchase_total" class="text-end fw-bold">0.00</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- New Products Section -->
                <div class="col-lg-6">
                    <div class="card h-100 border-success mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fa fa-shopping-cart"></i> New Products</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="medicine_id" class="form-label fw-bold">Select New Product</label>
                                <select id="medicine_id" class="form-control">
                                    <option value="" disabled selected>Select Product</option>
                                    <!-- Medicine details loaded via AJAX -->
                                </select>
                                <div class="d-grid gap-2 mt-2">
                                    <button type="button" class="btn btn-outline-success" onclick="addNewProduct()">
                                        <i class="fa fa-plus-circle"></i> Add to New Products
                                    </button>
                                </div>
                            </div>
                            
                            <div class="table-responsive mt-3">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Size</th>
                                            <th>Avail.</th>
                                            <th class="text-center">QTY</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-end">Total</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="selected_products_table2">
                                        <!-- New products will be added here -->
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-secondary">
                                            <th colspan="5" class="text-end fw-bold">Total New Value:</th>
                                            <th id="new_products_total" class="text-end fw-bold">0.00</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Exchange Summary -->
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa fa-calculator"></i> Exchange Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="alert alert-danger h-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong><i class="fa fa-arrow-left"></i> Total Return Value:</strong> 
                                    <span id="summary_exchange_total" class="h5 mb-0">0.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="alert alert-success h-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong><i class="fa fa-arrow-right"></i> Total New Value:</strong> 
                                    <span id="summary_new_total" class="h5 mb-0">0.00</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="alert h-100" id="balance_alert">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong><i class="fa fa-balance-scale"></i> Balance:</strong> 
                                    <span id="balance_amount" class="h5 mb-0">0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="exchange_message" class="alert alert-info d-none mt-3">
                        <!-- Exchange message will be displayed here -->
                    </div>
                    
                    <!-- Exchange Rules Reminder -->
                    <div class="alert alert-warning mt-3">
                        <h6 class="mb-2"><i class="fa fa-exclamation-triangle"></i> Exchange Rules:</h6>
                        <ul class="mb-0">
                            <li>The value of new products must be <strong>equal to or greater than</strong> the value of returned products.</li>
                            <li>If new products value > returned products value, the customer pays the difference.</li>
                            <li>If new products value = returned products value, no additional payment is needed.</li>
                        </ul>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-primary" onclick="submitExchange()">
                            <i class="fa fa-exchange-alt"></i> Process Exchange
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container {
        width: 100% !important;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .qty-input:invalid {
        border-color: #dc3545;
        background-color: #f8d7da;
    }
    
    /* Responsive improvements */
    @media (max-width: 767px) {
        .card-header h3, .card-header h5 {
            font-size: 1rem;
        }
        
        .table th, .table td {
            padding: 0.25rem;
            font-size: 0.875rem;
        }
        
        .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .form-control-sm, .qty-input {
            padding: 0.15rem;
            font-size: 0.75rem;
        }
        
        .alert {
            padding: 0.5rem;
            font-size: 0.875rem;
        }
    }
    
    /* Fix table overflow on small screens */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    /* Make prices more visible */
    .text-end {
        font-weight: 500;
    }
    
    /* Improve visibility of totals */
    #exchange_total, #purchase_total, #new_products_total, #summary_exchange_total, #summary_new_total, #balance_amount {
        font-weight: bold;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('assets/js/jquery.ui.min.js')}}"></script>
<script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>

<script>
// Global variables
let selectedProducts = [];
let exchangeTotal = 0;
let newProductsTotal = 0;
let purchaseTotal = 0;

// Initialize the page
$(document).ready(function() {
    initializeSelect2();
    
    // Fetch products when invoice ID is entered and fetch button clicked
    $("#fetch_invoice").click(function() {
        fetchInvoiceProducts();
    });
    
    // Also fetch when Enter key is pressed in the invoice ID field
    $("#invoice_id").keypress(function(e) {
        if (e.which == 13) {
            fetchInvoiceProducts();
            return false;
        }
    });
});

// Initialize Select2 for better dropdown UX
function initializeSelect2() {
    $("#product_select").select2({
        placeholder: "Select product to exchange",
        allowClear: true
    });
    
    $("#medicine_id").select2({
        placeholder: "Search for products",
        minimumInputLength: 2,
        ajax: {
            url: "{!! url('get-outlet-Stock') !!}",
            type: "get",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term
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
}

// Fetch products for a given invoice ID
function fetchInvoiceProducts() {
    const invoiceId = $("#invoice_id").val().trim();
    
    if (!invoiceId) {
        showInvoiceFeedback("Please enter an invoice ID", "text-danger");
        return;
    }
    
    showInvoiceFeedback("Fetching products...", "text-info");
    
    // Clear existing products
    $("#product_select").empty().append('<option value="" disabled selected>Select Product</option>');
    $("#selected_products_table").empty();
    updateTotals();
    
    $.ajax({
        url: "/get-products/" + invoiceId,
        type: "GET",
        dataType: "json",
        success: function(data) {
            if (data && data.length > 0) {
                let options = '<option value="" disabled selected>Select Product</option>';
                
                data.forEach(function(product) {
                    options += `<option 
                        value="${product.medicine_id}" 
                        data-name="${product.medicine_name}" 
                        data-size="${product.size}" 
                        data-qty="${product.quantity}" 
                        data-rate="${product.rate}" 
                        data-available="${product.available_qty}" 
                        data-purchase="${product.quantity * product.purchase_price}"
                        data-purchase-unit="${product.purchase_price}"
                    >
                        ${product.medicine_name} - ${product.size} (${product.quantity} units)
                    </option>`;
                });
                
                $("#product_select").html(options);
                showInvoiceFeedback("Products loaded successfully", "text-success");
            } else {
                showInvoiceFeedback("No products found for this invoice", "text-warning");
            }
        },
        error: function(xhr) {
            let errorMessage = "Failed to load products";
            
            try {
                const errorData = JSON.parse(xhr.responseText);
                errorMessage = errorData.error || errorMessage;
            } catch(e) {}
            
            showInvoiceFeedback(errorMessage, "text-danger");
        }
    });
}

// Display feedback for invoice ID field
function showInvoiceFeedback(message, className) {
    $("#invoice_feedback").attr('class', 'form-text ' + className).text(message);
}

// Add selected product to the exchange list
function addSelectedProduct() {
    const select = $("#product_select");
    const selectedOption = select.find("option:selected");
    
    if (select.val() === null || select.val() === "") {
        Swal.fire({
            title: 'Select a Product',
            text: 'Please select a product to add to the exchange list',
            icon: 'warning'
        });
        return;
    }
    
    const productId = select.val();
    const productName = selectedOption.data("name");
    const productSize = selectedOption.data("size");
    const availableQty = selectedOption.data("available");
    const maxQty = selectedOption.data("qty");
    const productPrice = selectedOption.data("rate");
    const purchaseUnitPrice = selectedOption.data("purchase-unit") || productPrice * 0.7; // Fallback if purchase price not available
    
    // Check if product already exists in the table
    if ($("#selected_products_table tr[data-id='" + productId + "']").length > 0) {
        Swal.fire({
            title: 'Product Already Added',
            text: 'This product is already in your exchange list',
            icon: 'warning'
        });
        return;
    }
    
    // Create new row with improved layout
    const newRow = `
        <tr data-id="${productId}">
            <td class="text-truncate" style="max-width: 100px;" title="${productName}">${productName}</td>
            <td>${productSize}</td>
            <td class="text-center">${availableQty}</td>
            <td class="text-center">
                <input type="number" class="form-control form-control-sm qty-input" 
                       value="${maxQty}" min="1" max="${maxQty}" required
                       onchange="updateProductTotal(this, ${productPrice}, ${purchaseUnitPrice})">
            </td>
            <td class="text-end">${parseFloat(productPrice).toFixed(2)}</td>
            <td class="text-end product-total">${parseFloat(productPrice * maxQty).toFixed(2)}</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger" onclick="deleteProduct(this, 'exchange')">
                    <i class="fa fa-trash"></i>
                </button>
                <input type="hidden" class="purchase-total" value="${parseFloat(purchaseUnitPrice * maxQty).toFixed(2)}">
            </td>
        </tr>
    `;
    
    $("#selected_products_table").append(newRow);
    updateTotals();
}

// Add new product to the exchange
function addNewProduct() {
    const medicineId = $("#medicine_id").val();
    
    if (!medicineId) {
        Swal.fire({
            title: 'Select a Product',
            text: 'Please select a new product to add',
            icon: 'warning'
        });
        return;
    }
    
    // Check if product already exists
    if ($("#selected_products_table2 tr[data-id='" + medicineId + "']").length > 0) {
        Swal.fire({
            title: 'Product Already Added',
            text: 'This product is already in your new products list',
            icon: 'warning'
        });
        return;
    }
    
    $.ajax({
        url: "{!! url('get-medicine-details-for-sale') !!}" + "/" + medicineId,
        type: "GET",
        dataType: "json",
        beforeSend: function() {
            Swal.fire({
                title: 'Loading',
                text: 'Fetching product details...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        },
        success: function(data) {
            Swal.close();
            
            // Create new row with real-time calculations and improved layout
            const newRow = `
                <tr data-id="${data.medicine_id}">
                    <td class="text-truncate" style="max-width: 100px;" title="${data.medicine_name}">${data.medicine_name}</td>
                    <td>${data.size}</td>
                    <td class="text-center">${data.quantity}</td>
                    <td class="text-center">
                        <input type="number" class="form-control form-control-sm qty-input" 
                               value="1" min="1" max="${data.quantity}" required
                               onchange="updateNewProductTotal(this, ${data.price})">
                    </td>
                    <td class="text-end">${parseFloat(data.price).toFixed(2)}</td>
                    <td class="text-end product-total">${parseFloat(data.price * 1).toFixed(2)}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteProduct(this, 'new')">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            $("#selected_products_table2").append(newRow);
            $("#medicine_id").val(null).trigger('change');
            updateTotals();
            
            // Check if we need to show a suggestion to add more products
            if (exchangeTotal > 0 && newProductsTotal < exchangeTotal) {
                const neededAmount = exchangeTotal - newProductsTotal;
                Swal.fire({
                    title: 'Add More Products',
                    html: `
                        <p>The new products total (${newProductsTotal.toFixed(2)}) is less than 
                        the return value (${exchangeTotal.toFixed(2)}).</p>
                        <p>You need to add more products worth at least <strong>${neededAmount.toFixed(2)}</strong>
                        to complete this exchange.</p>
                    `,
                    icon: 'info'
                });
            }
        },
        error: function() {
            Swal.fire({
                title: 'Error',
                text: 'Failed to fetch product details',
                icon: 'error'
            });
        }
    });
}

// Update product total when quantity changes (for exchange products)
function updateProductTotal(input, price, purchasePrice) {
    const qty = parseInt($(input).val()) || 0;
    const maxQty = parseInt($(input).attr('max')) || 0;
    
    // Validate input
    if (qty <= 0) {
        $(input).val(1);
        updateProductTotal(input, price, purchasePrice);
        return;
    }
    
    if (qty > maxQty) {
        Swal.fire({
            title: 'Invalid Quantity',
            text: `Maximum quantity allowed is ${maxQty}`,
            icon: 'warning'
        });
        $(input).val(maxQty);
    }
    
    const total = parseFloat($(input).val() * price).toFixed(2);
    const purchaseTotal = parseFloat($(input).val() * purchasePrice).toFixed(2);
    
    $(input).closest('tr').find('.product-total').text(total);
    $(input).closest('tr').find('.purchase-total').val(purchaseTotal);
    
    updateTotals();
}

// Update product total when quantity changes (for new products)
function updateNewProductTotal(input, price) {
    const qty = parseInt($(input).val()) || 0;
    const maxQty = parseInt($(input).attr('max')) || 0;
    
    // Validate input
    if (qty <= 0) {
        $(input).val(1);
        updateNewProductTotal(input, price);
        return;
    }
    
    if (qty > maxQty) {
        Swal.fire({
            title: 'Invalid Quantity',
            text: `Maximum quantity available is ${maxQty}`,
            icon: 'warning'
        });
        $(input).val(maxQty);
    }
    
    const total = parseFloat($(input).val() * price).toFixed(2);
    $(input).closest('tr').find('.product-total').text(total);
    
    updateTotals();
}

// Delete product from table
function deleteProduct(button, type) {
    const row = $(button).closest('tr');
    
    Swal.fire({
        title: 'Remove Product?',
        text: 'Are you sure you want to remove this product?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, remove it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            row.remove();
            updateTotals();
        }
    });
}

// Update all totals and check exchange conditions
function updateTotals() {
    // Calculate totals for exchange products
    exchangeTotal = 0;
    purchaseTotal = 0;
    
    $("#selected_products_table tr").each(function() {
        exchangeTotal += parseFloat($(this).find('.product-total').text()) || 0;
        purchaseTotal += parseFloat($(this).find('.purchase-total').val()) || 0;
    });
    
    // Calculate total for new products
    newProductsTotal = 0;
    $("#selected_products_table2 tr").each(function() {
        newProductsTotal += parseFloat($(this).find('.product-total').text()) || 0;
    });
    
    // Update displayed totals
    $("#exchange_total, #summary_exchange_total").text(exchangeTotal.toFixed(2));
    $("#purchase_total").text(purchaseTotal.toFixed(2));
    $("#new_products_total, #summary_new_total").text(newProductsTotal.toFixed(2));
    
    // Calculate and display balance
    const balance = newProductsTotal - exchangeTotal;
    $("#balance_amount").text(Math.abs(balance).toFixed(2));
    
    // Update balance alert styling and message
    const balanceAlert = $("#balance_alert");
    const exchangeMessage = $("#exchange_message");
    
    // Validate if new products value is less than exchange value
    if (newProductsTotal < exchangeTotal) {
        balanceAlert.attr('class', 'alert alert-danger');
        exchangeMessage.attr('class', 'alert alert-danger').html(
            `<strong>Invalid Exchange:</strong> New products value (${newProductsTotal.toFixed(2)}) must be greater than or equal to return value (${exchangeTotal.toFixed(2)})`
        ).removeClass('d-none');
        
        // Disable submit button when invalid
        $("button[onclick='submitExchange()']").prop('disabled', true)
            .removeClass('btn-primary').addClass('btn-secondary')
            .html('<i class="fa fa-ban"></i> Exchange Not Allowed');
    } else {
        // Exchange is valid, enable submit button
        $("button[onclick='submitExchange()']").prop('disabled', false)
            .removeClass('btn-secondary').addClass('btn-primary')
            .html('<i class="fa fa-exchange-alt"></i> Process Exchange');
            
        if (balance > 0) {
            balanceAlert.attr('class', 'alert alert-warning');
            exchangeMessage.attr('class', 'alert alert-warning').html(
                `<strong>Customer needs to pay:</strong> ${balance.toFixed(2)}`
            ).removeClass('d-none');
        } else if (balance < 0) {
            balanceAlert.attr('class', 'alert alert-success');
            exchangeMessage.attr('class', 'alert alert-success').html(
                `<strong>Even Exchange:</strong> No additional payment needed (values are equal)`
            ).removeClass('d-none');
        } else {
            balanceAlert.attr('class', 'alert alert-success');
            exchangeMessage.attr('class', 'alert alert-success').html(
                '<strong>Even Exchange:</strong> No additional payment needed'
            ).removeClass('d-none');
        }
    }
}

// Submit the exchange
function submitExchange() {
    // Validate if products are selected
    if ($("#selected_products_table tr").length === 0) {
        Swal.fire({
            title: 'No Products to Exchange',
            text: 'Please select at least one product to exchange',
            icon: 'warning'
        });
        return;
    }
    
    if ($("#selected_products_table2 tr").length === 0) {
        Swal.fire({
            title: 'No New Products',
            text: 'Please select at least one new product',
            icon: 'warning'
        });
        return;
    }
    
    // Primary validation: New products value must be greater than or equal to exchange value
    if (newProductsTotal < exchangeTotal) {
        Swal.fire({
            title: 'Invalid Exchange',
            html: `
                <p>This exchange cannot be processed because:</p>
                <ul>
                    <li>New products value (${newProductsTotal.toFixed(2)}) is less than return value (${exchangeTotal.toFixed(2)})</li>
                </ul>
                <p>Please add more new products or reduce return items.</p>
            `,
            icon: 'error'
        });
        return;
    }
    
    // Process exchange if all validations pass
    processExchange();
}

// Process the exchange after validation
function processExchange() {
    // Calculate balance
    const balance = newProductsTotal - exchangeTotal;
    let confirmMessage = '';
    
    if (balance > 0) {
        confirmMessage = `Customer needs to pay ${balance.toFixed(2)}. Proceed with exchange?`;
    } else if (balance < 0) {
        // This should never happen now with our validation, but just in case
        confirmMessage = `This exchange is not allowed. New products value must be greater than or equal to return value.`;
        return;
    } else {
        confirmMessage = 'This is an even exchange. Proceed?';
    }
    
    // Prepare data for submission
    const exchangeData = {
        invoice_id: $("#invoice_id").val(),
        exchange_products: getExchangeProducts(),
        new_products: getNewProducts(),
        balance: balance
    };
    
    // Log data to console for debugging
    console.log("Exchange Data:", exchangeData);
    
    Swal.fire({
        title: 'Confirm Exchange',
        text: confirmMessage,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, process exchange',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit the exchange
            $.ajax({
                url: "/exchange",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    exchangeData
                },
                dataType: "json",
                beforeSend: function() {
                    Swal.fire({
                        title: 'Processing',
                        text: 'Processing your exchange...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.close();
                    
                    // Open invoice in new window
                    var url = "{{ route('print-invoice-exchange', ':id') }}";
                    url = url.replace(':id', response.data);
                    window.open(url, "_blank");
                    
                    // Show success and redirect
                    Swal.fire({
                        title: 'Success!',
                        text: 'Exchange processed successfully',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = "{{ route('exchange.index') }}";
                    });
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to process exchange';
                    
                    console.error("Error response:", xhr.responseText);
                    
                    try {
                        const errorData = JSON.parse(xhr.responseText);
                        errorMessage = errorData.message || errorData.error || errorMessage;
                    } catch(e) {
                        console.error("Error parsing error response:", e);
                    }
                    
                    Swal.fire({
                        title: 'Error',
                        text: errorMessage,
                        icon: 'error'
                    });
                }
            });
        }
    });
}

// Get exchange products data
function getExchangeProducts() {
    const exchangeProducts = [];
    let totalExchangeValue = 0;
    
    $("#selected_products_table tr").each(function() {
        const productId = $(this).data('id');
        const productName = $(this).find('td:nth-child(1)').text();
        const productSize = $(this).find('td:nth-child(2)').text();
        const availableQty = $(this).find('td:nth-child(3)').text();
        const productQty = $(this).find('.qty-input').val();
        const productPrice = parseFloat($(this).find('td:nth-child(5)').text());
        const totalPrice = parseFloat($(this).find('.product-total').text());
        
        // Ensure purchase value is a valid number
        let purchaseValue = parseFloat($(this).find('.purchase-total').val());
        if (isNaN(purchaseValue)) {
            purchaseValue = totalPrice * 0.7; // Default to 70% of total price if not available
        }
        
        totalExchangeValue += totalPrice;
        
        exchangeProducts.push({
            id: productId,
            name: productName,
            size: productSize,
            avalqty: availableQty,
            qty: productQty,
            price: productPrice,
            total_price: totalPrice,
            purchase: purchaseValue / productQty // Purchase price per unit
        });
    });
    
    // Add the grand total as a separate object at the end of the array
    // This is critical for the backend to work properly
    exchangeProducts.push({
        grandTotal: totalExchangeValue
    });
    
    return exchangeProducts;
}

// Get new products data
function getNewProducts() {
    const newProducts = [];
    let totalNewValue = 0;
    
    $("#selected_products_table2 tr").each(function() {
        const productId = $(this).data('id');
        const productName = $(this).find('td:nth-child(1)').text();
        const productSize = $(this).find('td:nth-child(2)').text();
        const availableQty = $(this).find('td:nth-child(3)').text();
        const productQty = $(this).find('.qty-input').val();
        const productPrice = parseFloat($(this).find('td:nth-child(5)').text());
        const totalPrice = parseFloat($(this).find('.product-total').text());
        
        totalNewValue += totalPrice;
        
        newProducts.push({
            id: productId,
            name: productName,
            size: productSize,
            avalqty: availableQty,
            qty: productQty,
            price: productPrice,
            total_price: totalPrice
        });
    });
    
    // Add the grand total as a separate object at the end of the array
    // This is critical for the backend to work properly
    newProducts.push({
        grandTotal: totalNewValue
    });
    
    return newProducts;
}

// Display notification
function showNotification(message, type = 'success') {
    $.notify(
        '<i class="fa fa-bell"></i><strong>' + message + '</strong>', 
        {
            type: type,
            allow_dismiss: true,
            delay: 2000,
            showProgressbar: true,
            timer: 300
        }
    );
}
</script>

@if (Session()->get('success'))
<script>
    $(document).ready(function() {
        showNotification("{{ Session()->get('success') }}", "theme");
    });
</script>
@endif
@endpush

@endsection