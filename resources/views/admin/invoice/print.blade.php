<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lazz Pharma Limited</title>
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
tr{
    border-bottom: 1pt solid black;
}
.new{
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

            <p><b style="font-size: 25px">Lazz Pharma Limited</b> <br> {{ $outlet->outlet_name }}<br>
                {{ $outlet->address }} <br>
                {{ $outlet->mobile }}</p>


    </div>

    <div class="row">

        <div class="row">
            <table class="product-table">
                <thead>
                    <tr class="new">


<tr>
                </thead>
                <tr class="new"><td style="width: 50px; border: none; text-align: left;">
                    Order ID: #{{ $outletInvoice->id }}
</td>
<td style="width: 50px; border: none; text-align: right;">

                Date: {{ \Carbon\Carbon::parse($outletInvoice->created_at)->format('d-m-Y') }}
</td>
</tr>
<tr class="new">
@php
$payment = App\Models\PaymentMethod::where('id',$outletInvoice->payment_method_id)->first();
@endphp

<td style="width: 50px; border: none; text-align: left;"> Posted By: {{ Auth::user()->name }}</td>

 <td style="width: 50px; border: none; text-align: right;">Pay Mode: {{ $payment->method_name  }}</td>

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
            <th>Rate</th>
            <th>Qty</th>
            <th style="text-align: right; ">Amount</th>

        </tr>
    </thead>

        @foreach($outletInvoicedetails as $item)

            <tr>

                <td class="product"> {{ $loop->index+1 }}. {{ $item->medicine_name }} </td>
                <td>{{ $item->rate }} </td>
                <td>{{ $item->quantity }} </td>
                <td style="text-align: right; ">{{ $item->rate * $item->quantity }} </td>
            </tr>

        @endforeach

    </table>

    <table class="payment-table">
        <tr>
            <td colspan="3" style="text-align: right; ">Sub Total: </td>
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
            <td style="width: 50px; border: none; text-align: right;"><b> {{ round($outletInvoice->grand_total) }} </b></td>

        </tr>
        <tr>
            <td colspan="3" style="text-align: right; border: none;">Payment: </td>
            <td style="width: 50px; border: none; text-align: right;"> {{ round($outletInvoice->paid_amount) }} </td>

        </tr>


    </table>
    <hr>
    <table class="payment-table">
        <tr>
            @php
            $customer = App\Models\Customer::where('id',$outletInvoice->customer_id)->first();
           @endphp


            <td style="text-align: left; border: none;">Customer Name: </td>
            <td colspan="3" style="border: none;" style=""> <b>{{ $customer->name }} </b></td>

        </tr>

        <tr>

            <td style="text-align: left; border: none;">Your Previous Points: </td>
            <td colspan="3" style="border: none;"> <b>{{  $customer->points - $outletInvoice->earn_point }} </b></td>

        </tr>
        <tr>
            <td style="text-align: left; border: none;">Your Todays Points: </td>
            <td colspan="3" style="border: none; "> <b>{{ $outletInvoice->earn_point }}</b> </td>

        </tr>
        <tr>
            <td style="text-align: left; border: none;">Your Totals Points: </td>
            <td colspan="3" style="border: none;"><b> {{ $customer->points }} </b></td>

        </tr>
    </table>
    <br>
    <div class="row justify-content-center mt-3">
        <img style="margin-left: 125px" src="data:image/png;base64, {!! DNS1D::getBarcodePNG("$outletInvoice->id", 'C39+') !!}" alt="barcode"   />
    </div>

    <div class="footer">
        <h4>Thank You ❤</h4>
        <h6>Developed by Pigeon Soft</h6>
    </div>



</div>


</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>

window.print();

$(window).on('afterprint', function() {
  // Redirect back to the original page
  window.location.href = "{{ route('invoice.create') }}";
});
</script>

</html>
