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
                        <h1>{{ __('lang.STATUS_REPORT') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">Home</a></li>
                            <li class="breadcrumb-item active">{{ __('lang.STATUS_REPORT') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.STATUS_REPORT') }}</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('report.statusReport') }}" method="GET">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.MONTH') }}</label>
                                        <input type="month" name="selectedMonth" class="form-control"
                                            value="{{ request('selectedMonth', now()->format('Y-m')) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('report.statusReport') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" name="searchPerson" value="1"
                                    class="btn btn-success">Search</button>
                            </div>
                        </form>
                        <!-- /.card -->
                    </div>
        </section>
        <section class="content">
            <div class="card" style="margin-top: 2em; margin-left: 1em; margin-right: 1em;">
                <div class="card-header">
                    <h3 class="card-title">{{ __('lang.STATUS_REPORT') }}</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <div class="col-md-4 mb-3">
                            <label for="">{{ __('lang.TOTAL_AMOUNT') }}</label>
                            <div>
                                {{ number_format($totalAmount) ?? '0.00' }}
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">{{ __('lang.RECIEVED_AMOUNT') }}</label>
                            <div>
                                {{ number_format($receivedAmount) ?? '0.00' }}
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="">{{ __('lang.RECIEABLE_AMOUNT') }}</label>
                            <div>
                                {{ number_format($outstandingAmount) ?? '0.00' }}
                            </div>
                        </div>
                    </div>
                    <table id="statusTable" class="table table-bordered table-striped display">
                        <thead>
                            <tr>
                                <th>{{ __('lang.SR') }}</th>
                                <th>{{ __('lang.NAME') }}</th>
                                <th>{{ __('lang.CONTACT') }}</th>
                                <th>{{ __('lang.PRODUCT_NAME') }}</th>
                                <th>{{ __('lang.INSTALLMENT_TITLE') }}</th>
                                <th>{{ __('lang.DUE_DATE') }}</th>
                                <th>{{ __('lang.MONTH') }}</th>
                                <th>{{ __('lang.AMOUNT') }}</th>
                                <th>{{ __('lang.PAID_AMOUNT') }}</th>
                                <th>{{ __('lang.REMAINING_AMOUNT') }}</th>
                                <th>{{ __('lang.STATUS') }}</th>
                                <th>{{ __('lang.PROVE') }}</th>
                                <th>{{ __('lang.RECEIPT') }}</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records ?? [] as $i => $record)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $record->account->name }}</td>
                                    <td>{{ $record->account->contact }}</td>
                                    <td>{{ $record->booking->product->product_company }}
                                        {{ $record->booking->product->product_name }}</td>
                                    <td>{{ $record->installment_title }}</td>
                                    <td>{{ $record->due_date_formatted }}</td>
                                    <td>{{ $record->month }}</td>
                                    <td>{{ number_format($record->amount) }}</td>
                                    <td>{{ number_format($record->paid_amount) }}</td>
                                    <td>{{ number_format($record->remaining_amount) }}</td>
                                    <td>
                                        @if ($record->paid_amount > 0)
                                            <span class="badge bg-success">{{ $Full_PAY }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ $PENDING }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (!empty($record->image))
                                            <a href="{{ asset($record->image) }}" target="_blank" title="View Proof">
                                                <i class="fas fa-image text-success"></i>
                                            </a>
                                        @else
                                            <i class="fas fa-image" title="No Image Available"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($record->paid_amount > 0 && $record->latestPaymentLog)
                                            <a href="{{ url('receipt/' . $record->latestPaymentLog->transaction_id) }}"
                                                target="_blank" title="View Receipt">
                                                <i class="fas fa-file text-success"></i>
                                            </a>
                                        @else
                                            <i class="fas fa-file text-muted" title="No Receipt Available"></i>
                                        @endif
                                    </td>


                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center">No Records Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

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
    <script>
        $(document).ready(function() {
            var totalAmount = "{{ number_format($totalAmount) ?? '0.00' }}";
            var receivedAmount = "{{ number_format($receivedAmount) ?? '0.00' }}";
            var outstandingAmount = "{{ number_format($outstandingAmount) ?? '0.00' }}";

            $("#statusTable").DataTable({
                dom: "Bfrtip",
                responsive: false,
                autoWidth: false,
                scrollX: true,
                buttons: [{
                        extend: "excelHtml5",
                        title: "Status Report",
                        text: "Export to Excel",
                        className: "btn btn-secondary",
                        exportOptions: {
                            columns: ":not(.noExport)"
                        },
                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            var downrows = 3;
                            $('row', sheet).each(function() {
                                var attr = $(this).attr('r');
                                var ind = parseInt(attr);
                                $(this).attr("r", ind + downrows);
                            });
                            $('row c', sheet).each(function() {
                                var attr = $(this).attr('r');
                                var pre = attr.substring(0, 1);
                                var ind = parseInt(attr.substring(1));
                                $(this).attr("r", pre + (ind + downrows));
                            });
                            var rows =
                                '<row r="1">' +
                                '<c t="inlineStr" r="A1"><is><t>Total Amount</t></is></c>' +
                                '<c t="inlineStr" r="B1"><is><t>' + totalAmount + '</t></is></c>' +
                                '</row>' +
                                '<row r="2">' +
                                '<c t="inlineStr" r="A2"><is><t>Received Amount</t></is></c>' +
                                '<c t="inlineStr" r="B2"><is><t>' + receivedAmount +
                                '</t></is></c>' +
                                '</row>' +
                                '<row r="3">' +
                                '<c t="inlineStr" r="A3"><is><t>Receivable Amount</t></is></c>' +
                                '<c t="inlineStr" r="B3"><is><t>' + outstandingAmount +
                                '</t></is></c>' +
                                '</row>';

                            sheet.childNodes[0].childNodes[1].innerHTML = rows + sheet.childNodes[0]
                                .childNodes[1].innerHTML;
                        }
                    },
                    {
                        extend: "pdfHtml5",
                        title: "Status Report",
                        text: "Export to PDF",
                        className: "btn btn-success",
                        orientation: "landscape",
                        pageSize: "A4",
                        exportOptions: {
                            columns: ":not(.noExport)"
                        },
                        customize: function(doc) {
                            doc.content.splice(0, 0, {
                                text: "Totals Summary\n\n" +
                                    "Total Amount: " + totalAmount + "\n" +
                                    "Received Amount: " + receivedAmount + "\n" +
                                    "Receivable Amount: " + outstandingAmount + "\n\n",
                                fontSize: 12,
                                alignment: "left",
                                margin: [0, 0, 0, 10]
                            });
                        }
                    }
                ]
            });
        });
    </script>

    @include('components.notification')
</body>
