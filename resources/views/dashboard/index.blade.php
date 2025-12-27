@include('components.header')
@php
    use App\Models\Notification;
@endphp
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
<!--  Favicon -->
<link rel="shortcut icon" type="image/png" href="{{ asset('dist/images/logos/favicon.ico') }}" />
<!-- Owl Carousel  -->
<link rel="stylesheet" href="{{ asset('dist/libs/owl.carousel/dist/assets/owl.carousel.min.css') }}">

<!-- Core Css -->
<link id="themeColors" rel="stylesheet" href="{{ asset('dist/css/style.min.css') }}" />

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

    <div class="page-wrapper" id="main-wrapper" data-theme="blue_theme" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        @include('components.sidebar')
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            @include('components.navbar')
            <!--  Header End -->
            <div class="container-fluid">
                <!--  Owl carousel -->
                <div class="owl-carousel counter-carousel owl-theme">
                    <!-- Total Customers -->
                    <div class="item">
                        <div class="card border-0 zoom-in bg-light-primary shadow-none">
                            <div class="card-body text-center">
                                <div class="icon mb-2">
                                    <i class="ti ti-users h2 text-primary"></i>
                                </div>
                                <p class="fw-semibold fs-3 text-primary mb-1">{{ __('lang.TOTAL_CUSTOMERS') }}</p>
                                <h5 class="fw-semibold text-primary mb-0">{{ $totalCustomers }}</h5>
                                <a href="{{ route('report.statusReport', ['selectedMonth' => now()->format('Y-m')]) . '&searchPerson=1' }}"
                                    class="small-box-footer text-primary">
                                    {{ __('lang.MORE_INFO') }} <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Total Stock -->
                    <div class="item">
                        <div class="card border-0 zoom-in bg-light-warning shadow-none">
                            <div class="card-body text-center">
                                <div class="icon mb-2">
                                    <i class="ti ti-stack h2 text-warning"></i>
                                </div>
                                <p class="fw-semibold fs-3 text-warning mb-1">{{ __('lang.TOTAL_STOCK') }}</p>
                                <h5 class="fw-semibold text-warning mb-0">{{ $totalStock }}</h5>
                                <a href="{{ route('report.statusReport', ['selectedMonth' => now()->format('Y-m')]) . '&searchPerson=1' }}"
                                    class="small-box-footer text-warning">
                                    {{ __('lang.MORE_INFO') }} <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Sold Stock -->
                    <div class="item">
                        <div class="card border-0 zoom-in bg-light-info shadow-none">
                            <div class="card-body text-center">
                                <div class="icon mb-2">
                                    <i class="ti ti-stack h2 text-info"></i>
                                </div>
                                <p class="fw-semibold fs-3 text-info mb-1">{{ __('lang.SOLD_STOCK') }}</p>
                                <h5 class="fw-semibold text-info mb-0">{{ $totasalestock }}</h5>
                                <a href="{{ url('stockReport?stock_type=Sold&product_type=&productDD=all_products&searchPerson=1') }}"
                                    class="small-box-footer text-info">
                                    {{ __('lang.MORE_INFO') }} <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Available -->
                    <div class="item">
                        <div class="card border-0 zoom-in bg-light-danger shadow-none">
                            <div class="card-body text-center">
                                <div class="icon mb-2">
                                    <i class="ti ti-box h2 text-danger"></i>
                                </div>
                                <p class="fw-semibold fs-3 text-danger mb-1">{{ __('lang.STOCK_AVAILABLE') }}</p>
                                <h5 class="fw-semibold text-danger mb-0">{{ $totalAvailablestock }}</h5>
                                <a href="{{ url('stockReport?stock_type=Available&product_type=&productDD=all_products&searchPerson=1') }}"
                                    class="small-box-footer text-danger">
                                    {{ __('lang.MORE_INFO') }} <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Received Amount -->
                    <div class="item">
                        <div class="card border-0 zoom-in bg-light-success shadow-none">
                            <div class="card-body text-center">
                                <div class="icon mb-2">
                                    <i class="ti ti-transfer-in h2 text-success"></i>
                                </div>
                                <p class="fw-semibold fs-3 text-success mb-1">{{ __('lang.RECEIVED_AMOUNT') }}</p>
                                <h5 class="fw-semibold text-success mb-0">{{ number_format($receivedAmount) }}</h5>
                                <a href="{{ route('report.statusReport', ['selectedMonth' => now()->format('Y-m')]) . '&searchPerson=1' }}"
                                    class="small-box-footer text-success">
                                    {{ __('lang.MORE_INFO') }} <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Outstanding Amount -->
                    <div class="item">
                        <div class="card border-0 zoom-in bg-light-primary shadow-none">
                            <div class="card-body text-center">
                                <div class="icon mb-2">
                                    <i class="ti ti-cash h2 text-primary"></i>
                                </div>
                                <p class="fw-semibold fs-2 text-primary mb-1">{{ __('lang.OUTSTANDING_AMOUNT') }}</p>
                                <h5 class="fw-semibold text-primary mb-0">{{ number_format($outstandingAmount) }}</h5>
                                <a href="{{ route('report.statusReport', ['selectedMonth' => now()->format('Y-m')]) . '&searchPerson=1' }}"
                                    class="small-box-footer">
                                    {{ __('lang.MORE_INFO') }} <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Today Sale -->
                    <div class="item">
                        <div class="card border-0 zoom-in bg-light-warning shadow-none">
                            <div class="card-body text-center">
                                <div class="icon mb-2">
                                    <i class="ti ti-pig-money h2 text-warning"></i>
                                </div>
                                <p class="fw-semibold fs-3 text-warning mb-1">{{ __('lang.TODAY_SALE') }}</p>
                                <h5 class="fw-semibold text-warning mb-0">{{ number_format($today_sale) }}</h5>
                                <a href="{{ route('sale.report', ['start_date' => $today, 'end_date' => $today]) }}"
                                    class="small-box-footer text-warning">
                                    {{ __('lang.MORE_INFO') }} <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- This Month Sale -->
                    <div class="item">
                        <div class="card border-0 zoom-in bg-light-danger shadow-none">
                            <div class="card-body text-center">
                                <div class="icon mb-2">
                                    <i class="ti ti-pig-money h2 text-danger"></i>
                                </div>
                                <p class="fw-semibold fs-3 text-danger mb-1">{{ __('lang.THIS_MONTH_SALE') }}</p>
                                <h5 class="fw-semibold text-danger mb-0">{{ number_format($monthSale) }}</h5>
                                <a href="{{ route('sale.report') }}?start_date={{ $start_date }}&end_date={{ $end_date }}"
                                    class="small-box-footer text-danger">
                                    {{ __('lang.MORE_INFO') }} <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <!-- Card -->
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <h4 class="card-title">{{ __('lang.DUE_DATE_PASSED') }}</h4>
                                    {{-- <select class="form-select w-auto ms-auto">
                                    <option selected="">January</option>
                                    <option value="1">February</option>
                                    <option value="2">March</option>
                                    <option value="3">April</option>
                                </select> --}}
                                </div>
                                <div class="table-responsive">
                                    <table class="table stylish-table v-middle mb-0 text-nowrap" id="dueTable">
                                        <thead>
                                            <tr>
                                                <th class="border-0 text-muted fw-normal text-center" colspan="2">
                                                    {{ __('lang.NAME') }}
                                                </th>
                                                {{-- <th class="border-0 text-muted fw-normal">{{ __('lang.NAME') }}</th> --}}
                                                <th class="border-0 text-muted fw-normal">{{ __('lang.AMOUNT') }}</th>
                                                <th class="border-0 text-muted fw-normal">{{ __('lang.DUE_DATE') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($duePassed as $index => $item)
                                                {{-- <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>{{ $item->booking->account->address }}</td>
                                                        <td></td>
                                                        <td>
                                                        </td>
                                                    </tr> --}}
                                                <tr>
                                                    <td>
                                                        <span
                                                            class="round-40 text-white d-flex align-items-center justify-content-center text-center rounded-circle bg-info">{{ $loop->iteration }}</span>
                                                    </td>
                                                    <td>
                                                        <h6 class="font-weight-medium mb-0">
                                                            {{ $item->booking->account->name }}</h6>
                                                        <small
                                                            class="text-muted">{{ $item->booking->account->contact }}</small>
                                                    </td>
                                                    <td>{{ number_format($item->amount) }}</td>
                                                    {{-- <td>
                                                <span class="badge bg-success px-2 py-1">Low</span>
                                            </td> --}}
                                                    <td>{{ \Carbon\Carbon::parse($item->due_date)->format('d M, Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <!-- Card -->
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <h4 class="card-title">{{ __('lang.DUE_DATE_NEAR') }}</h4>
                                </div>
                                <div class="table-responsive">
                                    <table class="table stylish-table v-middle mb-0 text-nowrap" id="DueNearTable">
                                        <thead>
                                            <tr>
                                                <th class="border-0 text-muted fw-normal">{{ __('lang.SR') }}</th>
                                                <th class="border-0 text-muted fw-normal" colspan="">
                                                    {{ __('lang.NAME') }}</th>
                                                <th class="border-0 text-muted fw-normal">{{ __('lang.AMOUNT') }}</th>
                                                <th class="border-0 text-muted fw-normal">{{ __('lang.DUE_DATE') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dueNear as $index => $item)
                                                <tr>
                                                    <td>
                                                        <span
                                                            class="round-40 text-white d-flex align-items-center justify-content-center text-center rounded-circle bg-info">{{ $loop->iteration }}</span>
                                                    </td>
                                                    <td>
                                                        <h6 class="font-weight-medium mb-0">
                                                            {{ $item->booking->account->name }}</h6>
                                                        <small
                                                            class="text-muted">{{ $item->booking->account->contact }}</small>
                                                    </td>
                                                    <td>{{ number_format($item->amount) }}</td>
                                                    {{-- <td>
                                                <span class="badge bg-success px-2 py-1">Low</span>
                                            </td> --}}
                                                    <td>{{ \Carbon\Carbon::parse($item->due_date)->format('d M, Y') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="row gx-0">
                            <div class="col-lg-12">
                                <div class="p-4 calender-sidebar app-calendar">
                                    <div id="calendar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- BEGIN MODAL -->
                <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="eventModalLabel">
                                    Add / Edit Event
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="">
                                            <label class="form-label">Event Title</label>
                                            <input id="event-title" type="text" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-4">
                                        <div><label class="form-label">Event Color</label></div>
                                        <div class="d-flex">
                                            <div class="n-chk">
                                                <div class="form-check form-check-primary form-check-inline">
                                                    <input class="form-check-input" type="radio" name="event-level"
                                                        value="Danger" id="modalDanger" />
                                                    <label class="form-check-label" for="modalDanger">Danger</label>
                                                </div>
                                            </div>
                                            <div class="n-chk">
                                                <div class="form-check form-check-warning form-check-inline">
                                                    <input class="form-check-input" type="radio" name="event-level"
                                                        value="Success" id="modalSuccess" />
                                                    <label class="form-check-label" for="modalSuccess">Success</label>
                                                </div>
                                            </div>
                                            <div class="n-chk">
                                                <div class="form-check form-check-success form-check-inline">
                                                    <input class="form-check-input" type="radio" name="event-level"
                                                        value="Primary" id="modalPrimary" />
                                                    <label class="form-check-label" for="modalPrimary">Primary</label>
                                                </div>
                                            </div>
                                            <div class="n-chk">
                                                <div class="form-check form-check-danger form-check-inline">
                                                    <input class="form-check-input" type="radio" name="event-level"
                                                        value="Warning" id="modalWarning" />
                                                    <label class="form-check-label" for="modalWarning">Warning</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 d-none">
                                        <div class="">
                                            <label class="form-label">Enter Start Date</label>
                                            <input id="event-start-date" type="text" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="col-md-12 d-none">
                                        <div class="">
                                            <label class="form-label">Enter End Date</label>
                                            <input id="event-end-date" type="text" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn" data-bs-dismiss="modal">
                                    Close
                                </button>
                                <button type="button" class="btn btn-success btn-update-event"
                                    data-fc-event-public-id="">
                                    Update changes
                                </button>
                                <button type="button" class="btn btn-primary btn-add-event">
                                    Add Event
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END MODAL -->

            </div>
            <div class="dark-transparent sidebartoggler"></div>
            <div class="dark-transparent sidebartoggler"></div>
            @include('components.footer')
        </div>

        <script src="{{ asset('dist/libs/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('dist/libs/simplebar/dist/simplebar.min.js') }}"></script>
        <script src="{{ asset('dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
        <!--  core files -->
        <script src="{{ asset('dist/js/app.min.js') }}"></script>
        <script src="{{ asset('dist/js/app.init.js') }}"></script>
        <script src="{{ asset('dist/js/app-style-switcher.js') }}"></script>
        <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
        <script src="{{ asset('dist/js/custom.js') }}"></script>
        <!--  current page js files -->
        <script src="{{ asset('dist/libs/owl.carousel/dist/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('dist/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
        <script src="{{ asset('dist/js/dashboard.js') }}"></script>
        <script src="{{ asset('dist/libs/fullcalendar/index.global.min.js') }}"></script>
        <script src="{{ asset('dist/js/apps/calendar-init.js') }}"></script>
        <script src="{{ asset('plugins/scripts/dashboard.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>
        <script>
            $(document).ready(function() {
                $("#dueTable").DataTable({
                    dom: "Bfrtip",
                    responsive: true,
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
                    ]
                });


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
            });
        </script>

        @include('components.notification')
</body>
