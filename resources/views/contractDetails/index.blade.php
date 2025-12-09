@include('components.header')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="{{ asset('plugins/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/customerLedger.css') }}">

<body class="hold-transition sidebar-mini layout-fixed">
    @include('components.navbar')
    @include('components.sidebar')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('lang.CONTRACT_DETAILS') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">{{ __('lang.HOME') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('lang.CONTRACT_DETAILS') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.CONTRACT_DETAILS') }}</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('contract.details') }}" method="GET">
                            <div class="row">
                                <!-- Customer -->
                                <div class="form-group mb-3 col-md-3">
                                    <label class="required">{{ __('lang.CUSTOMER') }}</label>
                                    <select class="form-control select2" id="accountDD" name="account_id" required>
                                        <option value="" hidden>{{ __('lang.SELECT') }}</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ request('account_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }} ({{ $customer->contact }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Bookings -->
                                <div class="form-group mb-3 col-md-3">
                                    <label>{{ __('lang.BOOKINGS') }}</label>
                                    <select class="form-control select2" id="bookingDD" name="booking_id"
                                        data-selected="{{ request('booking_id') }}">
                                        <option value="">{{ __('lang.SELECT') }}</option>
                                    </select>
                                </div>
                                <div class="form-group  mb-3 col-md-6">
                                    <label>{{ __('lang.CHOOSE_SECTIONS_TO_INCLUDE_IN_PRINT') }}</label>
                                    <select class="form-control print-sections-select" multiple>
                                        <option value="customer-info" selected>{{ __('lang.CUSTOMER_INFORMATION') }}
                                        </option>
                                        <option value="guarantor-info" selected>{{ __('lang.GUARANTOR_INFORMATION') }}
                                        </option>
                                        <option value="booking-info" selected>{{ __('lang.BOOKING_INFORMATION') }}
                                        </option>
                                        <option value="payment-detail" selected>{{ __('lang.PAYMENT_DETAIL') }}
                                        </option>
                                        <option value="signature" selected>{{ __('lang.SIGNATURE_SECTION') }}</option>
                                    </select>
                                    <small
                                        class="form-text text-muted">{{ __('lang.DESELECT_SECTIONS_TO_HIDE_FROM_PRINT') }}</small>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <a href="{{ route('contract.details') }}"
                                    class="btn btn-secondary">{{ __('lang.CANCEL') }}</a>
                                <button type="submit" class="btn btn-success">{{ __('lang.SEARCH') }}</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
        </section>

        <section class="content">
            @if ($bookings->count())
                @foreach ($bookings as $booking)
                    <div class="receipt-container" id="receipt-{{ $booking->id }}">
                        <div class="receipt-header">
                            <div class="company-info">
                                <div class="company-name">{{ $user->groups->name }}</div>
                                <div class="company-location">{{ $user->groups->address }}</div>
                            </div>
                            <div class="receipt-title">{{ __('lang.ACCOUNT_STATEMENT') }}</div>
                        </div>

                        <!-- Member Information Section -->
                        <div class="print-section customer-info-section">
                            <div class="section-title">{{ __('lang.CUSTOMER_INFORMATION') }}:</div>
                            <div class="member-info-container">
                                <div class="member-info-details">
                                    <div class="info-row">
                                        <span class="info-label">{{ __('lang.CNIC_NO') }}:</span>
                                        {{ $booking->account->cnic }}
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">{{ __('lang.MEMBER_NAME') }}:</span>
                                        {{ $booking->account->name }}
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">{{ __('lang.S_O') }}:</span>
                                        {{ $booking->account->father_name }}
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">{{ __('lang.ADDRESS') }}:</span>
                                        {{ $booking->account->address }}
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">{{ __('lang.MOBILE_PH_OFF_RES') }}:</span>
                                        {{ $booking->account->contact }}
                                    </div>
                                </div>
                                <div class="member-image">
                                    @php
                                        $imageDoc = $booking->account->customerDocuments
                                            ->where('document_type', 'image')
                                            ->first();
                                    @endphp
                                    @if ($imageDoc && $imageDoc->document_path)
                                        <img src="{{ asset($imageDoc->document_path) }}" alt="Profile Image"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('profile_pictures/noImg.png') }}" alt="No Image"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="print-section guarantor-info-section">
                            <div class="section-title">{{ __('lang.GUARANTOR_INFORMATION') }}:</div>
                            @if ($booking->account->guarantors->count() > 0)
                                @foreach ($booking->account->guarantors as $index => $guarantor)
                                    <div class="info-row">
                                        <div class="info-item">
                                            <span class="info-label">{{ __('lang.GUARANTOR') }}
                                                {{ $index + 1 }}:</span> {{ $guarantor->name }}
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">{{ __('lang.S_O') }}:</span>
                                            {{ $guarantor->father_name }}
                                        </div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-item">
                                            <span class="info-label">{{ __('lang.CNIC') }}:</span>
                                            {{ $guarantor->cnic }}
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">{{ __('lang.MOBILE_PH_OFF_RES') }}:</span>
                                            {{ $guarantor->phone }}
                                        </div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-item">
                                            <span class="info-label">{{ __('lang.ADDRESS') }}:</span>
                                            {{ $guarantor->address }}
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="info-row">
                                    <div class="info-item">
                                        <span class="info-label">{{ __('lang.NO_GUARANTOR') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!-- Unit Information Section -->
                        <div class="print-section booking-info-section">
                            <div class="section-title">{{ __('lang.BOOKING_INFORMATION') }}:</div>
                            <div class="info-row">
                                <div class="info-item">
                                    <span class="info-label">{{ __('lang.CATEGORY') }}:</span>
                                    {{ $booking->product->product_company ?? 'N/A' }}
                                </div>
                                <div class="info-item">
                                    <span class="info-label">{{ __('lang.PRODUCT_TYPE') }}:</span>
                                    {{ $booking->product->product_type ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-item">
                                    <span class="info-label">{{ __('lang.PRODUCT_NAME') }}:</span>
                                    {{ $booking->product->product_name ?? 'N/A' }}
                                </div>
                                <div class="info-item">
                                    <span class="info-label">{{ __('lang.EMEI_SERIAL_NO') }}:</span>
                                    {{ $booking->imei_no ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-item">
                                    <span class="info-label">{{ __('lang.DEAL_DATE') }}:</span>
                                    {{ $booking->deal_date_formatted ?? 'N/A' }}
                                </div>
                                <div class="info-item">
                                    <span class="info-label">{{ __('lang.DEAL_AMOUNT') }}:</span>
                                    {{ number_format($booking->total_payable ?? 0) }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-item">
                                    <span class="info-label">{{ __('lang.MONTHS') }}:</span>
                                    {{ $booking->total_months ?? 'N/A' }}
                                </div>
                                <div class="info-item">
                                    <span class="info-label">{{ __('lang.MONTHLY_DUE_DATE') }}:</span>
                                    {{ $booking->due_date ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-item">
                                    <span class="info-label">{{ __('lang.ADVANCE_PAID') }}:</span>
                                    {{ number_format($booking->down_payment ?? 0) }}
                                </div>
                                <div class="info-item">
                                    <span class="info-label">{{ __('lang.WARRANTY_GUARANTEE') }}:</span>
                                    {{ $booking->warranty_info ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="print-section payment-detail-section">
                            <div class="section-title">{{ __('lang.PAYMENT_DETAIL') }}:</div>
                            <div class="payment-details-row">
                                <div class="payment-detail-item">
                                    <span class="info-label">{{ __('lang.NET_AMOUNT') }}:</span>
                                    {{ number_format($booking->payment_details->net_amount) }}
                                </div>
                                <div class="payment-detail-item">
                                    <span class="info-label">{{ __('lang.RECEIVED_AMOUNT') }}:</span>
                                    {{ number_format($booking->payment_details->received_amount) }}
                                </div>
                                <div class="payment-detail-item">
                                    <span class="info-label">{{ __('lang.OUTSTANDING_AMOUNT') }}:</span>
                                    {{ number_format($booking->payment_details->outstanding_amount) }}
                                </div>
                            </div>
                            <div class="payment-details-row">
                                <div class="payment-detail-item">
                                    <span class="info-label">{{ __('lang.PENALTY_AMOUNT') }}:</span>
                                    {{ number_format($booking->payment_details->penalty_amount) }}
                                </div>
                                <div class="payment-detail-item">
                                    <span class="info-label">{{ __('lang.RECEIVED_PERCENTAGE') }}:</span>
                                    {{ $booking->payment_details->received_percentage }} %
                                </div>
                                <div class="payment-detail-item">
                                </div>
                            </div>

                            <div class="payment-tables-container">
                                <table class="payment-table table table-bordered table-left">
                                    <thead>
                                        <tr>
                                            <th>{{ __('lang.PAYMENT_DESC') }}</th>
                                            <th>{{ __('lang.DUE_DATE') }}</th>
                                            <th>{{ __('lang.DUE_AMOUNT') }}</th>
                                            <th>{{ __('lang.RECEIVED_AMOUNT') }}</th>
                                            <th>{{ __('lang.PENALTY_AMOUNT') }}</th>
                                            <th>{{ __('lang.OUTSTANDING_AMOUNT') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($booking->installments as $installment)
                                            <tr>
                                                <td>{{ $installment->installment_title ?? 'Installment' }}</td>
                                                <td>{{ $installment->due_date }}
                                                </td>
                                                <td>{{ number_format($installment->amount ?? 0) }}</td>
                                                <td>{{ number_format($installment->paid_amount ?? 0) }}</td>
                                                <td>{{ number_format($installment->late_payment_penalty ?? 0) }}</td>
                                                <td>{{ number_format(($installment->amount + $installment->late_payment_penalty ?? 0) - ($installment->paid_amount ?? 0)) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <table class="payment-table table table-bordered table-right">
                                    <thead>
                                        <tr>
                                            <th>{{ __('lang.RCIPT_NO') }}</th>
                                            <th>{{ __('lang.PAYMENT_MODE') }}</th>
                                            <th>{{ __('lang.PAID_DATE') }}</th>
                                            <th>{{ __('lang.PAID_AMOUNT') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $totalPaidAmount = 0; @endphp
                                        @foreach ($booking->installments as $installment)
                                            @foreach ($installment->paymentLogs as $paymentLog)
                                                <tr>
                                                    <td>{{ $paymentLog->id ?? 'N/A' }}</td>
                                                    <td>{{ ucfirst($paymentLog->payment_mode) ?? 'N/A' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($paymentLog->created_at)->format('d/m/Y') }}
                                                    </td>
                                                    <td>{{ number_format($paymentLog->amount ?? 0) }}</td>
                                                </tr>
                                                @php $totalPaidAmount += $paymentLog->amount ?? 0; @endphp
                                            @endforeach
                                        @endforeach
                                        <tr class="total-row">
                                            <td></td>
                                            <td></td>
                                            <td><strong>{{ __('lang.TOTAL') }} :</strong></td>
                                            <td><strong>{{ number_format($totalPaidAmount) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="print-section signature-section">
                            <div class="signature-item">
                                <span class="signature-label">{{ __('lang.APPLICANT') }} :</span>
                                <div class="signature-line"></div>
                            </div>
                            <div class="signature-item">
                                <span class="signature-label">{{ __('lang.BOOKING_OFFICER') }}:</span>
                                <div class="signature-line"></div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <button type="button" class="btn btn-primary"
                                onclick="printReceipt({{ $booking->id }})">
                                <i class="fas fa-print"></i>Print Page
                            </button>
                            <button type="button" class="btn btn-secondary ml-2"
                                onclick="thermalPrint({{ $booking->id }})">
                                <i class="fas fa-file-alt"></i> Thermal Print
                            </button>
                            <button type="button" class="btn btn-info ml-2"
                                onclick="thermalPrintA5({{ $booking->id }})">
                                <i class="fas fa-file"></i> A5 Print
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="container my-4">
                    <div class="alert alert-secondary text-center">
                        {{ __('lang.NO_BOOKINGS_FOUND') }}
                    </div>
                </div>
            @endif

        </section>
    </div>

    @include('components.footer')

    <aside class="control-sidebar control-sidebar-dark">

    </aside>


    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <!-- page optional script -->
    <script src="{{ asset('plugins/scripts/contractDetails.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>
    @include('components.notification')
</body>
