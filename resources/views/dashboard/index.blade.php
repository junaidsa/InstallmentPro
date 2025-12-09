@include('components.header')
@php
    use App\Models\Notification;
@endphp
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
<!-- fullCalendar -->
<link rel="stylesheet" href="{{ 'plugins/fullcalendar/main.min.css' }}">
<link rel="stylesheet" href="{{ 'plugins/fullcalendar-daygrid/main.min.css' }}">
<link rel="stylesheet" href="{{ 'plugins/fullcalendar-timegrid/main.min.css ' }}">
<link rel="stylesheet" href="{{ 'plugins/fullcalendar-bootstrap/main.min.css ' }}">
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="{{ asset('plugins/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

<style>
    .filter-header {
        background-color: #fff;
        /* white background */
    }

    .filter-header .btn {
        margin: 3px;
        border-radius: 6px;
        transition: all 0.2s ease-in-out;
    }

    .filter-header .btn:hover {
        background-color: #fcfdfc;
        color: #28A745;
    }

    .filter-header .btn.active {
        background-color: #28A745;
        color: #fff;
    }

    .attendance-card-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        background: linear-gradient(135deg, #c3ecf8, #e1f6f4);
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        gap: 20px;
    }

    .employee-info {
        display: flex;
        align-items: center;
        max-width: 40%;
        min-width: 200px;
    }

    .employee-info img {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        margin-right: 30px;
    }

    .time-details {
        margin: 5px 0;
    }

    .icons {
        font-size: 1.5rem;
        margin-right: 15px;
        cursor: pointer;
        color: #007bff;
    }

    .icons:hover {
        color: #369e99;
    }

    .note-field {
        width: 100%;
        margin-top: 10px;
    }

    .activity-timeline {
        margin-top: 15px;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        background-color: #e9f7ff;
        padding: 10px;
        border-radius: 8px;
        border-left: 5px solid #00aaff;
    }

    .activity-entry {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }

    .activity-entry i {
        margin-right: 5px;
    }

    .custom-link,
    .custom-link:visited,
    .custom-link:hover,
    .custom-link:focus,
    .custom-link:active {
        color: black !important;
        text-decoration: none;
    }

    .ui-list {
        border-radius: 2px;
        background-color: #f8f9fa;
        border-left: 2px solid #e9ecef;
        color: #495057;
        margin-bottom: 2px;
        padding: 10px;
    }

    .bg-teal {
        background-color: #20c997 !important;
        color: #fff;
    }
</style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        @include('components.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('components.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Dashboard</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <!-- /.row -->
                </div>
            </section>
            <section class="content">
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $totalCustomers }}</h3>

                                <p>{{ __('lang.TOTAL_CUSTOMERS') }}</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="{{ route('booking.store') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $totalStock }}</h3>

                                <p>{{ __('lang.TOTAL_STOCK') }}</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{ url('stockReport?stock_type=All&product_type=&productDD=all_products&searchPerson=1') }}"
                                class="small-box-footer">{{ __('lang.MORE_INFO') }} <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-secondary">
                            <div class="inner">
                                <h3>{{ $totasalestock }}</h3>

                                <p>{{ __('lang.SOLD_STOCK') }}</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{ url('stockReport?stock_type=Sold&product_type=&productDD=all_products&searchPerson=1') }}"
                                class="small-box-footer">{{ __('lang.MORE_INFO') }} <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $totalAvailablestock }}</h3>

                                <p>{{ __('lang.STOCK_AVAILABLE') }}</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ url('stockReport?stock_type=Available&product_type=&productDD=all_products&searchPerson=1') }}"
                                class="small-box-footer">{{ __('lang.MORE_INFO') }} <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ number_format($receivedAmount) }}</h3>

                                <p>{{ __('lang.RECEIVED_AMOUNT') }}</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="{{ route('report.statusReport', ['selectedMonth' => now()->format('Y-m')]) . '&searchPerson=1' }}"
                                class="small-box-footer">{{ __('lang.MORE_INFO') }}<i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ number_format($outstandingAmount) }}</h3>

                                <p>{{ __('lang.OUTSTANDING_AMOUNT') }}</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="{{ route('report.statusReport', ['selectedMonth' => now()->format('Y-m')]) . '&searchPerson=1' }}"
                                class="small-box-footer">{{ __('lang.MORE_INFO') }} <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-dark">
                            <div class="inner">
                                <h3>{{ number_format($today_sale) }}</h3>
                                <p>{{ __('lang.TODAY_SALE') }}</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="{{ route('sale.report', ['start_date' => $today, 'end_date' => $today]) }}"
                                class="small-box-footer">
                                {{ __('lang.MORE_INFO') }}
                                <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-teal">
                            <div class="inner">
                                <h3>{{ number_format($monthSale) }}</h3>

                                <p>{{ __('lang.THIS_MONTH_SALE') }}</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                            <a href="{{ route('sale.report') }}?start_date={{ $start_date }}&end_date={{ $end_date }}"
                                class="small-box-footer">
                                {{ __('lang.MORE_INFO') }} <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>

            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card" style="margin-top: 2em; margin-left: 1em; margin-right: 1em;">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('lang.DUE_DATE_PASSED') }}</h3>
                                </div>
                                <div class="card-body">
                                    <table id="dueTable" class="table table-bordered table-striped display">
                                        <thead>
                                            <th>{{ __('lang.SR') }}</th>
                                            <th>{{ __('lang.NAME') }}</th>
                                            <th>{{ __('lang.CONTACT') }}</th>
                                            <th>{{ __('lang.ADDRESS') }}</th>
                                            <th>{{ __('lang.AMOUNT') }}</th>
                                            <th>{{ __('lang.DUE_DATE') }}</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($duePassed as $index => $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->booking->account->name }}</td>
                                                    <td>{{ $item->booking->account->contact }}</td>
                                                    <td>{{ $item->booking->account->address }}</td>
                                                    <td>{{ number_format($item->amount) }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item->due_date)->format('d M, Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">

                            <div class="card" style="margin-top: 2em; margin-left: 1em; margin-right: 1em;">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('lang.DUE_DATE_NEAR') }}</h3>
                                </div>
                                <div class="card-body">
                                    <table id="DueNearTable" class="table table-bordered table-striped display">
                                        <thead>
                                            <th>{{ __('lang.SR') }}</th>
                                            <th>{{ __('lang.NAME') }}</th>
                                            <th>{{ __('lang.CONTACT') }}</th>
                                            <th>{{ __('lang.ADDRESS') }}</th>
                                            <th>{{ __('lang.AMOUNT') }}</th>
                                            <th>{{ __('lang.DUE_DATE') }}</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($dueNear as $index => $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->booking->account->name }}</td>
                                                    <td>{{ $item->booking->account->contact }}</td>
                                                    <td>{{ $item->booking->account->address }}</td>
                                                    <td>{{ number_format($item->amount) }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item->due_date)->format('d M, Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>

            <section class="content">
                <!-- /.col -->
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-body p-0">
                            <!-- THE CALENDAR -->
                            <div id="calendar"></div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </section>
            <!-- Student List Modal -->
            <div class="modal fade" id="installmentModal" tabindex="-1" role="dialog"
                aria-labelledby="studentModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title" id="installmentModalLabel">Installments on <span
                                    id="modalDate"></span>
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <ul id="installmentList" class="list-group"></ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-wrapper -->
        @include('components.footer')
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">

        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <!-- bs-custom-file-input -->
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <!-- fullCalendar 2.2.5 -->
    <script src="{{ 'plugins/moment/moment.min.js' }}"></script>
    <script src="{{ 'plugins/fullcalendar/main.min.js' }}"></script>
    <script src="{{ 'plugins/fullcalendar-daygrid/main.min.js' }}"></script>
    <script src="{{ 'plugins/fullcalendar-timegrid/main.min.js' }}"></script>
    <script src="{{ 'plugins/fullcalendar-interaction/main.min.js' }}"></script>
    <script src="{{ 'plugins/fullcalendar-bootstrap/main.min.js' }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <!-- page optional script -->
    <!-- Buttons -->
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/vfs_fonts.js') }}"></script>
    <script>
        $("#DueNearTable").DataTable({
            dom: "Bfrtip",
            responsive: false,
            autoWidth: false,
            scrollX: true,
            buttons: [{
                    extend: "excelHtml5",
                    title: "Due Date Near Sheet",
                    text: "Export to Excel",
                    className: "btn btn-secondary",
                    exportOptions: {
                        columns: ":not(.noExport)",
                    },
                },
                {
                    extend: "pdfHtml5",
                    title: "Due Date Near Sheet",
                    text: "Export to PDF",
                    className: "btn btn-success",
                    orientation: "landscape",
                    pageSize: "A4",
                    exportOptions: {
                        columns: ":not(.noExport)",
                    },
                    customize: function(doc) {
                        doc.styles.title = {
                            fontSize: 14,
                            bold: true,
                            alignment: "center"
                        };
                        doc.defaultStyle.fontSize = 10;
                        doc.pageMargins = [20, 20, 20, 20];
                    }
                }
            ],
        });

        $("#dueTable").DataTable({
            dom: "Bfrtip",
            responsive: false,
            autoWidth: false,
            scrollX: true,
            buttons: [{
                    extend: "excelHtml5",
                    title: "Due Date Passed Sheet",
                    text: "Export to Excel",
                    className: "btn btn-secondary",
                    exportOptions: {
                        columns: ":not(.noExport)",
                    },
                },
                {
                    extend: "pdfHtml5",
                    title: "Due Date Passed Sheet",
                    text: "Export to PDF",
                    className: "btn btn-success",
                    orientation: "landscape",
                    pageSize: "A4",
                    exportOptions: {
                        columns: ":not(.noExport)",
                    },
                    customize: function(doc) {
                        doc.styles.title = {
                            fontSize: 14,
                            bold: true,
                            alignment: "center"
                        };
                        doc.defaultStyle.fontSize = 10;
                        doc.pageMargins = [20, 20, 20, 20];
                    }
                }
            ],
        });
    </script>
    <script src="{{ asset('plugins/scripts/dashboard.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>
    @include('components.notification')
</body>
