@include('components.header')
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
<link rel="stylesheet" href="{{ asset('plugins/jquery-editable/jquery-editable.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('dist/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

<body class="hold-transition sidebar-mini layout-fixed">
    <style>
        .custom-label {
            font-weight: 600;
            display: flex;
            align-items: center;
        }
    </style>
    @include('components.navbar')
    @include('components.sidebar')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('lang.RECOVERY_SHEET') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">Home</a></li>
                            <li class="breadcrumb-item active">{{ __('lang.RECOVERY_SHEET') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.RECOVERY_SHEET') }}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('recoverySheet.index') }}">
                            <div class="row">
                                <div class="form-group mb-3 col-md-4">
                                    <label class="required" for="">{{ __('lang.RECOVERY_MAN') }}</label>
                                    <select class="form-control select2" name="recovery_man_id" id="recovery_man_id"
                                        required>
                                        <option value="">Select Recovery Man</option>
                                        @foreach ($employees as $emp)
                                            <option value="{{ $emp->id }}"
                                                {{ request('recovery_man_id') == $emp->id ? 'selected' : '' }}>
                                                {{ $emp->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-3 col-md-4">
                                    <label class="required" for="">{{ __('lang.DATE') }}</label>
                                    <input type="date" name="date" id="date"
                                        value="{{ request('date', \Carbon\Carbon::today()) }}" class="form-control"
                                        required>
                                </div>

                                <div class="form-group mb-3 col-md-4">
                                    <label for="">{{ __('lang.STATUS') }}</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="all" {{ request('status') == 'All' ? 'selected' : '' }}>
                                            All</option>
                                        @foreach ($statuses as $status)
                                            <option value="{{ $status }}"
                                                {{ request('status') == $status ? 'selected' : '' }}>
                                                {{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <button type="reset" class="btn btn-secondary">{{ __('lang.CANCEL') }}</button>
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
                    <h3 class="card-title">{{ __('lang.RECOVERY_SHEET') }}</h3>
                </div>
                <div class="card-body">
                    <table id="recoverySheetTable" class="table table-bordered table-striped display">
                        <thead class="text-center">
                            <tr>
                                <th>{{ __('lang.SR') }}</th>
                                <th>{{ __('lang.CUSTOMER_NAME') }}</th>
                                <th>{{ __('lang.CONTACT') }}</th>
                                <th>{{ __('lang.ADDRESS') }}</th>
                                <th>{{ __('lang.PRODUCT') }}</th>
                                <th>{{ __('lang.INSTALLMENT_TITLE') }}</th>
                                <th>{{ __('lang.MONTH_YEAR') }}</th>
                                <th>{{ __('lang.INSTALMENT_AMOUNT') }}</th>
                                <th>{{ __('lang.PAID') }}</th>
                                <th>{{ __('lang.REMAINING') }}</th>
                                <th>{{ __('lang.DUE_DATE') }}</th>
                                <th>{{ __('lang.STATUS') }}</th>
                            </tr>
                        </thead>
                        <tbody id="tb-recovery">
                            @foreach ($installments as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->account->name ?? '-' }}</td>
                                    <td>{{ $item->account->contact ?? '-' }}</td>
                                    <td>{{ $item->account->address ?? '-' }}</td>
                                    <td>{{ $item->booking->product->product_name ?? '-' }}</td>
                                    <td>{{ $item->installment_title ?? '-' }}</td>
                                    <td>{{ $item->month . '-' . $item->year }}</td>
                                    <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->paid_amount, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->remaining_amount, 2) }}</td>
                                    <td>{{ $item->due_date ?? '-' }}
                                    </td>
                                    <td><span class="text-success">{{ ucfirst($item->status) }}</span></td>
                                </tr>
                            @endforeach
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
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/flatPicker/flatpickr.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/vfs_fonts.js') }}"></script>

    <script>
        $("#recoverySheetTable").DataTable({
            responsive: false,
            autoWidth: false,
            scrollX: true,
            dom: "Bfrtip",
            stateSave: true,
            buttons: [{
                    extend: "excelHtml5",
                    title: "Recovery Sheet",
                    text: "Export to Excel",
                    className: "btn btn-secondary",
                },
                {
                    extend: "pdfHtml5",
                    title: "Recovery Sheet Report",
                    text: "Export to PDF",
                    className: "btn btn-success",
                    orientation: "landscape",
                    pageSize: "A4",
                    footer: true,
                    exportOptions: {
                        columns: ":not(.noExport)"
                    },
                }
            ]

        });

        $(document).ready(function() {
            $(".select2").select2();
            const datePicker = flatpickr("#date", {
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
