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
                        <h1>{{ __('lang.PURCHASE') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">Home</a></li>
                            <li class="breadcrumb-item active">{{ __('lang.PURCHASE') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.ADD_PURCHASE') }}</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="purchaseForm" action="{{ route('purchase.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.SUPPLIER') }}</label>
                                        <select name="account_id" class="form-control select2" required>
                                            <option value="">Select Supplier</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.PRODUCT_TYPE') }}</label>
                                        <select class="form-control select2" name="product_type" id="product_type"
                                            required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach ($types as $type)
                                                <option value="{{ $type }}"
                                                    {{ old('product_type') == $type ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.PRODUCT') }}</label>
                                        <select name="product_id" id="product_id" class="form-control select2" required>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.DATE') }}</label>
                                        <input type="text" name="purchase_date" id="purchase_date"
                                            class="form-control" value="" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.QUANTITY') }}</label>
                                        <input type="number" name="quantity" id="quantity_log" class="form-control"
                                            min="1" onchange="calculatePayment(this.value)" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.COST_PRICE') }}</label>
                                        <input type="number" name="cost_price" id="cost_price" class="form-control"
                                            min="1" onchange="calculatePayment(this.value)" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.SALE_PRICE') }}</label>
                                        <input type="number" name="sale_price" id="sale_price" class="form-control"
                                            min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.TOTAL_PRICE') }}</label>
                                        <input type="number" id="total_price" name="total_price"
                                            class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{ __('lang.REMARKS') }}</label>
                                        <input type="text" name="remarks" class="form-control"
                                            onchange="calculatePayment()">
                                    </div>
                                </div>

                            </div>

                            <div class="text-center
                                            mt-3">
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                                <button type="submit" id="saveBtn" class="btn btn-success">Save</button>
                            </div>
                        </form>


                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </section>
        <section class="content">
            <div class="card" style="margin-top: 2em; margin-left: 1em; margin-right: 1em;">
                <div class="card-header">
                    <h3 class="card-title">{{ __('lang.AVAILABLE_PURCHASE') }}</h3>
                </div>
                <div class="card-body">
                    <table id="purchasetTable" class="table table-bordered table-striped display">
                        <thead>
                            <tr>
                                <th>{{ __('lang.SR') }}</th>
                                <th>{{ __('lang.SUPPLIER') }}</th>
                                <th>{{ __('lang.PRODUCT_TYPE') }}</th>
                                <th>{{ __('lang.PRODUCT_NAME') }}</th>
                                <th>{{ __('lang.PURCHASE_DATE') }}</th>
                                <th>{{ __('lang.QUANTITY') }}</th>
                                <th>{{ __('lang.COST_PRICE') }}</th>
                                <th>{{ __('lang.SALE_PRICE') }}</th>
                                <th>{{ __('lang.TOTAL_PRICE') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $purchase)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td data-pk="{{ $purchase->id }}">{{ $purchase->account->name ?? '' }}</td>

                                    <td>{{ $purchase->product->product_type ?? '' }}</td>

                                    <td>{{ $purchase->product->product_name ?? '' }}</td>

                                    <td data-pk="{{ $purchase->id }}">{{ $purchase->purchase_date }}</td>

                                    <td>{{ $purchase->quantity }}</td>
                                    <td>{{ number_format($purchase->cost_price) }}</td>
                                    <td data-pk="{{ $purchase->id }}">{{ number_format($purchase->sale_price) }}</td>
                                    <td>{{ number_format($purchase->total_price) }}</td>
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

    <script>
        suppliers = @json($suppliers);
    </script>

    <script src="{{ asset('plugins/scripts/purchase.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>

    @include('components.notification')
</body>
