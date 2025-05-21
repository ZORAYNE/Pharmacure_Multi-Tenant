<!DOCTYPE html>
<html>
<head>
    <title>Sale History - PHARMACURE</title>
    <meta charset="utf-8" />
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; max-width: 800px; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<h1>Sale History</h1>

<table>
    <thead>
        <tr>
            <th>Sale ID</th>
            <th>Date & Time</th>
            <th>Total Price</th>
            <th>Invoice</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sales as $index => $sale)
        <tr>
            <td>{{ $sale->created_at->format('Ymd_His') }}T-{{ str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}</td>
            <td>{{ $sale->created_at->format('Y-m-d H:i:s') }}</td>
            <td>${{ number_format($sale->total_price, 2) }}</td>
            <td><a href="{{ route('sales.invoice', $sale->id) }}" target="_blank">Download Invoice</a></td>
            <td>
                <form method="POST" action="{{ route('sales.sendEmail', $sale->id) }}">
                    @csrf
                    <button type="submit">Send to Email</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
