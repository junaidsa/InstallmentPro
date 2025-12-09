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
                        <h1>{{ __('lang.STOCK_REPORT') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">Home</a></li>
                            <li class="breadcrumb-item active">{{ __('lang.STOCK_REPORT') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.STOCK_REPORT') }}</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('report.stockReport') }}" method="GET">
                            <div class="row">
                                {{-- Stock Type --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.STOCK_TYPE') }}</label>
                                        <select class="form-control select2" name="stock_type" id="stock_type" required>
                                            <option value="All" {{ $stockType == 'All' ? 'selected' : '' }}>All
                                            </option>
                                            @foreach ($stockTypeList as $type)
                                                <option value="{{ $type }}"
                                                    {{ $stockType == $type ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Product Type --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ __('lang.PRODUCT_TYPE') }}</label>
                                        <select class="form-control select2" name="product_type" id="product_type">
                                            <option value="">Select</option>
                                            @foreach ($types as $type)
                                                <option value="{{ $type }}"
                                                    {{ $productType == $type ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Product --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.PRODUCT') }}</label>
                                        <select class="form-control select2" name="productDD" id="productDD" required>
                                            <option value="all_products"
                                                {{ $productId == 'all_products' ? 'selected' : '' }}>All Products
                                            </option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ $productId == $product->id ? 'selected' : '' }}>
                                                    {{ $product->product_name }} ({{ $product->product_company }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                                <button type="submit" name="searchPerson" value="1"
                                    class="btn btn-success">Search</button>
                            </div>
                        </form>
                    </div>

                </div>
        </section>
        @if (request()->has('searchPerson'))
            <section class="content">
                <div class="card" style="margin-top: 2em; margin-left: 1em; margin-right: 1em;">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.STOCK_REPORT') }}</h3>
                    </div>
                    <div class="card-body">
                        <table id="stockTable" class="table table-bordered table-striped display">
                            <thead>
                                <tr>
                                    <th>{{ __('lang.SR') }}</th>
                                    <th>{{ __('lang.PURCHASE_DATE') }}</th>
                                    <th>{{ __('lang.SUPPLIER_NAME') }}</th>
                                    <th>{{ __('lang.COST_PRICE') }}</th>
                                    <th>{{ __('lang.SALE_PRICE') }}</th>
                                    <th>{{ __('lang.PRODUCT_NAME') }}</th>
                                    <th>{{ __('lang.PURCHASE_QUANTITY') }}</th>
                                    <th>{{ __('lang.STOCK_QUANTITY') }}</th>
                                    <th>{{ __('lang.STOCK_COST') }}</th>
                                    <th>{{ __('lang.STOCK_WORTH') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $total_stock_cost = 0;
                                    $total_stock_worth = 0;
                                @endphp

                                @forelse ($records as $record)
                                    @php
                                        $stock_cost = $record->quantity_log * $record->cost_price;
                                        $stock_worth = $record->quantity * $record->sale_price;
                                        $total_stock_cost += $stock_cost;
                                        $total_stock_worth += $stock_worth;
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $record->purchase_date }}</td>
                                        <td>{{ $record->account->name ?? '-' }}</td>
                                        <td>{{ number_format($record->cost_price) }}</td>
                                        <td>{{ number_format($record->sale_price) }}</td>
                                        <td>{{ $record->product->product_company ?? '' }}
                                            {{ $record->product->product_name ?? '' }}</td>
                                        <td>{{ $record->quantity_log }}</td>
                                        <td>{{ $record->quantity }}</td>
                                        <td>{{ number_format($stock_cost) }}</td>
                                        <td>{{ number_format($stock_worth) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No Records Found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right font-weight-bold">{{ __('lang.TOTAL') }}</td>
                                    <td class="font-weight-bold">{{ number_format($total_stock_cost) }}</td>
                                    <td class="font-weight-bold">{{ number_format($total_stock_worth) }}</td>
                                </tr>
                            </tfoot>
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
    <!-- Buttons -->
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/scripts/utils.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>
    <script>
        $(document).ready(function() {
            $(".select2").select2();
            $("#stockTable").DataTable({
                dom: "Bfrtip",
                responsive: false,
                autoWidth: false,
                scrollX: true,
                buttons: [{
                        extend: "excelHtml5",
                        title: "Stock Report",
                        text: "Export to Excel",
                        className: "btn btn-secondary",
                        footer: true,
                        exportOptions: {
                            columns: ":not(.noExport)"
                        }
                    },
                    {
                        extend: "pdfHtml5",
                        title: "Stock Report",
                        text: "Export to PDF",
                        className: "btn btn-success",
                        orientation: "landscape",
                        pageSize: "A4",
                        footer: true, 
                        exportOptions: {
                            columns: ":not(.noExport)"
                        },
                        customize: function(doc) {
                            doc.styles.title = {
                                fontSize: 14,
                                bold: true,
                                alignment: "center"
                            };
                            doc.content[1].margin = [0, 10, 0, 0];
                        }
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    var intVal = function(i) {
                        return typeof i === "string" ?
                            i.replace(/[\$,]/g, "") * 1 :
                            typeof i === "number" ?
                            i :
                            0;
                    };
                    var totalStockCost = api
                        .column(8, {
                            page: "current"
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);

                    var totalStockWorth = api
                        .column(9, {
                            page: "current"
                        })
                        .data()
                        .reduce(function(a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    $(api.column(8).footer()).html(
                        totalStockCost.toLocaleString()
                    );
                    $(api.column(9).footer()).html(
                        totalStockWorth.toLocaleString()
                    );
                }
            });


        });
    </script>

    @include('components.notification')
</body>
