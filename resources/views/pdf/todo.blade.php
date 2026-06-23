{{-- 
    PDF Template for Todo Report
    This Blade view is rendered by PdfExportService and converted to PDF using DomPDF.
    
    Available variables:
    - $todo      : App\Models\Todo instance
    - $payment   : App\Models\Payment instance
    - $exportDate : Carbon instance (when the PDF was generated)
    
    To customize the PDF layout, modify this file.
    The styles use inline CSS (DomPDF supports basic CSS 2.1).
--}}
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Todo Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4B5563;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 22px;
            color: #1F2937;
            margin: 0 0 5px 0;
        }
        .header .date {
            font-size: 11px;
            color: #6B7280;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 8px;
        }
        .section-content {
            padding-left: 5px;
        }
        .label {
            font-weight: bold;
            color: #4B5563;
        }
        .value {
            color: #1F2937;
        }
        .description-text {
            text-align: justify;
            margin-top: 5px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #9CA3AF;
            border-top: 1px solid #E5E7EB;
            padding-top: 10px;
        }
        .status-paid {
            color: #059669;
            font-weight: bold;
        }
        table.info {
            width: 100%;
            border-collapse: collapse;
        }
        table.info td {
            padding: 4px 0;
            vertical-align: top;
        }
        table.info td:first-child {
            width: 140px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Todo Report</h1>
        <div class="date">Generated: {{ $exportDate->format('d F Y H:i:s') }}</div>
    </div>

    {{-- Todo Details Section --}}
    <div class="section">
        <div class="section-title">Task Details</div>
        <div class="section-content">
            <table class="info">
                <tr>
                    <td class="label">Title:</td>
                    <td class="value">{{ $todo->title }}</td>
                </tr>
                @if($todo->description)
                <tr>
                    <td class="label">Description:</td>
                    <td class="value">
                        <div class="description-text">{{ $todo->description }}</div>
                    </td>
                </tr>
                @endif
                <tr>
                    <td class="label">Status:</td>
                    <td class="value status-paid">Paid</td>
                </tr>
                <tr>
                    <td class="label">Created:</td>
                    <td class="value">{{ $todo->created_at->format('d F Y H:i:s') }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- Payment Details Section --}}
    <div class="section">
        <div class="section-title">Payment Details</div>
        <div class="section-content">
            <table class="info">
                <tr>
                    <td class="label">Order ID:</td>
                    <td class="value">{{ $payment->order_id }}</td>
                </tr>
                @if($payment->transaction_id)
                <tr>
                    <td class="label">Transaction ID:</td>
                    <td class="value">{{ $payment->transaction_id }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Amount:</td>
                    <td class="value">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
                @if($payment->payment_type)
                <tr>
                    <td class="label">Payment Type:</td>
                    <td class="value">{{ $payment->payment_type }}</td>
                </tr>
                @endif
                <tr>
                    <td class="label">Payment Date:</td>
                    <td class="value">{{ $payment->updated_at->format('d F Y H:i:s') }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- 
        Additional Information Section
        You can add more fields here as needed.
        Example:
        <tr>
            <td class="label">Custom Field:</td>
            <td class="value">{{ $todo->custom_field ?? 'N/A' }}</td>
        </tr>
    --}}

    <div class="footer">
        This document was automatically generated on {{ $exportDate->format('d F Y H:i:s') }}.<br>
        Todo Report &mdash; {{ config('app.name') }}
    </div>
</body>
</html>
