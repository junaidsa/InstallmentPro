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
<!-- DataTables Buttons -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
<!-- Toastr -->
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

<body class="hold-transition sidebar-mini layout-fixed">
    <style>
        .chart-container {
            position: relative;
            height: 400px;
            margin: 20px 0;
        }
    </style>

    @include('components.navbar')
    @include('components.sidebar')

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('lang.ITEM_WISE_PROFIT') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">{{ __('lang.HOME') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('lang.REPORTS') }}</li>
                            <li class="breadcrumb-item active">{{ __('lang.ITEM_WISE_PROFIT') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <!-- Filter Form -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.ITEM_WISE_PROFIT_REPORT') }}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('reports.item-wise-profit') }}" id="filterForm">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="product_id">{{ __('lang.SELECT_PRODUCT') }}</label>
                                        <select class="form-control select2" id="product_id" name="product_id">
                                            <option value="all">{{ __('lang.ALL_PRODUCTS') }}</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ $productId == $product->id ? 'selected' : '' }}>
                                                    {{ $product->product_name }} ({{ $product->product_company }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="start_date">{{ __('lang.START_DATE') }}</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date"
                                            value="{{ $startDate }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="end_date">{{ __('lang.END_DATE') }}</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date"
                                            value="{{ $endDate }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('reports.item-wise-profit') }}"
                                    class="btn btn-secondary">{{ __('lang.CANCEL') }}</a>
                                <button type="submit" class="btn btn-success" name="generate_report"
                                    value="1">{{ __('lang.GENERATE_REPORT') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
        </section>
        <section class="content">
            @if (isset($reportData) && $reportData->count() > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.ITEM_WISE_PROFIT_REPORT') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="profitTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('lang.PRODUCT_NAME') }}</th>
                                        <th>{{ __('lang.PRODUCT_COMPANY') }}</th>
                                        <th>{{ __('lang.QUANTITY_SOLD') }}</th>
                                        <th>{{ __('lang.TOTAL_SALES') }}</th>
                                        <th>{{ __('lang.TOTAL_COST') }}</th>
                                        <th>{{ __('lang.TOTAL_PROFIT') }}</th>
                                        <th>{{ __('lang.PROFIT_PERCENTAGE') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reportData as $item)
                                        <tr>
                                            <td class="text-center">{{ $item->product_name }}</td>
                                            <td class="text-center">{{ $item->product_company }}</td>
                                            <td class="text-center">{{ $item->quantity_sold }}</td>
                                            <td class="text-center">{{ number_format($item->total_sales) }}</td>
                                            <td class="text-center">{{ number_format($item->total_cost) }}</td>
                                            <td class="text-center">

                                                {{ number_format($item->total_profit) }}

                                            </td>
                                            <td class="text-center">

                                                {{ number_format($item->profit_percentage, 2) }}%

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-center">{{ __('lang.TOTAL') }}</th>
                                        <th></th>
                                        <th class="text-center">{{ $summary->total_quantity }}</th>
                                        <th class="text-center">{{ number_format($summary->total_sales) }}</th>
                                        <th class="text-center">{{ number_format($summary->total_cost) }}</th>
                                        <th class="text-center">{{ number_format($summary->total_profit) }}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>


                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </div>

    @include('components.footer')

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
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- DataTables Buttons -->
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/vfs_fonts.js') }}"></script>
    <!-- Toastr -->
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script>
        const excelText = "{{ __('lang.EXPORT_TO_EXCEL') }}";
        const pdfText = "{{ __('lang.EXPORT_TO_PDF') }}";
    </script>
    <script src="{{ asset('plugins/scripts/itemwiseProfit.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>

    @include('components.notification')
</body>

</html>
