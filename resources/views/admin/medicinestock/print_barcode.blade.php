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
            font-family: Arial, sans-serif;
            transform: rotate(180deg); /* Rotate content to fix upside-down printing */
        }

        .receipt {
            width: 38mm;
            height: 25mm;
            padding: 0;
            box-sizing: border-box;
            page-break-after: always;
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
            margin-bottom: 0;
            width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 36mm;
        }

        .price {
            font-size: 9pt;
            font-weight: bold;
            margin-top: 0.5mm;
        }

        .app-name {
            font-size: 6pt;
            font-weight: bold;
            margin-top: 0.5mm;
        }
    </style>
</head>

<body>
    <div class="receipt">
        <p class="app-name">{{ $stock->medicine->medicine_name ?? '' }} ({{$stock->size}})</p>
        <img
            src="data:image/png;base64, {!! DNS2D::getBarcodePNG("$stock->barcode_text", 'QRCODE', 3, 3) !!}"
            alt="qrcode"/>
        <p class="price">Tk {{ $stock->price }}/-</p>
    </div>

    <script>
        window.onload = function() {
            // Set a short delay before printing to ensure everything is loaded
            setTimeout(function() {
                window.print();
            }, 100);
            
            window.addEventListener('afterprint', function() {
                setTimeout(function() {
                    window.close();
                }, 250);
            });
        };
    </script>
</body>
</html>