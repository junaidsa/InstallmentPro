@include('components.header')
@php
    use App\Models\Booking;
    $today = now()->format('d-m-Y');
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
                        <h1>{{ __('lang.DATE_WISE_SALE_REPORT') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">Home</a></li>
                            <li class="breadcrumb-item active">{{ __('lang.DATE_WISE_SALE_REPORT') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.DATE_WISE_SALE_REPORT') }}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('sale.report') }}" method="GET">
                            <div class="row">
                                <div class="col-md-3">
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

                                <div class="col-md-3">
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

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('lang.PRODUCT_TYPE') }}</label>
                                        <select class="form-control select2" name="product_type" id="product_type">
                                            <option value="">All</option>
                                            @foreach ($types as $type)
                                                <option value="{{ $type }}"
                                                    {{ request('product_type') == $type ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('lang.PRODUCT') }}</label>
                                        <select class="form-control select2" name="product_id" id="product_id">
                                            <option value="">All</option>
                                            @foreach ($products ?? [] as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                    {{ $product->product_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-center" style="margin-top: 1em;">
                                <div class="col-auto">
                                    <a href="{{ route('sale.report') }}"
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
                    <h3 class="card-title">{{ __('lang.DATE_WISE_SALE_REPORT') }}</h3>
                </div>
                <div class="card-body">
                    <table id="saleReport" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('lang.SR') }}</th>
                                <th>{{ __('lang.CUSTOMER') }}</th>
                                <th>{{ __('lang.DEAL_DATE') }}</th>
                                <th>{{ __('lang.COMPANY') }}</th>
                                <th>{{ __('lang.PRODUCT') }}</th>
                                <th>{{ __('lang.DUE_DATE') }}</th>
                                <th>{{ __('lang.WARRANTY_PERIOD') }}</th>
                                <th>{{ __('lang.TOTAL_MONTH') }}</th>
                                <th>{{ __('lang.MONTHLY_INSTALLMENT') }}</th>
                                <th>{{ __('lang.DOWN_PAYMENT') }}</th>
                                <th>{{ __('lang.DISCOUNT_AMOUNT') }}</th>
                                <th>{{ __('lang.REMAINING_AMOUNT') }}</th>
                                <th>{{ __('lang.TOTAL_PAYABLE') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $sr = 1; @endphp
                            @foreach ($sales ?? [] as $date => $bookings)
                                @foreach ($bookings as $booking)
                                    <tr>
                                        <td>{{ $sr++ }}</td>
                                        <td>{{ $booking->account->name ?? 'N/A' }}</td>
                                        <td>{{ $booking->deal_date_formatted ?? 'N/A' }}</td>
                                        <td>{{ $booking->product->product_company ?? 'N/A' }}</td>
                                        <td>{{ $booking->product->product_name ?? 'N/A' }}</td>
                                        <td>{{ $booking->due_date ?? 'N/A' }}</td>
                                        <td>{{ $booking->warranty_period ?? 'N/A' }}</td>
                                        <td>{{ number_format($booking->total_months) }}</td>
                                        <td>{{ number_format($booking->monthly_installment) }}</td>
                                        <td>{{ number_format($booking->down_payment) }}</td>
                                        <td>{{ number_format($booking->discount_amount) }}</td>
                                        <td>{{ number_format($booking->remaining_amount) }}</td>
                                        <td>{{ number_format($booking->total_amount) }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                        <tr>
                            <th colspan="12" class="text-end">{{ __('lang.TOTAL_SALES') }}:</th>
                            <th>{{ number_format($totalSales ?? 0) }}</th>
                        </tr>
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
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/flatPicker/flatpickr.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/vfs_fonts.js') }}"></script>
    <!-- page optional script -->
    <script src="{{ asset('plugins/scripts/saleReport.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>
    @include('components.notification')
</body>
