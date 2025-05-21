<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <meta charset="utf-8" />
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; max-width: 600px; }
        label { display: block; margin-top: 1rem; }
        select, input, button { width: 100%; padding: 0.5rem; margin-top: 0.25rem; }
        button { margin-top: 1rem; background-color: #007bff; color: white; border: none; border-radius: 4px; font-size: 16px; }
    </style>
</head>
<body>

<h1>Generate Report</h1>

<form method="POST" action="{{ route('reports.generate', ['tenant' => request()->route('tenant')]) }}">
    @csrf

    <label for="report_type">Select Report Type:</label>
    <select id="report_type" name="report_type" required>
        <option value="" disabled selected>Select report type</option>
        <option value="total sales">Total Sales</option>
        <option value="stock left">Number of Stocks Left</option>
        <option value="expired products">Expired Products</option>
        <option value="most sold">Most Sold Products</option>
        <option value="least sold">Least Sold Products</option>
        <option value="invoice">Invoice</option>
    </select>

    <label for="period">Select Period (optional):</label>
    <select id="period" name="period">
        <option value="" selected>All Time</option>
        <option value="day">Day</option>
        <option value="week">Week</option>
        <option value="month">Month</option>
        <option value="year">Year</option>
    </select>

    <label for="sale_id">Sale ID (required for Invoice):</label>
    <select id="sale_id" name="sale_id">
    <option value="" selected>Select Sale ID</option>
    @foreach($sales as $sale)
    <option value="{{ $sale->id }}">{{ $sale->id }} - {{ $sale->created_at->format('Y-m-d H:i') }}</option>
    @endforeach
    </select>

    <button type="submit">Generate Report</button>
</form>

<button id="sendReportEmailBtn" style="margin-top: 1rem; background-color: #28a745; color: white; border: none; border-radius: 4px; padding: 0.5rem; font-size: 16px; cursor: pointer;">Send to Email</button>

<h2 style="margin-top: 2rem;">Report History</h2>
@if(isset($reportHistory) && count($reportHistory) > 0)
    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
        <thead>
            <tr>
                <th>Report Type</th>
                <th>Period</th>
                <th>Generated At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportHistory as $report)
            <tr>
                <td>{{ $report->report_type }}</td>
                <td>{{ $report->period ?? 'All Time' }}</td>
                <td>{{ $report->created_at->format('Y-m-d H:i:s') }}</td>
                <td>
        <form method="POST" action="{{ route('reports.sendEmail', ['tenant' => request()->route('tenant'), 'id' => $report->id]) }}">
                        @csrf
                        <button type="submit" style="background-color: #007bff; color: white; border: none; border-radius: 4px; padding: 0.25rem 0.5rem; cursor: pointer;">Send to Email</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>No report history available.</p>
@endif

<script>
    document.getElementById('sendReportEmailBtn').addEventListener('click', function() {
        const reportType = document.getElementById('report_type').value;
        const period = document.getElementById('period').value;
        const saleId = document.getElementById('sale_id').value;

        if (!reportType) {
            alert('Please select a report type before sending.');
            return;
        }

        // Create a form to submit the email request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("reports.sendEmail", ["tenant" => request()->route("tenant")]) }}';

        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);

        // Add report type
        const reportTypeInput = document.createElement('input');
        reportTypeInput.type = 'hidden';
        reportTypeInput.name = 'report_type';
        reportTypeInput.value = reportType;
        form.appendChild(reportTypeInput);

        // Add period
        const periodInput = document.createElement('input');
        periodInput.type = 'hidden';
        periodInput.name = 'period';
        periodInput.value = period;
        form.appendChild(periodInput);

        // Add sale ID if applicable
        if (saleId) {
            const saleIdInput = document.createElement('input');
            saleIdInput.type = 'hidden';
            saleIdInput.name = 'sale_id';
            saleIdInput.value = saleId;
            form.appendChild(saleIdInput);
        }

        document.body.appendChild(form);
        form.submit();
    });
</script>

</body>
</html>
