@include('components.header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Select2 -->
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="{{ asset('plugins/jquery-editable/jquery-editable.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('dist/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

<body class="hold-transition sidebar-mini layout-fixed">
    <style>
        .custom-label {
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        #cash-recept #installment_no {
            font-size: clamp(12px, 2vw, 18px);
            /* auto resizes between 12–18px */
            white-space: nowrap;
        }

        #cash-recept #installment_no {
            white-space: nowrap;
            font-size: clamp(12px, 2vw, 16px);
            /* Auto resize */
        }
    </style>
    @include('components.navbar')
    @include('components.sidebar')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('lang.INSTALLMENTS') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">Home</a></li>
                            <li class="breadcrumb-item active">{{ __('lang.INSTALLMENTS') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.INSTALLMENTS') }}</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group mb-3 col-md-6">
                                <label class="required" for=""> {{ __('lang.CUSTOMER') }}</label>
                                <select class="form-control select2" id="accountDD" name="account_id" required>
                                    <option value="" hidden>Select</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">
                                            {{ $customer->name }} ({{ $customer->contact }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Product Name --}}
                            <div class="form-group mb-3 col-md-6">
                                <label class="required" for="">{{ __('lang.BOOKINGS') }}</label>
                                <select class="form-control select2" id="bookingDD" name="booking_id" required>
                                </select>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <button type="reset" class="btn btn-secondary">Cancel</button>
                            <button type="button" onclick="insallmentUnpaind($('#bookingDD').val())"
                                class="btn btn-success">Search</button>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
        </section>

        <section class="content">
            <div class="card" style="margin-top: 2em; margin-left: 1em; margin-right: 1em;">
                <div class="card-header">
                    <h3 class="card-title">{{ __('lang.UNPAID_INSTALLMENTS') }}</h3>
                </div>
                <div class="card-body">
                    <table id="installmentTable" class="table table-bordered table-striped display">
                        <thead>
                            <tr>
                                <th data-field="srNo">{{ __('lang.SR') }}#</th>
                                <th data-field="Title">{{ __('lang.INSTALLMENT_TITLE') }}</th>
                                <th data-field="Month">{{ __('lang.MONTH_YEAR') }}</th>
                                <th data-field="Amount">{{ __('lang.AMOUNT') }}</th>
                                <th data-field="Paid">{{ __('lang.PAID') }}</th>
                                <th data-field="Remaining">{{ __('lang.REMAINING') }}</th>
                                <th data-field="Date">{{ __('lang.DUE_DATE') }}</th>
                                <th data-field="Status">{{ __('lang.STATUS') }}</th>
                                <th data-field="Action" class="noExport">{{ __('lang.ACTION') }}</th>
                            </tr>

                        </thead>
                        <tbody id="remainingInstalment">
                        </tbody>


                    </table>
                </div>
            </div>
        </section>
        <div class="modal fade" id="payModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title" id="exampleModalLabel">Confirm Transaction</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="payForm" enctype="multipart/form-data">
                            <input type="hidden" id="installmentId" name="installment_id">
                            <div class="mb-3">
                                <label class="form-label">{{ __('lang.INSTALLMENT_TITLE') }}</label>
                                <input type="text" class="form-control" id="installmentTitle"
                                    name="installment_title" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('lang.DUE_DATE') }}</label>
                                <input type="text" class="form-control" id="dueDate" name="due_date" readonly>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">{{ __('lang.INSTALLMENT') }}</label>
                                    <input type="text" class="form-control" id="amount"
                                        name="installment_amount" readonly>
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">
                                        <th>{{ __('lang.LATE_PAYMENT_FINE') }}</th>
                                    </label>
                                    <input type="number" class="form-control" id="late_payment_penalty"
                                        name="late_payment_penalty" min="0" step="0.01"
                                        placeholder="Enter penalty amount">
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label class="form-label">
                                        <th>{{ __('lang.TOTAL_AMOUNT') }}</th>
                                    </label>
                                    <input type="number" class="form-control" id="totalAmount_pay" name="amount">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('lang.IMAGE') }} ({{ __('lang.PROVE') }})</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input file-input-preview"
                                            name="proof_of_payment" id="proof_of_payment" accept="image/*">
                                        <label class="custom-file-label"
                                            for="proof_of_payment">{{ __('lang.CHOOSE_FILE') }} (JPG, JPEG,
                                            PNG)</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required">{{ __('lang.DETAIL') }}</label>
                                    <input type="text" class="form-control" id="detail" name="remarks"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">
                                        <th>{{ __('lang.PAYMENT_METHOD') }}</th>
                                    </label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="payment_mode"
                                                id="paymentCash" value="cash" checked>
                                            <label class="form-check-label"
                                                for="paymentCash">{{ __('lang.CASH') }}</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="payment_mode"
                                                id="paymentCheque" value="cheque">
                                            <label class="form-check-label"
                                                for="paymentCheque">{{ __('lang.CHEQUE') }}</label>
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="payment_mode"
                                                id="paymentOnline" value="online">
                                            <label class="form-check-label"
                                                for="paymentOnline">{{ __('lang.ONLINE') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="confirm_payment">Confirm
                                    Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
    <!-- Bootstrap Switch -->
    <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/poshytip/jquery.poshytip.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-editable/jquery-editable-poshytip.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script></script>
    <!-- page optional script -->
    <script src="{{ asset('plugins/scripts/instalment.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>

    @include('components.notification')
</body>
