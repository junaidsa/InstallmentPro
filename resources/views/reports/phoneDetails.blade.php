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
                        <h1>{{ __('lang.PHONE_DETAILS') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">Home</a></li>
                            <li class="breadcrumb-item active">{{ __('lang.PHONE_DETAILS') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.PHONE_DETAILS') }}</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('report.phoneDetailsReport') }}" method="GET">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>{{ __('lang.IMEI_SERIAL_NO') }}</label>
                                    <input type="text" name="imei" class="form-control"
                                        placeholder="Enter {{ __('lang.IMEI_SERIAL_NO') }}"
                                        value="{{ $imei ?? '' }}">
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('report.phoneDetailsReport') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-success" name="searchPerson"
                                    value="1">Search</button>
                            </div>
                        </form>
                    </div>
                </div>
        </section>
        @if (request()->has('searchPerson'))
            <section class="content">
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.PHONE_DETAILS') }}</h3>
                    </div>
                    <div class="card-body">
                        <table id="phoneDetailsTable" class="table table-bordered table-striped display">
                            <thead>
                                <tr>
                                    <th>{{ __('lang.SR') }}</th>
                                    <th>{{ __('lang.CUSTOMER_NAME') }}</th>
                                    <th>{{ __('lang.COMPANY_NAME') }}</th>
                                    <th>{{ __('lang.PRODUCT_NAME') }}</th>
                                    <th>{{ __('lang.IMEI_SERIAL_NO') }}</th>
                                    <th>{{ __('lang.DOWN_PAYMENT') }}</th>
                                    <th>{{ __('lang.TOTAL_PAYABLE') }}</th>
                                    <th>{{ __('lang.REMAINING_AMOUNT') }}</th>
                                    <th>{{ __('lang.MONTHLY_INSTALLMENT') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $i => $record)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $record->account->name }}</td>
                                        <td>{{ $record->product->product_company }}</td>
                                        <td>{{ $record->product->product_name }}</td>
                                        <td>{{ $record->imei_no }}</td>
                                        <td>{{ number_format($record->down_payment) }}</td>
                                        <td>{{ number_format($record->total_payable) }}</td>
                                        <td>{{ number_format($record->remaining_amount) }}</td>
                                        <td>{{ number_format($record->monthly_installment) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No Records Found</td>
                                    </tr>
                                @endforelse
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
    <script>
        const table = $("#stockTable").DataTable({
            responsive: false,
            autoWidth: false,
            scrollX: true,
        });
    </script>

    @include('components.notification')
</body>
