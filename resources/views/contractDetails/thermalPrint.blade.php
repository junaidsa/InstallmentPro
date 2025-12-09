<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thermal Print - Contract Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            font-size: 12px;
            line-height: 1.3;
            border: 2px solid black;
            width: 200px;
        }

        .thermal-container {
            width: 100%;
            max-width: 58mm;
            margin: 0 auto;
        }

        .thermal-header {
            text-align: center;
            margin-bottom: 5px;
            padding-bottom: 5px;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-location {
            font-size: 10px;
            margin-bottom: 5px;
        }

        .receipt-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .thermal-section {
            margin-bottom: 5px;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 11px;
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 3px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-size: 10px;
        }

        .info-label {
            font-weight: bold;
            margin-right: 10px;
        }

        .thermal-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 9px;
        }

        .thermal-table th,
        .thermal-table td {
            padding: 3px 2px;
            text-align: left;
            border-bottom: 1px dotted #ccc;
        }

        .thermal-table th {
            font-weight: bold;
            font-size: 9px;
        }

        .total-row {
            font-weight: bold;
            border-top: 1px solid #000;
        }

        @media print {
            body {
                margin: 0;
                padding: 5px;
                width: 200px;
            }

            .thermal-container {
                margin: 0;
                max-width: 100%;
            }

            @page {
                size: 58mm auto;
                margin: 0;
            }
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="thermal-container">
        <div class="thermal-header">
            <div class="company-name">{{ $user->groups->name }}</div>
            <div class="company-location">{{ $user->groups->address }}</div>
        </div>
        <div class="thermal-section">
            <div class="section-title">CUSTOMER INFORMATION</div>
            <div class="info-row">
                <span class="info-label">Name:</span>
                <span>{{ $booking->account->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">CNIC:</span>
                <span>{{ $booking->account->cnic }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Contact:</span>
                <span>{{ $booking->account->contact }}</span>
            </div>
        </div>

        <!-- Product Information -->
        <div class="thermal-section">
            <div class="section-title">PRODUCT INFORMATION</div>
            <div class="info-row">
                <span class="info-label">Product:</span>
                <span>{{ $booking->product->product_name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Company:</span>
                <span>{{ $booking->product->product_company ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">IMEI:</span>
                <span>{{ $booking->imei_no ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Deal Date:</span>
                <span>{{ $booking->deal_date_formatted }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Amount:</span>
                <span>{{ number_format($booking->payment_details->net_amount) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Paid Amount:</span>
                <span>{{ number_format($booking->payment_details->received_amount) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Outstanding:</span>
                <span>{{ number_format($booking->payment_details->outstanding_amount) }}</span>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="thermal-section">
            <div class="section-title">PAYMENT SUMMARY</div>
            <table class="thermal-table">
                <thead>
                    <tr>
                        <th>Due Date</th>
                        <th>Due Amt</th>
                        <th>Paid Amt</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalDue = 0;
                        $totalPaid = 0;
                        $totalBalance = 0;
                    @endphp
                    @foreach ($booking->installments as $installment)
                        @php
                            $due = $installment->amount ?? 0;
                            $paid = $installment->paid_amount ?? 0;
                            $balance = $due - $paid;
                            $totalDue += $due;
                            $totalPaid += $paid;
                            $totalBalance += $balance;
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($installment->due_date)->format('d/m/y') }}</td>
                            <td>{{ number_format($due) }}</td>
                            <td>{{ number_format($paid) }}</td>
                            <td>{{ number_format($balance) }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td><strong>TOTAL</strong></td>
                        <td><strong>{{ number_format($totalDue) }}</strong></td>
                        <td><strong>{{ number_format($totalPaid) }}</strong></td>
                        <td><strong>{{ number_format($totalBalance) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
                setTimeout(function() {
                    window.close();
                }, 1000);
            }, 500);
        };
    </script>
</body>

</html>
