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
                        <h1>{{ __('lang.BOOKING') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">Home</a></li>
                            <li class="breadcrumb-item active">{{ __('lang.ADD_BOOKING') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.BOOKING') }}</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="BookForm" action="{{ route('booking.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="required" for=""> {{ __('lang.RECOVERY_MAN') }}</label>
                                        <select class="form-control select2" id="recovery_man_id" name="recovery_man_id"
                                            required>
                                            <option value="" hidden>Select</option>
                                            @foreach ($recoveryMan as $man)
                                                <option value="{{ $man->id }}">
                                                    {{ $man->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="required" for=""> {{ __('lang.CUSTOMER') }}</label>
                                        <select class="form-control select2" id="accountDD" name="account_id" required>
                                            <option value="" hidden>Select</option>
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">
                                                    {{ $customer->name }} ({{ $customer->contact }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="required" for="">{{ __('lang.PRODUCT_TYPE') }}</label>
                                        <select class="form-control select2" id="productType" name="product_type"
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
                                    <div class="form-group mb-3">
                                        <label class="required" for="">{{ __('lang.PRODUCT_NAME') }}</label>
                                        <select class="form-control select2" id="productDD" name="product_id" required>
                                            <option value="" selected disabled>Select</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="purchase_id" id="purchase_id">
                                    <div class="form-group mb-3">
                                        <label for="">{{ __('lang.IMEI_SERIAL_NO') }}</label>
                                        <input type="text" class="form-control" name="imei_no"
                                            value="{{ old('imei_no') }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="required" for="">{{ __('lang.DEAL_DATE') }}</label>
                                        <input type="text" class="form-control datePicker" name="deal_date"
                                            id="deal_date" autocomplete="off" value="" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="required" for="">{{ __('lang.MONTHS') }}</label>
                                        <input type="number" class="form-control" id="installmentMonths"
                                            name="total_months" value="1" autocomplete="off" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="">{{ __('lang.GARANTREE_WARRENTY_MONTH') }}</label>
                                        <input type="number" class="form-control" id="warranty_period"
                                            name="warranty_period" value="0" min="1">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <label class="required" for="">{{ __('lang.TOTAL_PAYMENT') }}</label>
                                        <input type="number" class="form-control" id="totalPayment"
                                            name="total_payment" placeholder="Total Payment" min="0" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="required" for="">{{ __('lang.DISCOUNT') }}</label>
                                        <input type="number" class="form-control" id="discountPayment"
                                            name="discount_amount" placeholder="Discount" value="0"
                                            min="0" step="1" onblur="if(this.value===''){this.value=0;}"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">{{ __('lang.NET_PAYMENT') }}</label>
                                        <input type="number" class="form-control" id="netPayment"
                                            name="net_payment" value="0" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="required"
                                            for="">{{ __('lang.ADVANCE_PAYMENT') }}</label>
                                        <input type="number" class="form-control" id="downPayment"
                                            name="down_payment" value="0" min="0" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">{{ __('lang.REMAINING_AMOUNT') }}</label>
                                        <input type="number" class="form-control" id="remainingAmount"
                                            name="remaining_amount" value="0" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="">{{ __('lang.MONTHLY_INSTALLMENT') }}</label>
                                        <input type="number" class="form-control" id="monthlyInstallment"
                                            name="monthly_installment" value="0" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="">{{ __('lang.LATE_PAYMENT_PENALTY') }}</label>
                                        <input type="number" class="form-control" id="late_payment_penalty"
                                            name="late_payment_penalty" value="0" min="0">
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label for="">{{ __('lang.START_MONTH') }}</label>
                                                <input type="month" class="form-control" id="startDate"
                                                    name="start_month" value="{{ now()->format('Y-m') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label class="required"
                                                    for="">{{ __('lang.DUE_DATE') }}</label>
                                                <input type="text" class="form-control dayPicker" id="monthDate"
                                                    name="due_date" min="1" max="31"
                                                    placeholder="Due date of every month" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('bookingManagement.index') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                    </div>
                    </form>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </section>
        <section class="content">
            <div class="card" style="margin-top: 2em; margin-left: 1em; margin-right: 1em;">
                <div class="card-header">
                    <h3 class="card-title">Available Accounts</h3>
                </div>
                <div class="card-body">
                    <table id="bookingTable" class="table table-bordered table-striped display">
                        <thead>
                            <tr>
                                <th>{{ __('lang.SR') }}</th>
                                <th>{{ __('lang.ACCOUNT_TYPE') }}</th>
                                <th>{{ __('lang.NAME') }}</th>
                                <th>{{ __('lang.CNIC') }}</th>
                                <th>{{ __('lang.CONTACT') }}</th>
                                <th>{{ __('lang.ADDRESS') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $cus)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $cus->type }}</td>
                                    <td>{{ $cus->name }}</td>
                                    <td>{{ $cus->cnic ?? 'Nill' }}</td>
                                    <td>{{ $cus->contact ?? 'Nill' }}</td>
                                    <td>{{ $cus->address ?? 'Nill' }}</td>
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
    <script src="{{ asset('plugins/flatPicker/flatpickr.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <!-- Buttons -->
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script></script>
    <!-- page optional script -->
    <script src="{{ asset('plugins/scripts/booking.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>

    @include('components.notification')
</body>
