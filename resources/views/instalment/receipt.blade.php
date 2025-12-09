<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        .mt-2 {
            margin-top: 0.4em;
        }

        @media print {
            .print-btn {
                display: none;
            }

        }

        @media print and (size: A5) {
            @page {
                size: A5;
                margin: 10mm;
            }

            body {
                transform: scale(0.8);
                transform-origin: top left;
            }
        }

        @media print and (size: A4) {
            @page {
                size: A4;
                margin: 10mm;
            }

            body {
                transform: scale(1);
                transform-origin: top left;
            }
        }
    </style>

    <title>Payment Receipt</title>

</head>

<body style="width: 90%; height: 50%; border: 2px solid black; font-family: monospace, Times, serif; display:block;"
    onload="window.print()">
    <div style="float:left;margin-left:1em;margin-top: 8px;">
        <div class="mt-2" style="font-size: 1.5em;"><b>{{ $group->name }}</b>
        </div>
        <div class="mt-2" style="font-size: 0.8em;"><b>Contact</b>: {{ $group->contact }}</div>
        <div class="mt-2" style="font-size: 0.8em;"><b>Address</b>: {{ $group->address }}</div>
        <div class="mt-2" style="font-size: 1.1em;"><b>Payment Receipt</b></div>
    </div>
    <div style="text-align: right; margin-right:1em;">
        <p> Installment No:
            <u> {{ $installment->installment_title }} </u>
        </p>


        <p> Due Date: <u>&nbsp;&nbsp; &nbsp;{{ \Carbon\Carbon::parse($installment->due_date)->format('d-m-Y') }} &nbsp;
                &nbsp;&nbsp;</u>
        </p>
        <p> Paid Date: <u>&nbsp; &nbsp;{{ now()->format('d-m-Y') }} &nbsp;
                &nbsp;&nbsp;</u>
        </p>
    </div>

    <div style="margin-left:1em; font-size: 15px">
        <p>Received From<u>&nbsp; {{ $installment->account->name ?? 'N/A' }} &nbsp; </u> the amount of Rs.<u> &nbsp;
                {{ number_format($transaction->paymentLog->amount) }}/- &nbsp; </u>

            For<u> &nbsp; &nbsp;{{ $installment->booking->product->product_name ?? '' }}&nbsp; &nbsp;</u></p>
    </div>
    <div>
        <div style="float:right; border:2px solid black; margin-right:1em; margin-top:0px; padding: 3px">
            <div style="padding: 2px 4px 2px 4px">
                <input type="checkbox" {{ $transaction->payment_mode === 'cash' ? 'checked' : '' }}>
                <label for="" style=""> Cash</label>
            </div>
            <div style="padding: 2px 4px 2px 4px">
                <input type="checkbox" {{ $transaction->payment_mode === 'cheque' ? 'checked' : '' }}>
                <label for="">Cheque</label>
            </div>
            <div style="padding: 2px 5px 2px 4px">
                <input type="checkbox" {{ $transaction->payment_mode === 'online' ? 'checked' : '' }}>
                <label for="">Online Payment</label>
            </div>
        </div>
        <div
            style="float:left; border:2px solid black; margin-left:1em; margin-top:0px; padding: 6px 10px; width: 200px;">
            <div style="font-weight: bold; margin-bottom: 6px;">Summary</div>

            <div style="overflow: hidden; padding: 2px 4px;">
                <div style="float: left;">Total</div>
                <div style="float: right;">{{ number_format($installment->booking->total_payable) }}/-</div>
            </div>

            <div style="overflow: hidden; padding: 2px 4px;">
                <div style="float: left;">Received</div>
                <div style="float: right;">
                    {{ number_format($received) }}/-
                </div>
            </div>

            <div style="overflow: hidden; padding: 2px 4px;">
                <div style="float: left;">Remaining</div>
                <div style="float: right;">
                    {{ number_format($remaining) }}/-
                </div>
            </div>
        </div>



    </div>
    </div>
    <div style="margin-bottom: 20px; text-align:right; margin-right:1em;">
        <p style="margin-top: 100px;">Received By:
            <u> &nbsp;{{ $user->user_name ?? 'Staff' }} &nbsp;</u>
        </p>
    </div>
    <div style="text-align:center; margin-top: 20px; font-size: 0.9em; font-style: italic; margin-buttom: 10px;">
        * This is a system generated slip and does not require signature or stamp.
    </div>
    <button onclick=window.print(); class="print-btn">Print</button>
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
