@include('components.header')
@php
    use App\Models\Product;
@endphp

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
                        <h1>{{ __('lang.ADD_PRODUCT') }}</h1>
                    </div>
                   
                </div>
            </div>
        </section>

        <section class="content">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.PRODUCT') }}</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="productForm" action="{{ route('product.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.PRODUCT_TYPE') }}</label>
                                        <select class="form-control select2" name="product_type" required>
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
                                        <label class="required">{{ __('lang.COMPANY_NAME') }}</label>
                                        <input type="text" name="product_company" class="form-control"
                                            placeholder="Enter Company" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.PRODUCT_NAME') }}</label>
                                        <input type="text" name="product_name" id="product_name" class="form-control"
                                            placeholder="Enter Product Name" required>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.PRODUCT_DETAILS') }}</label>
                                        <input type="text" name="product_details" class="form-control"
                                            placeholder="Enter Details">
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                                <button type="submit" class="btn btn-success" id="saveProduct">Save</button>
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
                    <h3 class="card-title">{{ __('lang.AVAILABLE_PRODUCT') }}</h3>
                </div>
                <div class="card-body">
                    <table id="productTable" class="table table-bordered table-striped display">
                        <thead class="text-center">
                            <tr>
                                <th>{{ __('lang.SR') }}</th>
                                <th>{{ __('lang.PRODUCT_TYPE') }}</th>
                                <th>{{ __('lang.COMPANY_NAME') }}</th>
                                <th>{{ __('lang.PRODUCT_NAME') }}</th>
                                <th>{{ __('lang.PRODUCT_DETAILS') }}</th>
                                <th class="noExport">{{ __('lang.ACTION') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td class="update" data-name="product_type" data-type="select"
                                        data-pk="{{ $product->id }}">
                                        {{ $product->product_type }}
                                    </td>


                                    <td class="update" data-name="product_company" data-type="text"
                                        data-pk="{{ $product->id }}">
                                        {{ $product->product_company }}
                                    </td>
                                    <td class="update" data-name="product_name" data-type="text"
                                        data-pk="{{ $product->id }}">
                                        {{ $product->product_name }}
                                    </td>

                                    <td class="update" data-name="product_details" data-type="text"
                                        data-pk="{{ $product->id }}">
                                        {{ $product->product_details }}
                                    </td>

                                    <td>
                                        <a href="productDelete/{{ $product->id }}" class="btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this product?');">
                                            Delete
                                        </a>
                                    </td>
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
    <!-- Buttons -->
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script>
        var productType = @json(Product::types());
    </script>
    <!-- page optional script -->
    <script src="{{ asset('plugins/scripts/product.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>

    @include('components.notification')
</body>
