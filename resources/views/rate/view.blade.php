<!doctype html>
<html lang="en">

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Exchange Rates</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
        }

        .container {
            width: 100%;
            padding: 3px;
            max-width: 900px; /* Adjusted width */
            background-color: #ffffff;
            border-radius: 8px;
            /* box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); */
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: right;
            font-weight: bold;
        }

        thead {
            background-color: #f2f2f2;
        }

        th {
            font-weight: bold;
        }

        tbody tr {
            border-bottom: 1px solid #ddd;
        }

        tbody tr:hover {
            background-color: #f9f9f9;
        }

        .scrollable-table {
            overflow-x: auto;
        }

        @media (max-width: 768px) {
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <section class="scrollable-table">
            <table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Buy</th>
                        <th>Sell</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rates as $key => $rate)
                    <tr>
                        <td class="text-left">
                            <!-- <img src="{{ $rate->country_flag }}" alt="{{ $rate->currency_code }}" class="w-8 h-8 rounded-full border"> -->
                            <span class="text-sm font-bold">{{ $rate->currency_code }}</span>
                        </td>
                        <td>{{ round($rate->buying_rate) }}</td>
                        <td>{{ round($rate->selling_rate) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </div>
</body>

</html>
