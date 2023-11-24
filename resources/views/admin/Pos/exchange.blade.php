@extends('layouts.admin.master')

@section('title')Exchange

@endsection
    @section('content')
        <div class="container">
            <h3>Exchange Products</h3>

            <div class="form-group">
                <label for="invoice_id">Invoice ID</label>
                <input type="text" class="form-control" id="invoice_id" placeholder="Enter Invoice ID" required>
            </div>

            <div class="form-group">
                <label for="product_select">Select Product to Exchange</label>
                <select class="form-control" id="product_select" required>
                    <option value="" disabled selected>Select Product</option>
                    <!-- Products will be loaded dynamically via AJAX -->
                </select>
                <button type="button" class="btn btn-success mt-2" onclick="addSelectedProduct()">Add Selected Product</button>
            </div>

            <div class="form-group">
                <label for="new_product_name">Add New Product</label>
                <select id="medicine_id" class="form-control">
                    <!-- Medicine details will be loaded dynamically via AJAX -->
                    <option value="" disabled selected>Select Product</option>
                </select>
                <button type="button" class="btn btn-success mt-2" onclick="addNewProduct()">Add New Product</button>
                <div id="medicine_details"></div>
            </div>

            <!-- Table to display selected products -->
            <p style="text-align: center; color: green;" class="mt-2"><b>Exchange Product</b></p>
            <div class="table-responsive pt-2">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Size</th>
                    <th>Available Qty</th>
                    <th>QTY</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="selected_products_table">
                <!-- Selected products will be dynamically added here -->
                </tbody>
            </table>
                <p style="text-align: center; color: red;" class="mt-2"><b>New Product</b></p>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Size</th>
                    <th>Available Qty</th>
                    <th>QTY</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody id="selected_products_table2">
                <!-- Selected products will be dynamically added here -->
                </tbody>
            </table>
            </div>

            <button type="button" class="btn btn-primary mt-2" onclick="submitExchange()">Submit Exchange</button>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            var selectedProducts = [];
            // Load products for the selected invoice
            $("#invoice_id").change("input", function () {
                var invoiceId = $(this).val();

                // Fetch products via AJAX and populate the product_select dropdown
                $.ajax({
                    url: "/get-products/" + invoiceId, // Replace with your actual route
                    type: "GET",
                    success: function (data) {
                        var options = "";
                        data.forEach(function (product) {
                            options += `<option value="${product.medicine_id}" data-name="${product.medicine_name}" data-size="${product.size}" data-qty ="${product.quantity}" data-rate="${product.rate}" data-available="${product.available_qty}">${product.medicine_name} - ${product.size}</option>`;
                        });
                        $("#product_select").html(options);
                    },
                    error: function (xhr, status, errorThrown) {
                        // Attempt to parse the response text as JSON
                        var errorData = {};
                        try {
                            errorData = JSON.parse(xhr.responseText);
                        } catch (e) {
                            errorData = { error: 'An error occurred, but the server did not provide details.' };
                        }

                        Swal.fire(errorData.error)
                    },
                });
            });

            // Initialize select2 for the medicine_id dropdown
            $("#medicine_id").select2({
                ajax: {
                    url: "{!! url('get-outlet-Stock') !!}",
                    type: "get",
                    dataType: 'json',
                    data: function (params) {
                        return {
                            // _token: CSRF_TOKEN,
                            search: params.term,
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

            let medicine_id = '';

            // Handle selection in medicine_id dropdown
            $('#medicine_id').on('select2:select', function (e) {
                medicine_id = $(this).val();
            });

            // Handle keyup event for the barcode input
            $("#barcode").keyup(function (e) {
                if (e.which == 13) {
                    medicine_id = $(this).val();
                    $("#addProductRow").click();
                    $("#barcode").val('');
                } else {
                    // Do whatever you like
                }
                e.preventDefault();
            });

            // Add new product based on selected medicine_id
            {{--$("#addProductRow").click(function () {--}}
            {{--    if (medicine_id) {--}}
            {{--        $.ajax({--}}
            {{--            url: "{!! url('get-medicine-details-for-sale') !!}" + "/" + medicine_id,--}}
            {{--            type: "GET",--}}
            {{--            dataType: "json",--}}
            {{--            beforeSend: function () {--}}
            {{--                // You can add loading indicators or other UI changes here--}}
            {{--            },--}}
            {{--            success: function (data) {--}}
            {{--                // Populate the medicine_details div with fetched data--}}
            {{--                $("#medicine_details").html(`--}}
            {{--                <p>Product ID: ${data.id}</p>--}}
            {{--                <p>Name: ${data.name}</p>--}}
            {{--                <p>Size: ${data.size}</p>--}}
            {{--                <!-- Add more details as needed -->--}}
            {{--            `);--}}
            {{--            },--}}
            {{--            error: function () {--}}
            {{--                alert('Data not found!');--}}
            {{--            },--}}
            {{--            complete: function () {--}}
            {{--                // You can perform cleanup or other actions here--}}
            {{--            }--}}
            {{--        });--}}
            {{--    } else {--}}
            {{--        alert("Please Select Medicine Name");--}}
            {{--    }--}}
            {{--});--}}

                // Add selected product to the table
                function addSelectedProduct() {
                    var selectedProductId = $("#product_select").val();
                    var selectedProduct = $("#product_select option:selected");
                    var productName = selectedProduct.data("name");
                    var productSize = selectedProduct.data("size");
                    var avaiableQty = selectedProduct.data("available");
                    var productQty = selectedProduct.data("qty");
                    var productPrice = selectedProduct.data("rate")

                    console.log(avaiableQty);



                    // Append a new row to the table
                    $("#selected_products_table").append(`
                <tr>
                    <td>${selectedProductId}</td>
                    <td>${productName}</td>
                    <td>${productSize}</td>
                    <td>${avaiableQty}</td>
                    <td><input type="number" class="form-control newtest" id="qtytest2"
                                                               name="qty"
                                                               placeholder="Enter unit" value="${productQty}" required></td>
                    <td><input type="number" class="form-control newtest" id="testprice2"
                                                               name="qty"
                                                               placeholder="Enter unit" value="${productPrice}" required readonly></td>
<td><button type="button" class="btn btn-danger" onclick="deleteProduct1(this)">Delete</button></td>
                </tr>
            `);

                }

                // Add new product to the table
                function addNewProduct() {

                    $.ajax({
                        url: "{!! url('get-medicine-details-for-sale') !!}" + "/" + medicine_id,
                        type: "GET",
                        dataType: "json",
                        beforeSend: function () {
                            // You can add loading indicators or other UI changes here
                        },
                        success: function (data) {
                            // Populate the medicine_details div with fetched data
                            $("#selected_products_table2").append(`
                <tr>
                    <td>${data.medicine_id}</td>
                    <td>${data.medicine_name}</td>
                    <td>${data.size}</td>
                    <td>${data.quantity}</td>
                    <td><input type="number" class="form-control newtest" id="qtytest"
                                                               name="qty"
                                                               placeholder="Enter unit" value="1" required></td>
                    <td><input type="number" class="form-control newtest" id="testprice"
                                                               name="qty"
                                                               placeholder="Enter unit" value="${data.price}" required></td>
<td><button type="button" class="btn btn-danger" onclick="deleteProduct2(this)">Delete</button></td>
                </tr>
            `);
                            var newProduct = {
                                id: (data.medicine_id),
                                name: 'test',
                                size: 'N/A', // You may modify this based on your requirements
                            };

                            // Add the new product to the data structure
                            selectedProducts.push(newProduct);
                        },
                        error: function () {
                            alert('Data not found!');
                        },
                        complete: function () {
                            // You can perform cleanup or other actions here
                        }
                    });
                }
                    // var newProductName = $("#new_product_name").val();

                    // Append a new row to the table




                // Submit the exchange request
            function submitExchange() {
                // Prepare data for submission

                var exchangeData = {
                    invoice_id: $("#invoice_id").val(),
                    exchange_products: getExchangeProducts(),
                    new_products: getNewProducts(),
                };

            if (exchangeData.exchange_products.length === 0 || exchangeData.new_products.length === 0){

                    Swal.fire('No Exchange or New Product Selected, Please Select First');
                }
              else  if (exchangeData.exchange_products.length > 1 || exchangeData.new_products.length > 1){

                    Swal.fire('Only one Product can exchange at a time');
                }
            else  if(exchangeData.new_products[0].qty < 1 ){
                    Swal.fire('New Product Quantity Can not be Empty');
                }else if(exchangeData.exchange_products[0].avalqty < exchangeData.exchange_products[0].qty || exchangeData.new_products[0].avalqty < exchangeData.new_products[0].qty)  {
                    Swal.fire('Product Quantity not more then Available Quantity');
                }
               else if (exchangeData.exchange_products[0].total_price > exchangeData.new_products[0].total_price){
                    Swal.fire('Exchange price not Less then new Product price');
                }

               else {

                   if(exchangeData.new_products[0].total_price > exchangeData.exchange_products[0].total_price){

                       const price = exchangeData.new_products[0].total_price - exchangeData.exchange_products[0].total_price
                       let alert_text = '';

                       alert_text = "Please Take TK " +price+ " from customer, "+"You won't be able to revert this!";

                       Swal.fire({
                           title: 'Are you sure?',
                           text: alert_text,
                           showCancelButton: true,
                           confirmButtonText: 'Yes',
                       }).then((result) => {
                           if (result.isConfirmed) {
                               $.ajax({
                                   url: "/exchange", // Replace with your actual route
                                   type: "POST",
                                   data: {
                                       _token: "{{ csrf_token() }}",
                                       exchangeData
                                   },
                                   dataType: "json",
                                   success: function (response) {
                                       var datas = response.data;
                                       var url = "{{ route('print-invoice-exchange', ':id') }}";
                                       url = url.replace(':id', datas);
                                       window.open(url, "_blank");
                                       window.location.href = "{{ route('exchange.index') }}";

                                       $("#selected_products_table").empty();
                                       $("#selected_products_table2").empty();

                                       // Reset the selectedProducts array after submission
                                       selectedProducts = [];
                                   },
                                   error: function () {
                                       var errorData = {};
                                       try {
                                           errorData = JSON.parse(xhr.responseText);
                                       } catch (e) {
                                           errorData = { error: 'An error occurred, but the server did not provide details.' };
                                       }

                                       Swal.fire(errorData.error)
                                   },
                               });
                           } else if (result.isDenied) {
                               Swal.fire('Changes are not saved', '', 'info')
                           }
                       })
                   }else{


                       let alert_text = '';

                       alert_text = "You won't be able to revert this!";

                       Swal.fire({
                           title: 'Are you sure?',
                           text: alert_text,
                           showCancelButton: true,
                           confirmButtonText: 'Yes',
                       }).then((result) => {
                           if (result.isConfirmed) {
                               $.ajax({
                                   url: "/exchange", // Replace with your actual route
                                   type: "POST",
                                   data: {
                                       _token: "{{ csrf_token() }}",
                                       exchangeData
                                   },
                                   dataType: "json",
                                   success: function (response) {
                                       var datas = response.data;
                                       var url = "{{ route('print-invoice-exchange', ':id') }}";
                                       url = url.replace(':id', datas);
                                       window.open(url, "_blank");
                                       window.location.href = "{{ route('exchange.index') }}";

                                       $("#selected_products_table").empty();
                                       $("#selected_products_table2").empty();

                                       // Reset the selectedProducts array after submission
                                       selectedProducts = [];
                                   },
                                   error: function () {
                                       var errorData = {};
                                       try {
                                           errorData = JSON.parse(xhr.responseText);
                                       } catch (e) {
                                           errorData = { error: 'An error occurred, but the server did not provide details.' };
                                       }

                                       Swal.fire(errorData.error)
                                   },
                               });
                           } else if (result.isDenied) {
                               Swal.fire('Changes are not saved', '', 'info')
                           }
                       })
                   }




                }

                // Your AJAX code to submit the exchange request


                // For demonstration purposes, let's clear the selected products table

            }

            function getExchangeProducts() {
                var exchangeProducts = [];
                $("#selected_products_table tr").each(function () {
                    var productId = $(this).find("td:first").text();
                    var productName = $(this).find("td:nth-child(2)").text();
                    var productSize = $(this).find("td:nth-child(3)").text();
                    var availableqty = $(this).find("td:nth-child(4)").text();
                    var productQty = $("#qtytest2").val();
                    var productPrice = $("#testprice2").val();

                    exchangeProducts.push({
                        id: productId,
                        name: productName,
                        size: productSize,
                        avalqty: availableqty,
                        qty: productQty,
                        price: productPrice,
                        total_price: productPrice*productQty
                    });
                });


                return exchangeProducts;
            }
            function getNewProducts() {
                var newProducts = [];
                $("#selected_products_table2 tr").each(function () {
                    var productId = $(this).find("td:first").text();
                    var productName = $(this).find("td:nth-child(2)").text();
                    var productSize = $(this).find("td:nth-child(3)").text();
                    var availableqty = $(this).find("td:nth-child(4)").text();
                    var productQty = $("#qtytest").val();
                     var productPrice = $("#testprice").val();

                    newProducts.push({
                        id: productId,
                        name: productName,
                        size: productSize,
                        avalqty: availableqty,
                        qty: productQty,
                        price: productPrice,
                        total_price: productPrice*productQty
                    });

                });

                return newProducts;
            }
            function deleteProduct2(button) {
                // Get the row index
                var rowIndex = $(button).closest("tr").index();

                // Remove the row from the table
                $("#selected_products_table2 tr").eq(rowIndex).remove();

                // Remove the product from the selectedProducts array
                selectedProducts.splice(rowIndex, 1);
            }

            function deleteProduct1(button) {
                // Get the row index
                var rowIndex = $(button).closest("tr").index();

                // Remove the row from the table
                $("#selected_products_table tr").eq(rowIndex).remove();

                // Remove the product from the selectedProducts array
                selectedProducts.splice(rowIndex, 1);
            }

                // Fetch outlet stock products on page load
                // $(document).ready(function () {
                //     fetchOutletStockProducts("");
                // });
        </script>

    @push('scripts')
        <script src="{{asset('assets/js/jquery.ui.min.js')}}"></script>
        <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
        <script src="{{asset('assets/js/summernote/summernote.js')}}"></script>
        <script src="{{asset('assets/js/tooltip-init.js')}}"></script>
        <script src="{{asset('assets/js/summernote/summernote.custom.js')}}"></script>
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

    @endpush
    @endsection
