<!DOCTYPE html>
<html>
<head>
    <title>Notification Email</title>
    <style type="text/css">
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .title {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
            text-align: center;
        }

        .container {
            margin: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 style="text-align: center">{{ ENV('APP_NAME', 'Pharmassist') }}</h1>
    <h2 class="title">Daily Summary Report of {{ \Carbon\Carbon::parse($summary_date)->format('d-m-Y')}}</h2>

    <h3>Warehouse Summary</h3>
        @foreach ($warehouseSummaries as $data)

            <table >
                <tr>
                    <th colspan="2">{{ $data['name'] }}</th>

                </tr>
                <tr>
                    <td style="width: 50%">Total Purchase QTY</td>
                    <td style="width: 50%">{{ $data['purchase_qty'] }}</td>

                </tr>

                <tr>
                    <td>Total Purchase Amount</td>
                    <td>
                        {{ $data['purchase_amt'] }}
                    </td>

                </tr>
                <tr>
                    <td>Distribute (QTY),</td>
                    <td> {{ $data['distribute'] }}
                    </td>

                </tr>
                <tr>
                    <td>Medicine Request (QTY),</td>
                    <td> {{ $data['request'] }}
                    </td>

                </tr>
                <tr>
                    <td>Expired Today</td>
                    <td> {{ $data['stock'] }}
                    </td>

                </tr>
            </table>
        <br>
        @endforeach
    <br>
    <h3>Outlet Summary</h3>
    @foreach ($outletSummaries as $data)

        <table>
            <tr>
                <th colspan="2">{{ $data['name'] }}</th>

            </tr>
            <tr>
                <td style="width: 50%">Total Sale</td>
                <td style="width: 50%">{{ $data['sale'] }} TK</td>

            </tr>

            <tr>
                <td>Total Due </td>
                <td>
                    {{ $data['due'] }} TK
                </td>

            </tr>
            <tr>
                <td>Total Return </td>
                <td> {{ $data['return'] }}
                </td>

            </tr>
            <tr>
                <td>Total Received </td>
                <td> {{ $data['received'] }}
                </td>

            </tr>
            <tr>
                <td>Expired Today </td>
                <td> {{ $data['stock'] }}
                </td>

            </tr>
        </table>
        <br>
    @endforeach

</div>
</body>
</html>
