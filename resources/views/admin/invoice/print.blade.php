<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Invoice</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;

        }
        .receipt {
            width: 4in;
            display: block;
            margin: auto;
            border: 2px solid lightgray;
            padding: 10px;
            margin-top: 50px;
            /* background-color: lightgray; */
        }


        .header {
            text-align: center;
            margin: 10px 0;

        }
        .pos-logo{
            max-height: 100px;
            max-width: 300px;
            filter: grayscale(100%);
        }


        .footer {
            text-align: center;
            margin: 10px 0;
        }

        h2,
        h4,
        h5 {
            margin-block-end: 0;
            margin-block-start: 0;
        }

        .product-table,
        th, td {
            /* border: 1px solid black; */
            /* width: 100%; */
            border-collapse: collapse;
            text-align: center;
        }

        .product-table {
            width: 100%;
        }

        .product {
            text-align: left;
        }

        .payment-table {
            width: 100%;
        }

        thead {

        }

        tr {
            border-bottom: 1pt solid black;
        }

        .new {
            border-bottom: none;
        }
    </style>
</head>

<body>


<div class="receipt">
    <div class="header">
        @php
            $outlet = App\Models\Outlet::where('id',$outletInvoice->outlet_id)->first();
        @endphp

        <img class="pos-logo" src="{{asset('uploads/'.$app_setting->logo)}}" alt="">
        <p><b style="font-size: 20px">{{ $outletInvoice->outlet->outlet_name ?? '' }}</b> <br>
            {{ $outletInvoice->outlet->address ?? '' }} <br>
            {{ $outletInvoice->outlet->mobile ?? '' }}</p>


    </div>

    <div class="row">

        <div class="row">
            <table class="product-table">
                <thead>
                <tr class="new">


                <tr>
                </thead>
                <tr class="new">
                    <td style="width: 50px; border: none; text-align: left;">
                        Order ID: #{{ $outletInvoice->id }}
                    </td>
                    <td style="width: 50px; border: none; text-align: right;">

                        Date: {{ \Carbon\Carbon::parse($outletInvoice->created_at)->format('d-m-Y') }}
                    </td>
                </tr>
                <tr class="new">
                    <td style="width: 50px; border: none; text-align: left;"> Posted By: {{ $outletInvoice->user->name }}</td>

                    <td style="width: 50px; border: none; text-align: right;">Pay
                        Mode: {{ $outletInvoice->payment->method_name ?? '' }}</td>

                </tr>

            </table>
            <div class="col-mb-4">

            </div>


        </div>


    </div>
    <hr>
    <table class="product-table">
        <thead>
        <tr class="">

            <th class="product">Item</th>
            <th>Size</th>
            <th>Rate</th>
            <th>Qty</th>
            <th style="text-align: right; ">Amount</th>

        </tr>
        </thead>

        @foreach($outletInvoicedetails as $item)

            <tr>

                <td class="product"> {{ $loop->index+1 }}. {{ $item->medicine_name }} </td>
                <td>{{ $item->size }} </td>
                <td>{{ $item->rate }} </td>
                <td>{{ $item->quantity }} </td>
                <td style="text-align: right; ">{{ round($item->rate * $item->quantity) }} </td>
            </tr>

        @endforeach

    </table>

    <table class="payment-table">
        <tr>
            <td colspan="3" style="text-align: right; ">Sub Total:</td>
            <td style="width: 40px; border: none; text-align: right;"> {{ round($outletInvoice->sub_total) }} </td>

        </tr>
        <tr>
            <td colspan="3" style="text-align: right; border: none;">Discount:</td>
            <td style="width: 50px; border: none; text-align: right;"> {{ round($outletInvoice->total_discount) }} </td>

        </tr>
        <tr>
            <td colspan="3" style="text-align: right; border: none;">Vat:</td>
            <td style="width: 50px; border: none; text-align: right;"> {{ round($outletInvoice->vat) }} </td>

        </tr>
        <tr>
            <td colspan="3" style="text-align: right; border: none;"><b> Grand Total: </b></td>
            <td style="width: 50px; border: none; text-align: right;"><b> {{ round($outletInvoice->grand_total) }} </b>
            </td>

        </tr>
        <tr>
            <td colspan="3" style="text-align: right; border: none;"><b> Payable Amount: </b></td>
            <td style="width: 50px; border: none; text-align: right;"><b> {{ round($outletInvoice->payable_amount) }} </b>
            </td>

        </tr>
        <tr>
            <td colspan="3" style="text-align: right; border: none;">Given Amount:</td>
            <td style="width: 50px; border: none; text-align: right;"> {{ round($outletInvoice->given_amount) }} </td>
        </tr>
        @if($outletInvoice->given_amount > $outletInvoice->payable_amount)
            <tr>
                <td colspan="3" style="text-align: right; border: none;">Change Amount:</td>
                <td style="width: 50px; border: none; text-align: right;"> {{ round($outletInvoice->given_amount - $outletInvoice->payable_amount) }} </td>
            </tr>
        @endif



    </table>
    <hr>
    <table class="payment-table">
        <tr>

            <td style="text-align: left; border: none;">Customer Name:</td>
            <td colspan="3" style="border: none;" style=""><b>{{ $outletInvoice->customer->name }} </b></td>

        </tr>
        @if($outletInvoice->customer->name != 'Walking Customer')
        <tr>
            <td style="text-align: left; border: none;">Earned Points:</td>
            <td colspan="3" style="border: none; "><b>{{ $outletInvoice->earn_point }}</b></td>

        </tr>
        @if($outletInvoice->redeem_point > 0)
        <tr>
            <td style="text-align: left; border: none;">Your Redeem Points:</td>
            <td colspan="3" style="border: none;"><b> {{ $outletInvoice->redeem_point }} </b></td>

        </tr>
        @endif
        <tr>
            <td style="text-align: left; border: none;">Your Total Points:</td>
            <td colspan="3" style="border: none;"><b> {{ $outletInvoice->customer->points }} </b></td>

        </tr>
        @endif
    </table>
    <br>
    <div class="row justify-content-center mt-3">
        <img style="margin-left: 130px"
             src="data:image/png;base64, {!! DNS1D::getBarcodePNG("$outletInvoice->id", 'C39', 2) !!}" alt="barcode"/>
    </div>

    <div class="footer">
        <h4>A Concern of Stolen Group</h4>
        <h4>Thank You ❤</h4>
        <h5>Developed By: Tyrodevs.com</h5>
{{--        <h6>Software by Pigeon Soft</h6>--}}
    </div>


</div>


</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>

    window.print();

    $(window).on('afterprint', function () {
        // Redirect back to the original page
        setTimeout (function() {window.close();},250);
    });
</script>

</html>
