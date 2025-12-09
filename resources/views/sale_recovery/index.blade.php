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
<link rel="stylesheet" href="{{ asset('plugins/flatPicker/flatpickr.min.css') }}">
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="{{ asset('plugins/jquery-editable/jquery-editable.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
<!-- SweetAlert2 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('dist/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

<body class="hold-transition sidebar-mini layout-fixed">
    <style>
        .custom-label {
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .amount-input {
            width: 120px;
            text-align: right;
        }

        .select-all-checkbox {
            margin-bottom: 10px;
        }
    </style>
    @include('components.navbar')
    @include('components.sidebar')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('lang.SALE_RECOVERY') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">{{ __('lang.HOME') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('lang.SALE_RECOVERY') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.SALE_RECOVERY') }}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                data-toggle="tooltip" title="{{ __('lang.COLLAPSE') }}">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('saleRecovery.index') }}">
                            <div class="row">
                                <div class="form-group mb-3 col-md-6">
                                    <label class="required" for="">{{ __('lang.RECOVERY_MAN') }}</label>
                                    <select class="form-control select2" name="recovery_man_id" id="recovery_man_id"
                                        required>
                                        <option value="{{ $all }}"
                                            {{ request('recovery_man_id') == $all ? 'selected' : '' }}>
                                            {{ __('lang.ALL_RECOVERY_MEN') }}
                                        </option>
                                        @foreach ($recoveryMans as $emp)
                                            <option value="{{ $emp->employee_id }}"
                                                {{ request('recovery_man_id') == $emp->employee_id ? 'selected' : '' }}>
                                                {{ $emp->user_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group mb-3 col-md-6">
                                    <label for="">{{ __('lang.RECOVERY_DATE') }}</label>
                                    <input type="date" name="recovery_date" id="recovery_date"
                                        value="{{ request('recovery_date') ?? date('Y-m-d') }}" class="form-control">
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <a href="{{ route('saleRecovery.index') }}"
                                    class="btn btn-secondary">{{ __('lang.CANCEL') }}</a>
                                <button type="submit" class="btn btn-success">{{ __('lang.SEARCH') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>


        <section class="content">
            <div class="card" style="margin-top: 2em; margin-left: 1em; margin-right: 1em;">
                <div class="card-header">
                    <h3 class="card-title">{{ __('lang.SALE_RECOVERY') }}</h3>
                </div>
                <div class="card-body">
                    <div class="select-all-checkbox">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAllInstallments">
                            <label class="form-check-label" for="selectAllInstallments">
                                <strong>{{ __('lang.SELECT_ALL_INSTALLMENTS') }}</strong>
                            </label>
                        </div>
                    </div>

                    <table id="saleRecoveries" class="table table-bordered table-striped display">
                        <thead class="text-center">
                            <tr>
                                <th>{{ __('lang.SELECT') }}</th>
                                <th>{{ __('lang.SR') }}</th>
                                <th>{{ __('lang.RECOVERY_MAN') }}</th>
                                <th>{{ __('lang.CUSTOMER_NAME') }}</th>
                                <th>{{ __('lang.INSTALLMENT_TITLE') }}</th>
                                <th>{{ __('lang.PAID') }}</th>
                                <th>{{ __('lang.DUE_DATE') }}</th>
                                <th>{{ __('lang.STATUS') }}</th>
                                <th>{{ __('lang.NOT_APPROVED') }}</th>
                                <th>{{ __('lang.REMARKS') }}</th>
                            </tr>
                        </thead>
                        <tbody id="tb-history">
                            @if ($recoveries->isNotEmpty())
                                @foreach ($recoveries as $key => $item)
                                    <tr data-id="{{ $item->id }}" data-paid="{{ $item->amount }}"
                                        data-recovery-man="{{ $item->installment->recovery_man_id }}">
                                        <td class="text-center">
                                            <input type="checkbox" class="form-check-input installment-checkbox"
                                                value="{{ $item->id }}" data-paid="{{ $item->amount }}">
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->recoveryman?->user_name ?? '-' }}</td>
                                        <td>{{ $item->account->name ?? '-' }}</td>
                                        <td>{{ $item->installment->installment_title ?? '-' }}</td>
                                        <td class="text-end">
                                            {{ number_format($item->amount) }}</td>
                                        <td>{{ $item->installment->due_date_formatted }}</td>
                                        </td>
                                        <td><span
                                                class="text-success">{{ ucfirst($item->installment->status) }}</span>
                                        </td>
                                        <td>{{ $item->is_approve == $pending ? __('lang.PENDING') : '' }}</td>

                                        <td>{{ $item->installment->remarks ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                    @if ($recoveries->isNotEmpty())
                        <div class="d-flex justify-content-between mt-3">
                            <div>
                            </div>
                            @if ($recovery_man_id !== $all)
                                <button type="button" id="approveBtn" class="btn btn-success">
                                    <i class="fas fa-check-circle"></i> {{ __('lang.APPROVE_RECOVERIES') }}
                                </button>
                            @else
                                <button type="button" id="approveAllBtn" class="btn btn-success">
                                    <i class="fas fa-check-circle"></i> {{ __('lang.APPROVE_ALL_RECOVERIES') }}
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="modal fade" id="approveModal" tabindex="-1" role="dialog"
                    aria-labelledby="approveModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="approveModalLabel">{{ __('lang.APPROVE_RECOVERIES_MODAL') }}</h5>
                                <button type="button" class="close text-white" data-dismiss="modal"
                                    aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <form id="approveForm">
                                    <div id="selectedInstallmentsList" class="mb-3">
                                        <div id="installmentsSummary"></div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="givenAmount">{{ __('lang.GIVEN_AMOUNT') }}</label>
                                                <input type="number" class="form-control" id="givenAmount"
                                                    name="given_amount" step="0.01" min="0"
                                                    placeholder="{{ __('lang.ENTER_GIVEN_AMOUNT') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="newRemainingAmount">{{ __('lang.REMAINING_AMOUNT_LABEL') }}</label>
                                                <input type="text" class="form-control" id="newRemainingAmount"
                                                    name="remaining_amount" readonly value="0.00">
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="netTotal" name="net_total">
                                    <div class="form-group">
                                        <label>{{ __('lang.PAYMENT_METHOD') }}</label><br>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="method_cash" name="payment_method"
                                                value="cash" checked class="custom-control-input" required>
                                            <label class="custom-control-label" for="method_cash">Cash</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="method_bank" name="payment_method"
                                                value="bank" class="custom-control-input">
                                            <label class="custom-control-label" for="method_bank">Bank</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="method_cheque" name="payment_method"
                                                value="cheque" class="custom-control-input">
                                            <label class="custom-control-label" for="method_cheque">Cheque</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="method_online" name="payment_method"
                                                value="online" class="custom-control-input">
                                            <label class="custom-control-label" for="method_online">Online</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>{{ __('lang.REMARKS') }}</label>
                                        <textarea name="remarks" class="form-control" rows="2"></textarea>
                                    </div>

                                    <div class="text-right">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">{{ __('lang.CANCEL') }}</button>
                                        <button type="submit" class="btn btn-success">{{ __('lang.SUBMIT') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card" style="margin-top: 2em; margin-left: 1em; margin-right: 1em;">
                <div class="card-header">
                    <h3 class="card-title">{{ __('lang.PENDING_RECOVERIES_AMOUNT') }}</h3>
                </div>
                <div class="card-body">
                    <table id="RemaingRecoveries" class="table table-bordered table-striped display">
                        <thead class="text-center">
                            <tr>
                                <th>{{ __('lang.SR') }}</th>
                                <th>{{ __('lang.RECOVERY_MAN') }}</th>
                                <th>{{ __('lang.GIVEN_AMOUNT') }}</th>
                                <th>{{ __('lang.REMAINING_AMOUNT') }}</th>
                                <th>{{ __('lang.TOTAL_AMOUNT') }}</th>
                                <th>{{ __('lang.STATUS') }}</th>
                                <th>{{ __('lang.REMARKS') }}</th>
                                <th>{{ __('lang.DATE') }}</th>
                                <th>{{ __('lang.ACTION') }}</th>
                            </tr>
                        </thead>
                        <tbody id="tb-history">
                            @if ($pandingRecovery->isNotEmpty())
                                @foreach ($pandingRecovery as $key => $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->recoveryMan?->name ?? '-' }}</td>
                                        <td class="text-end">
                                            {{ number_format($item->amount) }}</td>
                                        <td class="text-end">
                                            {{ number_format($item->remaining_amount) }}</td>
                                        <td class="text-end">
                                            {{ number_format($item->total_amount) }}</td>
                                        <td><span class="text-success">{{ ucfirst($item->status) }}</span>
                                        </td>
                                        <td>{{ $item->remarks ?? '-' }}</td>
                                        <td>{{ $item->created_at ? $item->created_at->format('Y-m-d') : '-' }}</td>
                                        <td><button class="btn btn-success approve-btn"
                                                data-id="{{ $item->id }}"> {{ __('lang.APPROVED') }}</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

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
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/flatPicker/flatpickr.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/vfs_fonts.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        let recveryApprove = "{{ route('saleRecovery.approve') }}";
        let approveAllUrl = "{{ route('saleRecovery.approveAll') }}";
        const allRecoveryMen = "{{ $all }}";
        const approveRecoveriesText = "{{ __('lang.APPROVE_RECOVERIES') }}";
    </script>
    <script src="{{ asset('plugins/scripts/salerecovery.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>
    @include('components.notification')
</body>
