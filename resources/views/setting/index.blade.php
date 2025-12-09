@include('components.header')
@php
    use App\Models\SmsProvider;
@endphp
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
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


<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('components.navbar')
        @include('components.sidebar')
        <!-- Content Wrapper. Contains page content -->
        <!-- Content Header (Page header) -->

        <section class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>{{ __('lang.SETTING') }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">{{ __('lang.SETTING') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">

                            <!-- Settings Card -->
                            <div class="card card-outline card-primary">
                                <div class="card-header p-0 border-bottom-0">
                                    <ul class="nav nav-pills p-2">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#sms_api" data-toggle="tab">
                                                <i class="fa fa-cogs"></i> {{ __('lang.SMS_API') }}
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#sms_templates" data-toggle="tab">
                                                <i class="fa fa-envelope"></i> {{ __('lang.TEMPLATE_AND_TRIGGER') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <div class="tab-content">
                                        {{-- ===========================
                TAB 1: SMS API SETTINGS
            ============================ --}}
                                        <div class="tab-pane active" id="sms_api">
                                            <form action="{{ route('setting.store') }}" method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label for="name" class="form-label required">Provider
                                                                Name</label>
                                                            <input type="text" name="name" class="form-control"
                                                                required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <label for="method" class="form-label required">HTTP Method</label>
                                                            <select name="method" class="form-control" required>
                                                                <option value="">Select</option>
                                                                <option value="{{ SmsProvider::METHOD_POST }}">POST
                                                                </option>
                                                                <option value="{{ SmsProvider::METHOD_GET }}">GET
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label for="base_url" class="form-label required">API URL</label>
                                                            <input type="text" name="base_url" class="form-control"
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="text-center mt-3">
                                                    <button type="reset" class="btn btn-secondary">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Save</button>
                                                </div>
                                            </form>
                                        </div>
                                        {{-- =========================== TAB 2: SMS TEMPLATE MANAGEMENT ============================ --}}
                                        <div class="tab-pane fade" id="sms_templates">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <form action="{{ route('assignTemplate.store') }}" method="POST">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group mb-3">
                                                                    <label>Template</label>
                                                                    <select class="form-control select2"
                                                                        id="sms_template_id" name="sms_template_id">
                                                                        <option value="" selected>Select Template
                                                                        </option>
                                                                        @foreach ($smstems as $tem)
                                                                            <option value="{{ $tem->id }}">
                                                                                {{ $tem->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group mb-3">
                                                                    <label
                                                                        for="">{{ __('lang.SELECT_TRIGGER') }}</label>
                                                                    <select class="form-control select2"
                                                                        id="trigger_name" name="trigger_name">
                                                                        <option value="" selected>Select</option>
                                                                        @foreach ($triggers as $key => $label)
                                                                            <option value="{{ $key }}">
                                                                                {{ $label }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group mb-3">
                                                                    <label class="required">Message Template</label>
                                                                    <textarea name="message" class="form-control" id="messagedb" rows="4"
                                                                        placeholder="Write your message here using placeholders like <customer_name>, <installment_amount>, <due_date>..."
                                                                        required></textarea>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="text-center mt-3">
                                                            <button type="reset"
                                                                class="btn btn-secondary">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Save
                                                                Template</button>
                                                        </div>
                                                    </form>
                                                    <hr>
                                                    <h1> {{ __('lang.SMS_TEMPLATE') }}</h1>
                                                    {{-- <hr> --}}
                                                    <form action="{{ route('smsTemplate.store') }}" method="POST">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group mb-3">
                                                                    <label class="required">Template Name</label>
                                                                    <input type="text" name="name"
                                                                        class="form-control"
                                                                        placeholder="e.g., Installment Reminder"
                                                                        required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group mb-3">
                                                                    <label class="required">Message Template</label>
                                                                    <textarea name="message" class="form-control" rows="4" required
                                                                        placeholder="Write your message here using placeholders like <customer_name>, <installment_amount>, <due_date>..."
                                                                        required></textarea>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="text-center mt-3">
                                                            <button type="reset"
                                                                class="btn btn-secondary">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Save
                                                                Template</button>
                                                        </div>
                                                    </form>


                                                </div>
                                                <div class="col-md-5 mt-3">
                                                    <table class="table table-bordered table-striped">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Placeholder</th>
                                                                <th>Description</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><code>&lt;customer_name&gt;</code></td>
                                                                <td>Customer’s full name</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>&lt;installment_amount&gt;</code></td>
                                                                <td>Amount of the current installment</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>&lt;month_name&gt;</code></td>
                                                                <td>Name of the installment month</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>&lt;due_date&gt;</code></td>
                                                                <td>Date when the installment is due</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>&lt;shop_name&gt;</code></td>
                                                                <td>Name of your shop or company</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>&lt;total_amount&gt;</code></td>
                                                                <td>Total payable amount</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>&lt;paid_amount&gt;</code></td>
                                                                <td>Amount already paid by the customer</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>&lt;remaining_balance&gt;</code></td>
                                                                <td>Outstanding balance amount</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>&lt;payment_date&gt;</code></td>
                                                                <td>Date when payment was made</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>&lt;product_name&gt;</code></td>
                                                                <td>Name of the purchased product or service</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>&lt;invoice_no&gt;</code></td>
                                                                <td>Invoice or receipt number</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>&lt;contact_no&gt;</code></td>
                                                                <td>Customer’s registered contact number</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>&lt;address&gt;</code></td>
                                                                <td>Customer’s or branch’s address</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>&lt;branch_name&gt;</code></td>
                                                                <td>Name of the related branch</td>
                                                            </tr>
                                                            <tr>
                                                                <td><code>&lt;remaining_quantity&gt;</code></td>
                                                                <td>Remaining quantity in stock</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </section>

        </section>
        @include('components.footer')

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
    </div>
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
    @include('components.notification')
    <script>
        $('.select2').select2({
            placeholder: "Select",
            allowClear: true,
            width: '100%'
        });
        $("#datetimeForm").on("submit", function(e) {
            let valid = true;
            if ($("#date_format").val() === "" || $("#date_format").val() === null) {
                toastr.error(
                    "Please select a Date Format.",
                    "Error!"
                );
                valid = false;
            }
            if ($("input[name='time_format']:checked").length === 0) {
                toastr.error(
                    "Please select a Time Format.",
                    "Error!"
                );
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
            }
        });
        $("#sms_template_id").on("change", function() {
            var id = $(this).val();
            if (id) {
                $.ajax({
                    url: "/getTemplete/" + id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        console.log(data.message);
                        $("#messagedb").val(
                            data.message
                        );

                    },
                });
            } else {
                $("#product_id").empty();
                $("#product_id").append(
                    "<option disabled selected>Select Product</option>"
                );
            }
        });
    </script>

</body>
