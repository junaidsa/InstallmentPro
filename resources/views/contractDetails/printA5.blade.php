 <!DOCTYPE html>
 <html>

 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <title>A5 Print - Contract Details</title>
     <style>
         body {
             font-family: Arial, sans-serif;
             margin: 0;
             padding: 8mm;

             font-size: 11px;

             line-height: 1.3;
             width: 148mm;
             min-height: 210mm;
             box-sizing: border-box;
         }

         .container {
             width: 100%;
             margin: 0;
             padding: 0;
         }

         .header {
             text-align: center;
             margin-bottom: 12px;
             padding-bottom: 8px;
             border-bottom: 1px solid #000;
         }

         .company-name {
             font-size: 15px;
             font-weight: bold;
         }

         .company-address {
             font-size: 10px;
         }

         .title {
             font-size: 13px;
             font-weight: bold;
             margin-bottom: 12px;
         }

         .section {
             margin-bottom: 10px;
         }

         .section-title {
             font-weight: bold;
             font-size: 12px;
             text-align: center;
             border-bottom: 1px solid #000;
             padding-bottom: 3px;
             margin-bottom: 6px;
         }

         .info-row {
             display: flex;
             justify-content: space-between;
             font-size: 11px;
             margin-bottom: 3px;
         }

         .label {
             font-weight: bold;
             margin-right: 8px;
         }

         table {
             width: 100%;
             border-collapse: collapse;
             font-size: 10px;
         }

         th,
         td {
             padding: 3px 2px;
             border: 1px solid #000;
         }

         th {
             font-weight: bold;
             font-size: 10px;
             text-align: center;
         }

         .amount {
             text-align: right;
         }

         .total-row {
             font-weight: bold;
             border-top: 2px solid #000;
         }

         @media print {
             body {
                 margin: 0;
                 padding: 6mm;
                 width: 148mm;
                 min-height: 210mm;
             }

             @page {
                 size: A5;
                 margin: 0;
             }
         }
     </style>

 </head>

 <body>
     <div class="container">
         <div class="header">
             <div class="company-name">{{ $user->groups->name ?? 'Company Name' }}</div>
             <div class="company-address">{{ $user->groups->address ?? 'Company Address' }}</div>
         </div>

         <div class="section">
             <div class="section-title">Customer Information</div>
             <div class="info-row">
                 <span class="label">Name:</span>
                 <span>{{ $booking->account->name ?? 'N/A' }}</span>
             </div>
             <div class="info-row">
                 <span class="label">CNIC:</span>
                 <span>{{ $booking->account->cnic ?? 'N/A' }}</span>
             </div>
             <div class="info-row">
                 <span class="label">Contact:</span>
                 <span>{{ $booking->account->contact ?? 'N/A' }}</span>
             </div>
         </div>

         <div class="section">
             <div class="section-title">Product Information</div>
             <div class="info-row">
                 <span class="label">Product:</span>
                 <span>{{ $booking->product->product_name ?? 'N/A' }}</span>
             </div>
             <div class="info-row">
                 <span class="label">Company:</span>
                 <span>{{ $booking->product->product_company ?? 'N/A' }}</span>
             </div>
             <div class="info-row">
                 <span class="label">IMEI:</span>
                 <span>{{ $booking->imei_no ?? 'N/A' }}</span>
             </div>
             <div class="info-row">
                 <span class="label">Deal Date:</span>
                 <span>{{ $booking->deal_date_formatted ?? 'N/A' }}</span>
             </div>
             <div class="info-row">
                 <span class="label">Total Amount:</span>
                 <span class="amount">{{ number_format($booking->payment_details->net_amount ?? 0) }}</span>
             </div>
             <div class="info-row">
                 <span class="label">Paid Amount:</span>
                 <span class="amount">{{ number_format($booking->payment_details->received_amount ?? 0) }}</span>
             </div>
             <div class="info-row">
                 <span class="label">Outstanding:</span>
                 <span class="amount">{{ number_format($booking->payment_details->outstanding_amount ?? 0) }}</span>
             </div>
         </div>

         <div class="section">
             <div class="section-title">Payment Summary</div>
             <table>
                 <thead>
                     <tr>
                         <th>Recovery Man</th>
                         <th>Due Date</th>
                         <th>Amount</th>
                         <th>Paid</th>
                         <th>Paid</th>
                         <th>Balance</th>
                     </tr>
                 </thead>
                 <tbody>
                     @php
                         $totalDue = 0;
                         $totalPaid = 0;
                         $totalBalance = 0;
                     @endphp
                     @forelse($booking->installments ?? [] as $installment)
                         @php
                             $due = $installment->amount ?? 0;
                             $paid = $installment->paid_amount ?? 0;
                             $balance = $due - $paid;
                             $totalDue += $due;
                             $totalPaid += $paid;
                             $totalBalance += $balance;
                         @endphp
                         <tr>
                             <td>{{ $installment->recoveryMan->name ?? '-' }}</td>
                             <td>{{ $installment->due_date_formatted ?? 'N/A' }}</td>
                             <td class="amount">{{ number_format($due) }}</td>
                             <td class="amount">{{ number_format($due) }}</td>
                             <td class="amount">{{ number_format($paid) }}</td>
                             <td class="amount">{{ number_format($balance) }}</td>
                         </tr>
                     @empty
                         <tr>
                             <td colspan="4" style="text-align: center;">No installments found</td>
                         </tr>
                     @endforelse
                     @if ($booking->installments ?? false)
                         <tr class="total-row">
                             <td></td>
                             <td></td>
                             <td><strong>TOTAL</strong></td>
                             <td class="amount"><strong>{{ number_format($totalDue) }}</strong></td>
                             <td class="amount"><strong>{{ number_format($totalPaid) }}</strong></td>
                             <td class="amount"><strong>{{ number_format($totalBalance) }}</strong></td>
                         </tr>
                     @endif
                 </tbody>
             </table>
         </div>
     </div>

     <script>
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
