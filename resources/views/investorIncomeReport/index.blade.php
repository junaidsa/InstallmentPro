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
<link rel="stylesheet" href="{{ asset('dist/css/incomeReport.css') }}">
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="{{ asset('plugins/jquery-editable/jquery-editable.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/flatPicker/flatpickr.min.css') }}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('dist/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

<body class="hold-transition sidebar-mini layout-fixed">
    @include('components.navbar')
    @include('components.sidebar')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('lang.INVESTOR_INCOME_REPORT') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">{{ __('lang.HOME') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('lang.INVESTOR_INCOME_REPORT') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.INVESTOR_INCOME_REPORT') }}</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('investorIncomeReport.index') }}" method="GET">
                            <div class="row">
                                <div class="form-group mb-3 col-md-6">
                                    <label>{{ __('lang.INVESTORS') }}</label>
                                    <select class="form-control select2" id="accountDD" name="account_id">
                                        <option value="" selected>{{ __('lang.ALL_INVESTORS') }}</option>
                                        @foreach ($investors as $investor)
                                            <option value="{{ $investor->id }}"
                                                {{ request('account_id') == $investor->id ? 'selected' : '' }}>
                                                {{ $investor->name }} ({{ $investor->contact }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.MONTH') }}</label>
                                        <input type="month" name="selectedMonth" class="form-control"
                                            value="{{ request('selectedMonth', now()->format('Y-m')) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('investorIncomeReport.index') }}"
                                    class="btn btn-secondary">{{ __('lang.CANCEL') }}</a>
                                <button type="submit" name="searchPerson" value="1"
                                    class="btn btn-success">{{ __('lang.SEARCH') }}</button>
                            </div>
                        </form>
                        <!-- /.card -->
                    </div>
        </section>
        <section class="content">
            <div class="card" style="margin-top: 2em; margin-left: 1em; margin-right: 1em;">
                <div class="card-header">
                    <h3 class="card-title">{{ __('lang.INVESTOR_INCOME_REPORT') }}</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <div class="col-md-3 mb-3">
                            <label for="">{{ __('lang.TOTAL_SALES') }}</label>
                            <div>
                                {{ number_format($totalSale) ?? '0.00' }}
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="">{{ __('lang.TOTAL_RECOVERED') }}</label>
                            <div>
                                {{ number_format($totalRecovered) ?? '0.00' }}
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="">{{ __('lang.TOTAL_EXPENSE') }}</label>
                            <div>
                                {{ number_format($totalExpense) ?? '0.00' }}
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="">{{ __('lang.TOTAL_PROFIT') }}</label>
                            <div>
                                {{ number_format($netProfit, 2) ?? '0.00' }}
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="InvestorIncomeTable" class="table table-bordered table-striped">
                            <thead class="text-center">
                                <tr>
                                    <th>{{ __('lang.SR') }}</th>
                                    <th>{{ __('lang.INVESTOR_NAME') }}</th>
                                    <th>{{ __('lang.MONTH') }}</th>
                                    <th>{{ __('lang.TOTAL_INVESTMENT') }}</th>
                                    <th>{{ __('lang.MONTHLY_PROFIT') }}</th>
                                    <th>{{ __('lang.INVESTOR_INCOME') }}</th>
                                    <th>{{ __('lang.SHOP_INCOME') }}</th>
                                    <th>{{ __('lang.ACTION') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($investorIncomes as $index => $income)
                                    <tr data-investor-id="{{ $income->investor_id }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $income->investor_name }}</td>
                                        <td>{{ $income->month }}</td>
                                        <td>{{ number_format($income->total_investment, 2) }}</td>
                                        <td>{{ number_format($income->monthly_profit, 2) }}</td>
                                        <td>{{ number_format($income->investorIncome, 2) }}</td>
                                        <td>{{ number_format($income->shopProfit, 2) }}</td>
                                        <td>

                                            <button class="btn btn-sm btn-success distribute-btn"
                                                data-id="{{ $income->investor_id }}"
                                                data-name="{{ $income->investor_name }}"
                                                data-profit="{{ round($income->investorIncome, 2) }}">
                                                <i class="fas fa-coins"></i> {{ __('lang.DISTRIBUTE') }}
                                            </button>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="text-center text-muted">
                                            {{ __('lang.NO_DATA_FOUND_FOR_THIS_MONTH') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal fade" id="profitModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form id="profitForm" action="{{ route('transfer.Profit') }}" method="POST">
                                @csrf
                                <input type="hidden" id="modalInvestorId" name="investor_id">
                                <input type="hidden" id="shop_profit" name="shop_profit"
                                    value="{{ $totalShopProfit }}">
                                <input type="hidden" id="totalAvailableProfit" name="total_available_profit">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="profitModalLabel">
                                        <i class="fas fa-coins mr-2"></i>{{ __('lang.DISTRIBUTE_PROFIT') }}
                                    </h5>
                                    <button type="button" class="close text-white" data-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="alert alert-info">
                                                <p class="mb-1"><strong>{{ __('lang.INVESTOR') }}</strong> <span
                                                        id="modalInvestorName"></span></p>
                                                <p class="mb-0">
                                                    <strong>{{ __('lang.TOTAL_AVAILABLE_PROFIT') }}</strong> <span
                                                        id="modalTotalProfit" class="font-weight-bold"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="reinvestedAmount" class="text-success font-weight-bold">
                                                    <i
                                                        class="fas fa-chart-line mr-1"></i>{{ __('lang.REINVESTED_AMOUNT') }}
                                                </label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-success text-white">PKR</span>
                                                    </div>
                                                    <input type="number" id="reinvestedAmount"
                                                        name="reinvested_amount" class="form-control form-control-lg"
                                                        min="0" step="0.01" placeholder="0.00" required
                                                        readonly>
                                                </div>
                                                <small class="form-text text-muted">
                                                    <i class="fas fa-info-circle text-success"></i>
                                                    {{ __('lang.AUTOMATICALLY_CALCULATED_PROFIT_CASH_OUT') }}
                                                </small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cashOutAmount" class="text-primary font-weight-bold">
                                                    <i
                                                        class="fas fa-money-bill-wave mr-1"></i>{{ __('lang.CASH_OUT_AMOUNT') }}
                                                </label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-primary text-white">PKR</span>
                                                    </div>
                                                    <input type="number" id="cashOutAmount" name="cash_out_amount"
                                                        class="form-control form-control-lg" min="0"
                                                        step="0.01" placeholder="0.00" required>
                                                </div>
                                                <small
                                                    class="form-text text-muted">{{ __('lang.AMOUNT_TO_WITHDRAW_AS_CASH') }}</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-warning" id="amountWarning"
                                                style="display: none;">
                                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                                <span id="warningMessage"></span>
                                            </div>

                                            <div class="alert alert-success" id="amountSummary"
                                                style="display: none;">
                                                <div class="row text-center">
                                                    <div class="col-md-4">
                                                        <strong>{{ __('lang.TOTAL_PROFIT') }}</strong><br>
                                                        <span id="summaryTotal" class="h5"></span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <strong>{{ __('lang.REINVESTED') }}</strong><br>
                                                        <span id="summaryReinvested" class="h5 text-success"></span>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <strong>{{ __('lang.CASH_OUT') }}</strong><br>
                                                        <span id="summaryCashOut" class="h5 text-primary"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        {{ __('lang.CANCEL') }}
                                    </button>
                                    <button type="submit" class="btn btn-success" id="proceedBtn" disabled>
                                        <i
                                            class="fas fa-check-circle mr-1"></i>{{ __('lang.PROCEED_WITH_DISTRIBUTION') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


            </div>


    </div>
    </section>
    </div>
    @include('components.footer')

    <aside class="control-sidebar control-sidebar-dark">

    </aside>

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
    <script src="{{ asset('plugins/bootstrap/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/flatPicker/flatpickr.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/scripts/investorIncome.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>
    @include('components.notification')
</body>
