@include('components.header')
@php
    use App\Models\Account;
    use App\Models\Transaction;

    $selectedAccounts = request()->has('account_id') ? request('account_id') : [Account::ALL];
@endphp

<meta name="csrf-token" content="{{ csrf_token() }}">
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
<link rel="stylesheet" href="{{ asset('plugins/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

<body class="hold-transition sidebar-mini layout-fixed">
    @include('components.navbar')
    @include('components.sidebar')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('lang.ACCOUNT_LEDGER_REPORT') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">Home</a></li>
                            <li class="breadcrumb-item active">{{ __('lang.ACCOUNT_LEDGER_REPORT') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.ACCOUNT_LEDGER_REPORT') }}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('account.ledger.report') }}" method="GET">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.ACCOUNT') }}</label>
                                        <select class="form-control select2 accountId" name="account_id[]"
                                            id="accountId" required multiple>
                                            <option disabled>Select</option>
                                            <option value="{{ Account::ALL }}"
                                                {{ in_array(Account::ALL, $selectedAccounts) ? 'selected' : '' }}>
                                                {{ Account::ALL }}
                                            </option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}"
                                                    {{ in_array($account->id, $selectedAccounts) ? 'selected' : '' }}>
                                                    {{ $account->name }} -
                                                    {{ $account->type == Account::EMPLOYEE ? $account->designation : $account->type }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('lang.TRANSACTION_TYPE') }}</label>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="all" class="custom-control-input"
                                                name="transaction_type" value="{{ Transaction::ALL }}"
                                                {{ request('transaction_type', Transaction::ALL) == Transaction::ALL ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="all">(A)
                                                {{ Transaction::ALL }}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="debit" value="{{ Transaction::TYPE_DEBIT }}"
                                                name="transaction_type" class="custom-control-input"
                                                {{ request('transaction_type') == Transaction::TYPE_DEBIT ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="debit">(+)
                                                {{ Transaction::TYPE_CREDIT }}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="credit" value="{{ Transaction::TYPE_CREDIT }}"
                                                name="transaction_type" class="custom-control-input"
                                                {{ request('transaction_type') == Transaction::TYPE_CREDIT ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="credit">(-)
                                                {{ Transaction::TYPE_CREDIT }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('lang.FROM') }}</label>
                                        <div class="input-group">
                                            <input type="text" id="startDate" name="start_date"
                                                class="form-control datepicker"
                                                value="{{ request('start_date', \Carbon\Carbon::today()) }}">
                                            <div class="input-group-append">
                                                <button class="input-group-text clear-date"
                                                    id="clearStartDate">&times;</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('lang.TO') }}</label>
                                        <div class="input-group">
                                            <input type="text" id="endDate" name="end_date"
                                                class="form-control datepicker"
                                                value="{{ request('end_date', \Carbon\Carbon::today()) }}">
                                            <div class="input-group-append">
                                                <button class="input-group-text clear-date"
                                                    id="clearEndDate">&times;</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center" style="margin-top: 1em;">
                                <div class="col-auto">
                                    <a href="{{ route('account.ledger.report') }}"
                                        class="btn btn-secondary">{{ __('lang.CANCEL') }}</a>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" id="submit"
                                        class="btn btn-success">{{ __('lang.SEARCH') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card" style="margin-top: 2em; margin-left: 1em; margin-right: 1em;">
                <div class="card-header">
                    <h3 class="card-title">{{ __('lang.ACCOUNT_LEDGER_REPORT') }}</h3>
                </div>
                <div class="card-body">
                    <table id="ledgerReport" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('lang.SR') }}</th>
                                <th>{{ __('lang.DATE') }}</th>
                                <th>{{ __('lang.ACCOUNT') }}</th>
                                <th>{{ __('lang.TRANSACTION_TYPE') }}</th>
                                <th>{{ __('lang.CURRENCY') }}</th>
                                <th>{{ __('lang.DEBIT') }}</th>
                                <th>{{ __('lang.CREDIT') }}</th>
                                <th>{{ __('lang.METHOD') }}</th>
                                <th>{{ __('lang.NARRATION') }}</th>
                                <th>{{ __('lang.DOC') }}</th>
                                <th>{{ __('lang.BALANCE') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions ?? [] as $index => $transaction)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y') }}</td>
                                    <td>{{ $transaction->account->name ?? 'N/A' }}</td>
                                    <td>{{ $transaction->type }}</td>
                                    <td>{{ __('lang.PKR') }}</td>
                                    <td>{{ ucfirst($transaction->type) === Transaction::TYPE_DEBIT ? number_format($transaction->amount) : '-' }}
                                    </td>
                                    <td>{{ ucfirst($transaction->type) === Transaction::TYPE_CREDIT ? number_format($transaction->amount) : '-' }}
                                    </td>
                                    <td>{{ $transaction->payment_mode ?? 'N/A' }}</td>
                                    <td>{{ $transaction->remarks ?? 'N/A' }}</td>
                                    <td>{{ $transaction->doc_no ?? 'N/A' }}</td>
                                    <td>{{ number_format($transaction->running_balance ?? 0) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>{{ __('lang.TOTAL') }}:</th>
                                <th>{{ number_format($totalDebit ?? 0) }}</th>
                                <th>{{ number_format($totalCredit ?? 0) }}</th>
                                <th colspan="4"></th>
                            </tr>
                        </tfoot>
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
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>


    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/vfs_fonts.js') }}"></script>

    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('plugins/flatPicker/flatpickr.js') }}"></script>



    <script>
        $("#ledgerReport").DataTable({
            responsive: false,
            autoWidth: true,
            scrollX: true,
            dom: "Bfrtip",
            stateSave: true,
            buttons: [{
                    extend: "excelHtml5",
                    title: "Export Account Ledger",
                    text: "Export to Excel",
                    className: "btn btn-secondary",
                    footer: true
                },
                {
                    extend: "pdfHtml5",
                    title: "Export Account Ledger",
                    text: "Export to PDF",
                    className: "btn btn-success",
                    footer: true,
                    orientation: 'landscape',
                    pageSize: 'A4',
                    customize: function(doc) {
                        doc.styles.tableHeader.alignment = 'left';
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join(
                            '*').split('');
                    }
                }
            ],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search Account Ledger...",
            }
        });


        $(document).ready(function() {
            $(".select2").select2();
            const datePicker = flatpickr(".datePicker", {
                altInput: true,
                altFormat: "d/m/Y",
                dateFormat: "Y-m-d",
                minDate: "2025-01-01",
                maxDate: "today",
            });
        });
    </script>
    @include('components.notification')
</body>
