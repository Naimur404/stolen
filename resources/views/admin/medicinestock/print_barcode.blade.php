<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode</title>
    <style>
        @page {
            size: 38mm 25mm;
            margin: 0;
        }
        
        html, body {
            margin: 0;
            padding: 0;
            width: 38mm;
            height: 25mm;
            overflow: hidden;
        }

        .receipt {
            width: 38mm;
            height: 25mm;
            padding: 1mm;
            box-sizing: border-box;
            page-break-after: avoid;
            page-break-inside: avoid;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .receipt img {
            display: block;
            max-width: 18mm;
            max-height: 18mm;
            width: auto;
            height: auto;
            margin: 0 auto;
        }

        p {
            margin: 0;
            padding: 0;
            text-align: center;
            font-size: 8pt;
            line-height: 1;
        }

        .app-name {
            font-size: 6pt;
            margin-bottom: 0.5mm;
        }

        .medicine-name {
            margin-top: 0.5mm;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 36mm;
        }

        .price {
            font-size: 9pt;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="receipt">
        <p class="app-name">{{ $outletStock->medicine->medicine_name ?? '' }} ({{$outletStock->size}})</p>
        <img
            src="data:image/png;base64, {!! DNS2D::getBarcodePNG("$outletStock->barcode_text", 'QRCODE', 3, 3) !!}"
            alt="qrcode"/>
        <p class="price">Tk {{ $outletStock->price }}/-</p>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    {{-- @if ($app_setting->print == 1) --}}
        <script>
            window.print();

            $(window).on('afterprint', function () {
                // Redirect back to the original page
                setTimeout(function () {
                    window.close();
                }, 250);
            });
        </script>
    {{-- @endif --}}
</body>
</html>