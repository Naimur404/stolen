@extends('admin.Pos.pos_master')
@section('title')
    POS
@endsection

@push('css')
<style>
    /* General Styling */
    .form-control, .form-select {
        border-color: #172d7b !important;
    }
    
    .form-control:disabled, .form-control[readonly] {
        border-color: #efeff1 !important;
    }
    
    /* Card Styling */
    .card .card-header {
        padding: 6px !important;
    }
    
    .card .card-body {
        padding: 10px !important;
    }
    
    /* Table Styling */
    .custom-td {
        padding: 5px !important;
        vertical-align: middle !important;
    }
    
    /* Header Adjustments */
    .page-main-header {
        max-height: 80px !important;
    }
    
    .page-main-header .main-header-right .main-header-left {
        border-right: none !important;
    }
    
    /* Input Focus */
    input:focus, textarea:focus, select:focus {
        outline: none;
    }
    
    /* Button Styling */
    .delete {
        color: #fff;
    }
</style>
@endpush

@section('content')
    @component('components.breadcrumb')
        @slot('breadcrumb_title')
            <div class="row">
                <div class="col-sm-6">
                    <h3>POS</h3>
                </div>
            </div>
        @endslot
    @endcomponent
    
    {!! Form::open([
        'id' => 'my-form',
        'class' => 'needs-validation',
        'novalidate' => '',
        'autocomplete' => 'off',
        'files' => true,
    ]) !!}

    <input type="hidden" name="outlet_id" value="{{ $outlet_id }}">
    <div class="row item-row-add">
        <!-- Left Column - Product Selection & Details -->
        <div class="col-md-8 col-lg-8">
            <div class="card">
                <div class="card-body mt-3">
                    <!-- Product Search & Barcode Section -->
                    <div class="form-group row">
                        <div class="col-md-4">
                            {{ Form::select('medicine_id', [], null, [
                                'class' => 'form-control', 
                                'placeholder' => 'Select Product', 
                                'id' => 'medicine_id'
                            ]) }}
                        </div>
                        
                        <div class="col-md-1">
                            <button type="button" class="btn btn-primary btn-xs addProductRow mt-2" id="addProductRow">
                                Add
                            </button>
                        </div>
                        
                        <div class="col-md-3">
                            {!! Form::text('medicine', null, [
                                'class' => 'form-control', 
                                'placeholder' => 'Barcode', 
                                'id' => 'barcode'
                            ]) !!}
                        </div>
                        
                        <div class="col-md-4">
                            <p class="btn btn-air-info mb-3" style="margin-left:1px">
                                {{ Carbon\Carbon::today()->format('d M y') }}</p>
                            <p class="btn btn-air-info mb-3" style="margin-left:4px" id="time"></p>
                        </div>
                    </div>

                    <!-- Product Table Section -->
                    <div class="card">
                        <div class="card-header bg-primary">
                            <i class="fa fa-table"></i> Item Details
                        </div>
                        <div class="card-body">
                            <div class="table-responsive pt-2">
                                <table class="table table-striped" id="purchaseTable">
                                    <thead>
                                        <tr class="item-row">
                                            <th class="text-center">
                                                <nobr>Product<i class="text-danger">*</i></nobr>
                                            </th>
                                            <th class="text-center">
                                                <nobr>Size<i class="text-danger">*</i></nobr>
                                            </th>
                                            <th class="text-center">
                                                <nobr>Stock <i class="text-danger">*</i></nobr>
                                            </th>
                                            <th class="text-center">
                                                <nobr>Qty<i class="text-danger">*</i></nobr>
                                            </th>
                                            <th class="text-center">
                                                <nobr>Price<i class="text-danger">*</i></nobr>
                                            </th>
                                            <th class="text-center">
                                                <nobr>Discount<i class="text-danger">*</i></nobr>
                                            </th>
                                            <th class="text-center">
                                                <nobr>Total Price</nobr>
                                            </th>
                                            <th class="text-center">
                                                <nobr>Remarks</nobr>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Product items will be added here dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Customer Details Section -->
                    <div class="card-header bg-primary mt-4">
                        <i class="fa fa-table"></i> Customer Details
                    </div>
                    <div class="form-group row mt-3">
                        <div class="col-md-3">
                            {!! Form::label('mobile', 'Select Customer', ['class' => 'form-label']) !!}
                            {{ Form::select('mobile', [], null, [
                                'class' => 'form-control add', 
                                'placeholder' => 'Walking Customer', 
                                'id' => 'user_id'
                            ]) }}
                        </div>
                        
                        <div class="col-md-1">
                            <button class="btn btn-primary btn-xs adduser" style="margin-top: 32px" id="adduser" type="button">
                                <i data-feather="arrow-right-circle" class="mt-1"></i>
                            </button>
                        </div>
                        
                        <div class="col-md-3">
                            {!! Form::label('name', 'Customer Name', ['class' => 'form-label']) !!}
                            {!! Form::text('name', null, [
                                'class' => 'form-control',
                                'id' => 'name',
                                'placeholder' => 'Enter Customer Name',
                                'required',
                            ]) !!}
                        </div>

                        <div class="col-md-2">
                            {!! Form::label('birth_date', 'Birth Date', ['class' => 'form-label']) !!}
                            <input class="datepicker-here form-control digits" type="text" data-language="en"
                                data-bs-original-title="" title="" id="birth_date" name="birth_date" value=""
                                tabindex="2" required placeholder="dd-mm-yyyy">
                        </div>
                        
                        <div class="col-md-2">
                            {!! Form::label('points', 'Points', ['class' => 'form-label']) !!}
                            {!! Form::number('points', null, [
                                'class' => 'form-control',
                                'id' => 'points',
                                'placeholder' => '0',
                                'step' => '0.1',
                                'readonly',
                            ]) !!}
                        </div>
                        
                        <div class="col-md-6 mt-3">
                            {!! Form::label('address', 'Address', ['class' => 'form-label']) !!}
                            {!! Form::textarea('address', old('address', $outlet->address ?? ''), [
                                'class' => 'form-control' . ($outlet->is_active_courier_gateway == 1 && $app_setting->courier_gateway == 'pathao' ? ' hello24' : ''),
                                'id' => 'address',
                                'placeholder' => 'Enter Address',
                                'rows' => 3
                            ]) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-danger pull-right" id="has_due_text"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column - Invoice & Payment -->
        <div class="col-md-4 col-lg-4">
            <div class="card">
                <div class="card-header bg-primary">
                    <i class="fa fa-table"></i> Invoice
                </div>

                <div class="card-body">
                    <div class="table-responsive pt-2">
                        <table class="table table-striped" id="purchaseTableww">
                            <tbody>
                                <tr>
                                    <td class="text-right" colspan="7"><b>Sub total:</b></td>
                                    <td></td>
                                    <td class="text-right">
                                        <input type="number" class="form-control text-right fw-bold text-primary"
                                            name="sub_total" id="subtotal" readonly>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="text-right" colspan="7"><b>Discount:</b></td>
                                    <td></td>
                                    <td>
                                        <input type="number" id="discount" class="text-right form-control discount"
                                            name="discount" value="0" tabindex="17" step="any"
                                            placeholder="Tk" onclick="clearInput1(this)"/>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="text-right" colspan="7"><b>Flat Discount:</b></td>
                                    <td class="text-right">
                                        <input type="number" id="discount_percent" max="100"
                                            class="text-right form-control" placeholder="%" />
                                    </td>
                                    <td>
                                        <input type="number" id="flatdiscount"
                                            class="text-right form-control flatdiscount" name="flatdiscount"
                                            value="0" tabindex="17" step="any" placeholder="Tk" onclick="clearInput2(this)"/>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-right" colspan="8"><b>After All Discount:</b></td>
                                    <td class="text-right">
                                        <input type="number"
                                            class="form-control text-right afterdis fw-bold text-primary"
                                            name="afterdis" id="afterdis" readonly>
                                        <input type="hidden" id="vat_percent" max="100"
                                            class="text-right form-control" />
                                        <input type="hidden" id="vat" class="text-right form-control clearVat"
                                            name="vat" value="0" step="any" placeholder="Tk" />
                                        @if ($outlet->is_active_courier_gateway == 0)
                                            <input type="hidden" class="form-control text-right text-success fw-bold"
                                                name="delivery" id="delivery" value="0" step="any"
                                                placeholder="Tk" required>
                                        @endif
                                    </td>
                                </tr>
                                
                                @if ($outlet->is_active_courier_gateway == 1)
                                    <tr>
                                        <td class="text-right" colspan="8"><b>Delivery Charge:</b></td>
                                        <td class="text-right">
                                            <input type="number" class="form-control text-right text-success fw-bold"
                                                name="delivery" id="delivery" value="0" step="any"
                                                placeholder="Tk" required>
                                        </td>
                                    </tr>
                                @endif
                                
                                <tr>
                                    <td class="text-right" colspan="8"><b>Grand Total:</b></td>
                                    <td class="text-right">
                                        <input type="number" class="form-control text-right text-success fw-bold"
                                            name="grand_total" id="grandTotal" readonly="readonly" required>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-right" colspan="8"><b>Point Redeem:</b></td>
                                    <td class="text-right">
                                        <input type="number" class="form-control text-right redeem_points"
                                            name="redeem_points" id="redeem_points" onkeyup="prevent_points_amount()"
                                            onchange="prevent_points_amount()">
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-right" colspan="8"><b>Payable Amount:</b></td>
                                    <td class="text-right">
                                        <input type="number" class="form-control text-right text-danger fw-bold"
                                            name="payable_amount" id="payable_amount" readonly="readonly" required>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-right" colspan="8"><b>Given Amount:</b></td>
                                    <td class="text-right">
                                        <input type="number" id="pay" class="text-right form-control"
                                            name="paid_amount" value="0" onkeyup="prevent_paid_amount()"
                                            onchange="prevent_paid_amount()" tabindex="16" step=".01"
                                            required onclick="clearInput3(this)"/>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td class="text-right" colspan="8"><b>Back Amount:</b></td>
                                    <td class="text-right">
                                        <input type="number" id="back" class="text-right form-control back"
                                            name="back_amount" value="0" readonly="readonly" />
                                        <input type="hidden" id="due" class="text-right form-control"
                                            name="due_amount" value="0" readonly="readonly" />
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-right" colspan="8"><b>Total Items:</b></td>
                                    <td class="text-right">
                                        <input type="number" id="item" class="text-right form-control"
                                            name="item" value="0" readonly="readonly" />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Payment Method Section -->
                    <div class="form-group mt-3">
                        <label for="payment_type" class="text-right col-form-label">Payment Method</label>
                        {{ Form::select('payment_method_id', $payment_methods, null, [
                            'class' => 'form-control', 
                            'required'
                        ]) }}
                        <div class="invalid-feedback">Please Add Payment Type</div>

                        <div class="card-footer text-end mt-3">
                            <div class="btn-group">
                                <button type="button" class="btn btn-info btn-sm" onclick="lastprint()">
                                    Recent Invoice
                                </button>
                                &nbsp;
                                <button type="button" class="btn btn-primary btn-sm" onclick="submitForm()">
                                    Save & Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
    
    <!-- Loader Animation -->
    <div id="loader" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); display: flex; align-items: center; justify-content: center; z-index: 9999;">
        <div id="lottie-loader"></div>
    </div>

@push('scripts')
    <script src="{{ asset('assets/js/notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('assets/js/pos.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.13/lottie.min.js"></script>

    <script type="text/javascript">
        // Initialize loader animation
        $('#loader').hide();
        var animation = lottie.loadAnimation({
            container: document.getElementById('lottie-loader'),
            renderer: 'svg',
            loop: true,
            autoplay: true,
            path: '/animations/loader.json'
        });

        // Clock function
        function updateClock() {
            const now = new Date();
            const hours = now.getHours() % 12 || 12;
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
            document.getElementById('time').innerHTML = `${hours}:${minutes}:${seconds} ${ampm}`;
        }
        
        setInterval(updateClock, 1000);
        updateClock(); // Initial call

        // Stock validation
        $("#purchaseTable").on("change", "#saleRow", function() {
            let stock = $(this).find('.stock-qty').val();
            let qty = $(this).find('.qty').val();
            
            if (parseInt(qty) > parseInt(stock)) {
                alert("Quantity not more than Stock amount.");
                $(this).find(".qty").val("");
            }
        });

        // Points validation
        function prevent_points_amount() {
            var cuspoint = $("#points").val();
            var point = $("#redeem_points").val();
            
            if (parseInt(point) > parseInt(cuspoint)) {
                alert("Redeem Points not more than Customer Point.");
                $("#redeem_points").val("");
            } else if (cuspoint == '' || cuspoint == 0) {
                $("#redeem_points").val("");
                alert("Redeem Points not more than Customer Point.");
            }
        }

        // Clear input fields when clicked
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

        function clearInput3(target) {
            if (target.value == '0') {
                target.value = "";
            }
        }

        // Debounce function for address input
        function debounce(func, delay) {
            let debounceTimer;
            return function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => func.apply(this, arguments), delay);
            };
        }

        // Address parsing for Pathao delivery
        const inputElement = document.querySelector('.hello24');
        if (inputElement) {
            inputElement.addEventListener('input', debounce(function() {
                const address = this.value.trim();
                if (address) {
                    fetchAddressData(address);
                }
            }, 1000));
        }

        function fetchAddressData(address) {
            $('#loader').show();
            fetch('/parse-address', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ address: address })
            })
            .then(response => response.json())
            .then(data => {
                $('#loader').hide();
                if (data.type === 'success') {
                    const city = data.data.district_name;
                    const zone = data.data.zone_name;
                    const area = data.data.area_name;

                    Swal.fire({
                        title: 'Delivery Address In Pathao',
                        text: `${city} > ${zone} > ${area}`,
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Could not fetch address data. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                $('#loader').hide();
                console.error('Error fetching address data:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'There was an error processing your request.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }

        // Form submission handler
        function submitForm() {
            var pay = parseInt($("#pay").val()) || 0;
            var customer_name = $("#name").val().toLowerCase();
            let amount = parseInt($("#payable_amount").val()) || 0;
            var item = $("#item").val();
            
            if (item == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please Select At List One Item',
                });
                return;
            }
            
            if ((customer_name === 'walking customer' || customer_name === '') && pay < amount) {
                Swal.fire(
                    'Payment Required',
                    'This customer is not allowed for due',
                    'warning'
                );
                return;
            }
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                showCancelButton: true,
                confirmButtonText: 'Yes',
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('my-form');
                    const formData = new FormData(form);

                    $.ajax({
                        url: "{{ route('invoice.store') }}",
                        method: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('#loader').show();
                        },
                        success: function(response) {
                            $('#loader').hide();
                            var datas = response.data;
                            var url = "{{ route('print-invoice', ':id') }}";
                            url = url.replace(':id', datas.id);
                            window.open(url, "_blank");
                            window.location.href = "{{ route('invoice.create') }}";
                        },
                        error: function(xhr) {
                            $('#loader').hide();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to process invoice. Please try again.',
                            });
                        }
                    });
                }
            });
        }

        // Print last invoice
        function lastprint() {
            const form = document.getElementById('my-form');
            const formData = new FormData(form);

            $.ajax({
                url: "{{ route('last-invoice.print') }}",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loader').show();
                },
                success: function(response) {
                    $('#loader').hide();
                    var datas = response.data;
                    var url = "{{ route('print-invoice', ':id') }}";
                    url = url.replace(':id', datas.id);
                    window.open(url, "_blank");
                },
                error: function(xhr) {
                    $('#loader').hide();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to retrieve last invoice.',
                    });
                }
            });
        }

        $(document).ready(function() {
            // Prevent form submission on Enter key
            $(window).keydown(function(event) {
                if (event.keyCode == 13) {
                    event.preventDefault();
                    return false;
                }
            });

            // Clear input values when clicked
            $(".clearVat, #discount, #pay, .flatdiscount").on('click', function() {
                let input = $(this).val();
                if (input == 0) {
                    $(this).val('');
                }
            });

            // Percentage calculations
            $("#vat_percent").bind('keypress keyup keydown mouseup', function() {
                let vat = $(this).val();
                let subTotal = $("#afterdis").val();
                let totalVat = calculatePercentage(subTotal, vat);
                $("#vat").val(totalVat);
            });

            $("#discount_percent").on('keyup', function() {
                let vat = $(this).val();
                let subTotal = $("#afterdis").val();
                let totalVat = calculatePercentage(subTotal, vat);
                $("#flatdiscount").val(totalVat);
            });

            function calculatePercentage(subTotal, vat) {
                let result = subTotal * (vat / 100);
                return result.toFixed(2);
            }

            // Select2 for customer search
            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $("#user_id").select2({
                tags: true,
                ajax: {
                    url: "{!! url('get-user') !!}",
                    type: "get",
                    dataType: 'json',
                    data: function(params) {
                        return {
                            _token: CSRF_TOKEN,
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

            // Select2 for product search
            $("#medicine_id").select2({
                ajax: {
                    url: "{!! url('get-outlet-Stock') !!}",
                    type: "get",
                    dataType: 'json',
                    data: function(params) {
                        return {
                            _token: CSRF_TOKEN,
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

            // Customer selection handler
            let user_id = '';
            $("#user_id").on('change', function() {
                user_id = $(this).val();
                $("#adduser").click();
            });

            // Get customer details
            $("#adduser").on('click', function() {
                if (user_id) {
                    $.ajax({
                        url: "{!! url('get-user-details') !!}" + "/" + user_id,
                        type: "GET",
                        dataType: "json",
                        beforeSend: function() {
                            $('#loader').show();
                        },
                        success: function(data) {
                            $('#loader').hide();
                            if (data != null) {
                                $('#name').val(data.name);
                                $('#address').val(data.address);
                                $('#points').val(data.points);
                                $('#birth_date').val(data.birth_date);
                                
                                if (data.due_balance > 0) {
                                    $('#has_due_text').html('Previous Due: <b> BDT ' + data.due_balance +
                                        ' Taka</b> To pay <a href="/customer-due/' + data.id + 
                                        '" target="_blank">Click here</a>');
                                } else {
                                    $('#has_due_text').text('');
                                }
                            } else {
                                alert('Data not found!');
                            }
                        },
                        error: function() {
                            $('#loader').hide();
                            alert('Error fetching customer data');
                        }
                    });
                } else {
                    alert("Please Select User");
                }
            });

            // Product selection handler
            let medicine_id = '';
            $('#medicine_id').on('select2:select', function(e) {
                medicine_id = $(this).val();
                $("#addProductRow").click();
            });

            // Barcode scanner handler
            $("#barcode").keyup(function(e) {
                if (e.which == 13) {
                    medicine_id = $(this).val();
                    $("#addProductRow").click();
                    $("#barcode").val('');
                }
                e.preventDefault();
            });

            // Add product row
            $("#addProductRow").click(function() {
                if (medicine_id) {
                    $.ajax({
                        url: "{!! url('get-medicine-details-for-sale') !!}" + "/" + medicine_id,
                        type: "GET",
                        dataType: "json",
                        beforeSend: function() {
                            $('#loader').show();
                        },
                        success: function(data) {
                            $('#loader').hide();
                            if (data != null) {
                                $('.stock_id').first().val(data.id);
                                $('.pr_id').first().val(data.medicine_id);
                                $('.size').first().val(data.size);
                                $('#product_name').first().val(data.medicine_name);
                                $('.stock-qty').first().val(data.quantity);
                                $('.qty').first().val('1');
                                $('#price').first().val(data.price);
                                $('.total').first().val(1 * data.price);
                            } else {
                                alert('Data not found!');
                            }
                        },
                        error: function() {
                            $('#loader').hide();
                            alert('Error fetching product data');
                        }
                    });
                } else {
                    alert("Please Select Medicine Name");
                }
            });

            // Purchase invoice generator
            jQuery().invoice({
                addRow: "#addRow",
                addProductRow: "#addProductRow",
                delete: ".delete",
                parentClass: ".item-row",
                price: ".price",
                qty: ".qty",
                Quantity: "#Quantity",
                total: ".total",
                totaldis: ".totaldis",
                deliveryCharge: "#delivery",
                subtotal: "#subtotal",
                discount: "#discount",
                flatdiscount: "#flatdiscount",
                discountt: "#discountt",
                vat: "#vat",
                grandTotal: "#grandTotal",
                payable_amount: "#payable_amount",
                afterdis: "#afterdis",
                pay: "#pay",
                back: ".back",
                points: "#redeem_points",
                item: "#item",
                due: "#due"
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