<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;

        }

        .receipt {
            width: 240px;
            display: block;
            margin: auto;
            border: 2px solid lightgray;
            padding: 10px;
            /*margin-top: 50px;*/
            /* background-color: lightgray; */
        }


        h2,
        h4,
        h5, p {
            margin-block-end: 0;
            margin-block-start: 0;
        }

        .row{
            overflow: hidden;
        }
        .row > .left {
            float: left;
            margin-left: 10px;
        }
        .row > .right {
            float: right;
            margin-right: 15px;
        }

    </style>
</head>

<body>


<div class="receipt">
    <p align="center">{{ $app_setting->app_name }}</p>
    <img
         src="data:image/png;base64, {!! DNS1D::getBarcodePNG("$warehouseStock->barcode_text", 'C39+', 1, 35) !!}"
         alt="barcode"/>
    <p>{{ $warehouseStock->medicine->medicine_name ?? '' }}</p>
    <p>Tk. <b>{{ $warehouseStock->price }}/-</b></p>
</div>


</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

@if ($app_setting->print == 1)
    <script>

        window.print();

        $(window).on('afterprint', function () {
            // Redirect back to the original page
            setTimeout(function () {
                window.close();
            }, 250);
        });
    </script>
@endif

</html>
