@extends('layouts.admin.master')
@section('title')Add Product To Warehouse
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
}

.select2-container--default .select2-selection--single {
height: 46px;
border-radius: 0;
border: 1px solid #e0e0e0;
padding: 8px 10px;
background-color: #f9f9f9;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
height: 44px;
}

label {
font-weight: 600;
margin-bottom: 8px;
color: #222;
text-transform: uppercase;
font-size: 12px;
letter-spacing: 0.5px;
}

.col-form-label {
font-weight: 600;
text-transform: uppercase;
font-size: 12px;
letter-spacing: 0.5px;
}

/* Button styling */
.btn {
border-radius: 0;
font-weight: 600;
text-transform: uppercase;
letter-spacing: 1px;
padding: 10px 20px;
transition: all 0.2s ease;
box-shadow: none;
}

.btn-primary {
background: #000;
border-color: #000;
}

.btn-primary:hover {
background: #333;
border-color: #333;
}

.btn-success {
background: #1a1a1a;
border-color: #1a1a1a;
}

.btn-success:hover {
background: #333;
border-color: #333;
}

.btn-warning {
background: #333;
color: white;
border-color: #333;
}

.btn-warning:hover {
background: #555;
border-color: #555;
color: white;
}

/* Table styling */
#purchaseTable {
border: none;
width: 100%;
}

#purchaseTable thead th {
background-color: #000;
color: #fff;
padding: 12px 15px;
font-weight: 500;
text-transform: uppercase;
font-size: 12px;
letter-spacing: 0.5px;
border: none;
}

#purchaseTable tbody td {
padding: 12px 15px;
vertical-align: middle;
border: 1px solid #eee;
}

.table-bordered {
border: 1px solid #eee;
}

/* Input groups */
.input-group-text {
border-radius: 0;
background: #000;
color: white;
border: none;
}

/* Custom class for blade-like edges */
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

/* Add sharp angle to form sections */
.form-group {
position: relative;
margin-bottom: 28px;
}

/* Make notification sharp edged */
.alert {
border-radius: 0;
border-left: 4px solid #000;
}

/* Custom validation styling */
.invalid-feedback2 {
display: block;
color: #ff0000;
font-weight: 500;
font-size: 12px;
margin-top: 5px;
text-transform: uppercase;
letter-spacing: 0.5px;
}

/* Focus indicators */
input:focus, select:focus {
border-left: 3px solid #000 !important;
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

/* Size Combination Builder Styles */
.size-combination-builder {
border: 1px solid #eee;
}

.size-combination-builder .card-header {
background: #000;
color: white;
}

.size-checkbox {
display: inline-flex;
align-items: center;
justify-content: center;
margin: 5px;
position: relative;
cursor: pointer;
}

.size-checkbox input {
position: absolute;
opacity: 0;
cursor: pointer;
height: 0;
width: 0;
}

.size-checkbox span {
display: flex;
align-items: center;
justify-content: center;
height: 40px;
width: 40px;
background-color: #f9f9f9;
border: 1px solid #e0e0e0;
transition: all 0.2s ease;
user-select: none;
}

.size-checkbox input:checked ~ span {
background-color: #000;
color: white;
border-color: #000;
}

.size-checkbox:hover span {
background-color: #e0e0e0;
}

.size-checkbox input:checked:hover ~ span {
background-color: #333;
}

#size-combinations-table th {
background-color: #000;
color: white;
border: none;
text-transform: uppercase;
font-size: 12px;
letter-spacing: 0.5px;
}

.selected-combinations {
background-color: #f9f9f9;
padding: 15px;
border: 1px solid #e0e0e0;
}

#no-sizes-selected {
color: #888;
font-style: italic;
}

/* Quantity input for size combinations */
.size-qty-input {
width: 60px;
text-align: center;
border: 1px solid #e0e0e0;
padding: 5px;
background-color: #fff;
}

.size-price-adjust {
width: 80px;
text-align: right;
border: 1px solid #e0e0e0;
padding: 5px;
background-color: #fff;
}

/* Size combination remove button */
.remove-size-btn {
background-color: transparent;
border: none;
color: #ff0000;
cursor: pointer;
transition: all 0.2s ease;
}

.remove-size-btn:hover {
color: #cc0000;
}

.delete {
color: #fff;
}

.custom-td {
padding: 5px !important;
vertical-align: middle !important;
}
</style>
@endpush
@section('content')
@component('components.breadcrumb')
@slot('breadcrumb_title')
<div class="row">
<div class="col-sm-6">
<h3 class="blade-box">ADD PURCHASE</h3>
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
<div class="card-body mt-3">
{!! Form::open(['route' => 'product-purchase.store', 'class' => 'needs-validation', 'novalidate'=> '', 'autocomplete' => 'off', 'files' => true]) !!}

<div class="form-group row">
<label for="supplier" class="col-md-2 text-right col-form-label">SUPPLIER:</label>
<div class="col-md-4">
{{ Form::select('supplier_id', $suppliers ?? [], null, ['class' => 'form-control', 'placeholder' => 'Select Supplier', 'id' => 'supplier_id']) }}
<div class="invalid-feedback">Please Add Supplier</div>
@error('supplier_id')
<div class="invalid-feedback2"> {{ $message }}</div>
@enderror
</div>
<label for="warehouse_id" class="col-md-2 text-right">WAREHOUSE NAME:</label>
<div class="col-md-4">
{{ Form::select( 'warehouse_id', $warehouse, null, ['class' => 'form-control', 'required'] ) }}
@error('warehouse_id')
<div class="invalid-feedback2"> {{ $message }}</div>
@enderror
</div>
</div>

<div class="form-group row">
<label for="invoice_no" class="col-md-2 text-right col-form-label">INVOICE NO<i class="text-danger">*</i>:</label>
<div class="col-md-4">
<div class="">
<input type="text" class="form-control valid_number" name="invoice_no" id="invoice_no"
   placeholder="Invoice No" value="" tabindex="3" required>
@error('invoice_no')
<div class="invalid-feedback2"> {{ $message }}</div>
@enderror
</div>
</div>

<label for="date" class="col-md-2 text-right col-form-label">PURCHASE DATE<i class="text-danger">*</i>:</label>
<div class="col-md-4">
<input class="datepicker-here form-control digits" type="text" data-language="en" 
name="purchase_date" value="{{ Carbon\Carbon::today()->format('d-m-Y') }}" tabindex="2" required>
</div>
</div>

<div class="form-group row">
<label for="payment_type" class="col-md-2 text-right col-form-label">PAYMENT METHOD<i class="text-danger">*</i>:</label>
<div class="col-md-4">
{{ Form::select( 'payment_method_id', $payment_methods, null, ['class' => 'form-control', 'required'] ) }}
@error('payment_method_id')
<div class="invalid-feedback2"> {{ $message }}</div>
@enderror
</div>

<label for="details" class="col-md-2 text-right col-form-label">PURCHASE DETAILS:</label>
<div class="col-md-4">
<input type="text" class="form-control" name="purchase_details" id="details"
placeholder="Purchase Details" tabindex="4">
</div>
</div>

<div class="row form-group">
<label for="invoicePhoto" class="col-md-2 text-right">INVOICE IMAGE:</label>
<div class="col-md-4">
<div class="custom-file">
{{ Form::file('invoice_image', ['class' => 'custom-file-input', 'id' => 'invoice_image']) }}
<label class="custom-file-label" for="invoice_image">Choose file</label>
</div>
@error('invoice_image')
<div class="invalid-feedback2"> {{ $message }}</div>
@enderror
</div>
</div>

<div class="card mt-4">
<div class="card-header">
<i class="fa fa-table mr-2"></i> MAKE PURCHASE
</div>

<div class="card-body">
<div class="row mb-4">
<div class="col-md-9">
<div class="form-group mb-0">
    <label for="medicine_id">SELECT PRODUCT <i class="text-danger">*</i></label>
    {{ Form::select('', [], null, ['class' => 'form-control', 'placeholder' => 'Select Product', 'id' => 'medicine_id']) }}
    <div class="invalid-feedback">Please Add Product</div>
    <!-- Hidden input to track if products are added -->
    <input type="hidden" name="has_products" id="has_products" value="0">
</div>
</div>

<div class="col-md-3">
<button type="button" class="btn btn-primary btn-block mt-4" id="selectProduct">
    <i class="fa fa-check mr-2"></i> SELECT PRODUCT
</button>
</div>
</div>

<!-- Size Combination Builder -->
<div class="card mb-4 size-combination-builder" id="size-builder" style="display: none;">
<div class="card-header">
<i class="fa fa-th-large mr-2"></i> SIZE COMBINATION BUILDER
<span class="float-right" id="selected-product-name">No product selected</span>
</div>
<div class="card-body">
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="size_type">SIZE TYPE</label>
            <select id="size_type" class="form-control">
                <option value="letter">Letter Sizes (S, M, L, XL, etc.)</option>
                <option value="number">Numeric Sizes (34, 36, 38, etc.)</option>
                <option value="custom">Custom Sizes</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div id="size-selection-area" class="mt-4 pt-2">
            <!-- Letter sizes (default) -->
            <div id="letter-sizes" class="size-type-container">
                <div class="d-flex flex-wrap size-checkboxes">
                    <label class="size-checkbox">
                        <input type="checkbox" value="XS"> <span>XS</span>
                    </label>
                    <label class="size-checkbox">
                        <input type="checkbox" value="S"> <span>S</span>
                    </label>
                    <label class="size-checkbox">
                        <input type="checkbox" value="M"> <span>M</span>
                    </label>
                    <label class="size-checkbox">
                        <input type="checkbox" value="L"> <span>L</span>
                    </label>
                    <label class="size-checkbox">
                        <input type="checkbox" value="XL"> <span>XL</span>
                    </label>
                    <label class="size-checkbox">
                        <input type="checkbox" value="2XL"> <span>2XL</span>
                    </label>
                    <label class="size-checkbox">
                        <input type="checkbox" value="3XL"> <span>3XL</span>
                    </label>
                </div>
            </div>
            
            <!-- Number sizes (hidden by default) -->
            <div id="number-sizes" class="size-type-container" style="display: none;">
                <div class="d-flex flex-wrap size-checkboxes">
                    @for($i = 34; $i <= 48; $i += 2)
                    <label class="size-checkbox">
                        <input type="checkbox" value="{{ $i }}"> <span>{{ $i }}</span>
                    </label>
                    @endfor
                </div>
            </div>
            
            <!-- Custom sizes (hidden by default) -->
            <div id="custom-sizes" class="size-type-container" style="display: none;">
                <div class="input-group">
                    <input type="text" class="form-control" id="custom-size-input" placeholder="Enter custom size (e.g. 'One Size', '4-6Y')">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" id="add-custom-size">ADD</button>
                    </div>
                </div>
                <div class="d-flex flex-wrap size-checkboxes mt-2" id="custom-size-checkboxes">
                    <!-- Custom sizes will be added here dynamically -->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="selected-combinations mt-4">
    <label>SELECTED SIZE COMBINATIONS</label>
    <div class="table-responsive">
        <table class="table table-bordered" id="size-combinations-table">
            <thead>
                <tr>
                    <th>SIZE</th>
                    <th>QUANTITY</th>
                    <th>PRICE ADJUSTMENT</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <!-- Selected sizes will appear here -->
                <tr id="no-sizes-selected">
                    <td colspan="4" class="text-center">No sizes selected yet</td>
                </tr>
            </tbody>
        </table>
    </div>
    <button type="button" id="apply-combinations" class="btn btn-success float-right">
        <i class="fa fa-check mr-1"></i> APPLY COMBINATIONS
    </button>
    <div class="clearfix"></div>
</div>
</div>
</div>

<div class="table-responsive pt-2">
<table class="table table-bordered" id="purchaseTable">
<thead>
<tr class="item-row">
    <th class="text-center">PRODUCT INFO<i class="text-danger">*</i></th>
    <th class="text-center">SIZE<i class="text-danger">*</i></th>
    <th class="text-center">QTY<i class="text-danger">*</i></th>
    <th class="text-center">MSRP<i class="text-danger">*</i></th>
    <th class="text-center">MRP<i class="text-danger">*</i></th>
    <th class="text-center">TYPE<i class="text-danger">*</i></th>
    <th class="text-center">TOTAL PRICE</th>
    <th class="text-center">ACTION</th>
</tr>
</thead>
<tbody>
<tr>
    <td class="text-right" colspan="6"><b>SUB TOTAL:</b></td>
    <td class="text-right">
        <input type="number" class="form-control text-right" name="sub_total"
               id="subtotal" readonly>
    </td>
    <td></td>
</tr>
<tr>
    <td class="text-right" colspan="5"><b>VAT:</b></td>
    <td class="text-right">
        <input type="number" id="vat_percent" max="100" class="text-right form-control"
               placeholder="%"/>
    </td>
    <td>
        <input type="number" id="vat" class="text-right form-control clearVat"
               name="vat" value="0" step="any" placeholder="Tk"/>
    </td>
    <td></td>
</tr>
<tr>
    <td class="text-right" colspan="5"><b>DISCOUNT:</b></td>
    <td class="text-right">
        <input type="number" id="discount_percent" class="text-right form-control"
               max="100" tabindex="16" placeholder="%"/>
    </td>
    <td>
        <input type="number" id="discount" class="text-right form-control"
               name="total_discount" value="0" tabindex="17" step="any"
               placeholder="Tk"/>
    </td>
    <td></td>
</tr>
<tr>
    <td class="text-right" colspan="6"><b>GRAND TOTAL:</b></td>
    <td class="text-right">
        <input type="number" class="form-control text-right" name="grand_total"
               id="grandTotal" readonly="readonly" required>
    </td>
    <td></td>
</tr>
<tr>
    <td class="text-right" colspan="6"><b>PAID AMOUNT:</b></td>
    <td class="text-right">
        <input type="number" id="pay" class="text-right form-control"
               name="paid_amount" value="" onkeyup="prevent_paid_amount()" placeholder="0.00"
               onchange="prevent_paid_amount()" tabindex="16" step=".01" required/>
        <div class="invalid-feedback">Paid Amount Can Not Be Empty</div>
    </td>
    <td></td>
</tr>
<tr>
    <td class="text-right" colspan="6"><b>DUE AMOUNT:</b></td>
    <td class="text-right">
        <input type="number" id="due" class="text-right form-control" name="due_amount"
               value="0" readonly="readonly"/>
    </td>
    <td></td>
</tr>
</tbody>
</table>
</div>

<div class="form-group row mt-4">
<div class="col-md-6"></div>
<div class="col-md-6 text-right">
<button type="button" id="full_paid" class="btn btn-warning mr-2" tabindex="19">
    <i class="fa fa-money mr-1"></i> FULL PAID
</button>
<button type="submit" class="btn btn-success" tabindex="19" id="save_purchase">
    <i class="fa fa-save mr-1"></i> SAVE
</button>
</div>
</div>
</div>
</div>
{{ Form::close() }}
</div>
</div>
</div>

@push('scripts')
<script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
<script src="{{ asset('assets/js/product_purchase_invoice.js') }}"></script>
<script type="text/javascript">
function clearInput1(target) {
if (target.value == '0') {
target.value = "";
}
}

function clearInput2(target) {
if (target.value == '0') {
target.value = "";
}
}

function prevent_paid_amount() {
var paid_amount = $("#pay").val();
var grand_total_amount = $("#grandTotal").val();
if (parseFloat(grand_total_amount) < parseFloat(paid_amount)) {
alert("You can not paid more than grand total amount.");
$("#pay").val("");
}
}

$(document).ready(function () {
// Define CSRF token
let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
let supplier_id = '';
let selectedProduct = null;
let selectedProductDetails = null;

// File input styling
$(".custom-file-input").on("change", function() {
let fileName = $(this).val().split("\\").pop();
$(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

$(".clearVat,#discount,#pay,#manufacturer_price").on('click', function () {
let input = $(this).val();
if (input == 0) {
$(this).val('');
}
});

let grandTotal = '';
$("#full_paid").on('click', function () {
grandTotal = $("#grandTotal").val();
$("#pay").val(grandTotal);
});

// payment validations
$("#discount").on('click', function () {
let subTotal = $('#subtotal').val();
$("#discount").attr({
max: subTotal,
});
});

$("#pay").on('click', function () {
grandTotal = $("#grandTotal").val();
$("#pay").attr({
max: grandTotal,
});
});

// percentage live calculations
$("#vat_percent").bind('keypress keyup keydown mouseup', function () {
let vat = $(this).val();
let subTotal = $("#subtotal").val();
let totalVat = calculatePercentage(subTotal, vat);
totalVat = totalVat.toFixed(2);
$("#vat").val(totalVat);
});

$("#discount_percent").bind('keypress keyup keydown mouseup', function () {
let discount = $(this).val();
let subTotal = $("#subtotal").val();
let totalDiscount = calculatePercentage(subTotal, discount);
totalDiscount = totalDiscount.toFixed(2);
$("#discount").val(totalDiscount);
});

function calculatePercentage(subTotal, vat) {
let result = subTotal * (vat / 100);
return result;
}

// Initialize supplier select2 with server-side data
$("#supplier_id").select2({
placeholder: "Search and select supplier",
allowClear: true,
ajax: {
url: "{!! url('get-supplier') !!}",
type: "get",
dataType: 'json',
delay: 250,
data: function (params) {
return {
_token: CSRF_TOKEN,
search: params.term || '',
page: params.page || 1
};
},
processResults: function (response) {
return {
results: response.map(function(item) {
    return {
        id: item.id,
        text: item.text || item.name || item.supplier_name || `Supplier #${item.id}`
    };
}),
pagination: {
    more: false
}
};
},
cache: true
}
});

// Pre-load suppliers if empty
if ($("#supplier_id option").length <= 1) {
$.ajax({
url: "{!! url('get-supplier') !!}",
type: "get",
dataType: 'json',
success: function(response) {
if (response && response.length > 0) {
// Clear any existing options except the placeholder
$("#supplier_id option:not(:first)").remove();

// Add all suppliers to the dropdown
$.each(response, function(index, item) {
    const supplierName = item.text || item.name || item.supplier_name || `Supplier #${item.id}`;
    $("#supplier_id").append(new Option(supplierName, item.id, false, false));
});

// Refresh the select2
$("#supplier_id").trigger('change');
}
}
});
}

// Update supplier_id when changed
$("#supplier_id").on('change', function () {
supplier_id = $(this).val();
});

// manufacturer wise medicine selection
$("#medicine_id").select2({
ajax: {
url: "{!! url('get-manufacture-wise-medicine') !!}",
type: "get",
dataType: 'json',
delay: 250,
data: function (params) {
return {
_token: CSRF_TOKEN,
search: params.term || '',
supplier: supplier_id,
};
},
processResults: function (response) {
return {
results: response
};
},
cache: true
}
});

// Use correct key for product details
$("#selectProduct").click(function() {
const productId = $("#medicine_id").val();

if (!productId) {
alert("Please select a product first!");
return;
}

// Get product details
$.ajax({
url: "{!! url('get-medicine-details-for-purchase') !!}" + "/" + productId,
type: "GET",
dataType: "json",
beforeSend: function() {
// Show loading indicator
$("#selectProduct").html('<i class="fa fa-spinner fa-spin"></i> LOADING...');
$("#selectProduct").prop('disabled', true);
},
success: function(data) {
if (data != null) {
// Store product info
selectedProduct = productId;
selectedProductDetails = data;

// Debug product data structure
console.log("Product data:", data);

// Show product name in the size builder - using more reliable approach
let productName = 'Selected Product';
if (typeof data === 'object') {
    if (data.medicine_name) productName = data.medicine_name;
    else if (data.product_name) productName = data.product_name;
    else if (data.name) productName = data.name;
    else if (data.title) productName = data.title;
}
$("#selected-product-name").text(productName);

// Show size builder
$("#size-builder").slideDown(300);

// Clear previous selections
$(".size-checkbox input").prop("checked", false);
updateSizeCombinationsTable();

// Scroll to size builder
$('html, body').animate({
    scrollTop: $("#size-builder").offset().top - 20
}, 500);

// Add success notification
$.notify('<i class="fa fa-check"></i> Product selected! Now choose sizes.', {
    type: 'success',
    allow_dismiss: true,
    delay: 3000
});
} else {
alert('Product data not found!');
}
},
error: function() {
alert('Error fetching product data!');
},
complete: function() {
// Reset button
$("#selectProduct").html('<i class="fa fa-check mr-2"></i> SELECT PRODUCT');
$("#selectProduct").prop('disabled', false);
}
});
});

$("#size_type").change(function() {
const selectedType = $(this).val();

// Hide all size type containers
$(".size-type-container").hide();

// Show the selected size type container
if (selectedType === "letter") {
$("#letter-sizes").show();
} else if (selectedType === "number") {
$("#number-sizes").show();
} else if (selectedType === "custom") {
$("#custom-sizes").show();
}
});

// Handle size checkbox clicks
$(document).on("change", ".size-checkbox input", function() {
updateSizeCombinationsTable();
});

// Add custom size
$("#add-custom-size").click(function() {
const customSize = $("#custom-size-input").val().trim();

if (customSize) {
// Check if size already exists
const exists = $("#custom-size-checkboxes").find(`input[value="${customSize}"]`).length > 0;

if (!exists) {
const checkbox = `
<label class="size-checkbox">
    <input type="checkbox" value="${customSize}" checked> <span>${customSize}</span>
</label>
`;

$("#custom-size-checkboxes").append(checkbox);
$("#custom-size-input").val('');

updateSizeCombinationsTable();
} else {
alert("This size already exists!");
}
}
});

// Handle custom size input enter key
$("#custom-size-input").keypress(function(e) {
if (e.which === 13) {
e.preventDefault();
$("#add-custom-size").click();
}
});

// Update size combinations table
function updateSizeCombinationsTable() {
const selectedSizes = [];

// Get all checked sizes from all size type containers
$(".size-type-container:visible .size-checkbox input:checked").each(function() {
selectedSizes.push($(this).val());
});

// Clear the table
const tbody = $("#size-combinations-table tbody");
tbody.empty();

if (selectedSizes.length === 0) {
tbody.append(`
<tr id="no-sizes-selected">
<td colspan="4" class="text-center">No sizes selected yet</td>
</tr>
`);
} else {
selectedSizes.forEach(size => {
tbody.append(`
<tr data-size="${size}">
    <td>${size}</td>
    <td>
        <input type="number" class="form-control size-qty-input" value="1" min="1">
    </td>
    <td>
        <input type="number" class="form-control size-price-adjust" value="0" step="0.01">
    </td>
    <td class="text-center">
        <button type="button" class="remove-size-btn" data-size="${size}">
            <i class="fa fa-trash"></i>
        </button>
    </td>
</tr>
`);
});
}
}

// Remove size from combinations
$(document).on("click", ".remove-size-btn", function() {
const size = $(this).data("size");

// Uncheck the corresponding checkbox
$(`.size-checkbox input[value="${size}"]`).prop("checked", false);

// Update the table
updateSizeCombinationsTable();
});

// Step 3: Apply size combinations to purchase table
$("#apply-combinations").click(function() {
if (!selectedProduct || !selectedProductDetails) {
alert("Product information is not available!");
return;
}

const combinations = [];
let hasItems = false;

// Get all selected sizes with quantities
$("#size-combinations-table tbody tr[data-size]").each(function() {
hasItems = true;
const size = $(this).data("size");
const qty = parseInt($(this).find(".size-qty-input").val()) || 1;
const priceAdjust = parseFloat($(this).find(".size-price-adjust").val()) || 0;

combinations.push({
size: size,
qty: qty,
priceAdjust: priceAdjust
});
});

if (!hasItems) {
alert("Please select at least one size!");
return;
}

// Add rows to purchase table
const purchaseTable = $("#purchaseTable");
let subTotal = 0;

// Determine the product name reliably
let productName = 'Selected Product';
if (typeof selectedProductDetails === 'object') {
if (selectedProductDetails.medicine_name) productName = selectedProductDetails.medicine_name;
else if (selectedProductDetails.product_name) productName = selectedProductDetails.product_name;
else if (selectedProductDetails.name) productName = selectedProductDetails.name;
else if (selectedProductDetails.title) productName = selectedProductDetails.title;
}

// Add each size as a new row
combinations.forEach(item => {
const basePrice = selectedProductDetails.price || 0;
const manufacturerPrice = selectedProductDetails.manufacturer_price || 0;
const adjustedPrice = parseFloat(basePrice) + parseFloat(item.priceAdjust);
const total = adjustedPrice * item.qty;
subTotal += total;

// Create a new row with the product and size info
const newRow = `
<tr class="item-row">
<td>
    <input type="hidden" name="product_id[]" value="${selectedProduct}">
    <input type="hidden" name="product_name[]" value="${productName}">
    <input type="hidden" name="product_type[]" value="T-Shirt">
    <strong>${productName}</strong>
</td>
<td>
    <input type="text" class="form-control" name="size[]" value="${item.size}" readonly>
</td>
<td>
    <input type="number" class="form-control qty" name="qty[]" value="${item.qty}" min="1">
    <input type="hidden" name="quantity[]" value="${item.qty}">
</td>
<td>
    <input type="number" class="form-control" name="manufacturer_price[]" value="${manufacturerPrice}" step="0.01">
    <input type="hidden" name="msrp[]" value="${manufacturerPrice}">
</td>
<td>
    <input type="number" class="form-control price" name="box_mrp[]" value="${adjustedPrice}" step="0.01">
    <input type="hidden" name="mrp[]" value="${adjustedPrice}">
</td>
<td>
    <input type="text" class="form-control" name="product_type[]" value="T-Shirt">
</td>
<td>
    <input type="text" class="form-control total" name="total_price[]" value="${total.toFixed(2)}" readonly>
</td>
<td class="text-center">
    <button type="button" class="btn btn-danger btn-sm delete"><i class="fa fa-trash"></i></button>
</td>
</tr>
`;

// Insert before the subtotal row
$(newRow).insertBefore($("#purchaseTable tbody tr:first"));
});

// Mark that we have products (for form validation)
$("#has_products").val("1");

// Update subtotal
$("#subtotal").val(subTotal.toFixed(2));

// Reset form for next product
$("#medicine_id").val('').trigger('change');
$("#size-builder").slideUp(300);

// Clear checked sizes
$(".size-checkbox input").prop("checked", false);
updateSizeCombinationsTable();

// Show success notification
$.notify('<i class="fa fa-check"></i> Size combinations added to purchase!', {
type: 'success',
allow_dismiss: true,
delay: 3000
});

// Scroll to purchase table
$('html, body').animate({
scrollTop: $("#purchaseTable").offset().top - 20
}, 500);

// Calculate grand total
calculateTotals();
});

// Helper function to calculate totals
function calculateTotals() {
// Calculate subtotal from all product rows
let subtotal = 0;
$(".item-row .total").each(function() {
subtotal += parseFloat($(this).val()) || 0;
});

// Update subtotal field
$("#subtotal").val(subtotal.toFixed(2));

const vat = parseFloat($("#vat").val()) || 0;
const discount = parseFloat($("#discount").val()) || 0;

const grandTotal = subtotal + vat - discount;
$("#grandTotal").val(grandTotal.toFixed(2));

// Update due amount
const paid = parseFloat($("#pay").val()) || 0;
const due = grandTotal - paid;
$("#due").val(due.toFixed(2));
}

// Add form submission handler
$("form").on("submit", function(e) {
// Check if products have been added
if ($("#has_products").val() === "0" && !$("#medicine_id").val()) {
e.preventDefault();
alert("Please add at least one product to the purchase!");
return false;
}

// If there are products, form submission can proceed
return true;
});

// Make sure invoice.js removes deleted rows properly
$(document).on("click", ".delete", function() {
$(this).closest("tr").remove();

// Check if there are any product rows left
const productRows = $(".item-row").length;
if (productRows === 0) {
$("#has_products").val("0");
}

// Recalculate totals
calculateTotals();
});

// Recalculate totals when vat or discount changes
$("#vat, #discount, #pay").on('change keyup', function() {
calculateTotals();
});

// Update row totals when quantity or price changes
$(document).on("change keyup", ".qty, .price", function() {
const row = $(this).closest("tr");
const qty = parseFloat(row.find(".qty").val()) || 0;
const price = parseFloat(row.find(".price").val()) || 0;
const total = qty * price;

row.find(".total").val(total.toFixed(2));
calculateTotals();
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