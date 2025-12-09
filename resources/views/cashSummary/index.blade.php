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
<link rel="stylesheet" href="{{ asset('plugins/flatPicker/flatpickr.min.css') }}">

<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('dist/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@php
    $today = now()->format('d-m-Y');
@endphp

<body class="hold-transition sidebar-mini layout-fixed">
    @include('components.navbar')
    @include('components.sidebar')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('lang.DAILY_CASH_SUMMARY') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">{{ __('lang.HOME') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('lang.DAILY_CASH_SUMMARY') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.DAILY_CASH_SUMMARY') }}</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('report.cashInOutSummary') }}" method="GET">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('lang.FROM') }}</label>
                                        <div class="input-group">
                                            <input type="text" id="startDate" name="start_date"
                                                class="form-control datepicker"
                                                value="{{ request('start_date', $today) }}">
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
                                                value="{{ request('end_date', $today) }}">
                                            <div class="input-group-append">
                                                <button class="input-group-text clear-date"
                                                    id="clearEndDate">&times;</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('report.cashInOutSummary') }}"
                                    class="btn btn-secondary">{{ __('lang.CANCEL') }}</a>
                                <button type="submit" name="searchPerson" value="1"
                                    class="btn btn-success">{{ __('lang.SEARCH') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
        </section>

        @if (isset($cashIn) && isset($cashOut))
            <section class="content">
                <div class="row">
                    <div class="col-lg-3 col-12">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ number_format($cashIn ?? 0) }}</h3>
                                <p>{{ __('lang.CASH_IN_TOTAL_CREDITS') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-arrow-down"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ number_format($cashOut ?? 0) }}</h3>
                                <p>{{ __('lang.CASH_OUT_TOTAL_DEBITS') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-12">
                        <div class="small-box {{ $net >= 0 ? 'bg-primary' : 'bg-warning' }}">
                            <div class="inner">
                                <h3>{{ number_format($net) }}</h3>
                                <p>{{ __('lang.NET_BALANCE_IN_OUT') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas {{ $net >= 0 ? 'fa-chart-line' : 'fa-exclamation-triangle' }}"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Sales -->
                    <div class="col-lg-3 col-12">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($totalSales ?? 0) }}</h3>
                                <p>{{ __('lang.TOTAL_SALES') }}</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-primary">
                    <div class="card-header  mb-2">
                        <h3 class="card-title">{{ __('lang.TRANSACTIONS') }}</h3>
                    </div>
                    <div class="card-body">
                        <table id="transactionTable" class="table table-bordered table-striped w-100 nowrap">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">{{ __('lang.SR') }}</th>
                                    <th class="text-center align-middle">{{ __('lang.ACCOUNT') }}</th>
                                    <th class="text-center align-middle">{{ __('lang.TYPE') }}</th>
                                    <th class="text-center align-middle">{{ __('lang.AMOUNT') }}</th>
                                    <th class="text-center align-middle">{{ __('lang.REMARK') }}</th>
                                    <th class="text-center align-middle">{{ __('lang.DATE') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $index => $t)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $t->account->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge {{ $t->type == $debit ? 'bg-danger' : 'bg-success' }}">
                                                {{ ucfirst($t->type) }}
                                            </span>
                                        </td>


                                        <td>{{ number_format($t->amount, 2) }}</td>
                                        <td>{{ $t->remarks ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($t->created_at)->format('d-m-Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        @endif
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
    <script src="{{ asset('plugins/flatPicker/flatpickr.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/vfs_fonts.js') }}"></script>
    <!-- page optional script -->
    <script src="{{ asset('plugins/scripts/cashSummary.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>
    @include('components.notification')
</body>
