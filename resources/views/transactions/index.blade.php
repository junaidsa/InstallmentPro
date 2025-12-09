@include('components.header')
@php
    use App\Models\Transaction;
@endphp
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{ asset('plugins/ionicons/ionicons.min.css') }}">
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="{{ asset('plugins/jquery-editable/jquery-editable.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">


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
                        <h1>{{ __('lang.ADD_TRANSACTION') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Add Transaction</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Transaction</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 col-md-6 d-flex align-items-center">
                                    <div class="form-group flex-grow-1">
                                        <label class="required">{{ __('lang.ACCOUNT') }}</label>
                                        <select class="form-control select2" name="account_id" id="selectedId"
                                            style="width: 100%;" required>
                                            <option value="" selected="selected" disabled="disabled">Select
                                            </option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->name }} -
                                                    {{ $account->type }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-success ml-2"
                                        style="margin-top: 1em; margin-bottom: 1em;" data-toggle="modal"
                                        data-target="#addAccountModal">Add</button>
                                </div>
                                <div class="col-sm-12 col-md-6 mt-3 mt-md-0">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.AMOUNT') }}</label>
                                        <input type="number" id="amount" name="amount" class="form-control"
                                            placeholder="Enter Amount" required>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 mt-3">
                                    <div class="form-group">
                                        <label class="required">Transaction Type</label>
                                        <select class="form-control select2" name="type" id="type"
                                            style="width: 100%;" required>
                                            <option value="" hidden>Select</option>
                                            <option value="{{ Transaction::TYPE_CREDIT }}">
                                                {{ Transaction::TYPE_CREDIT }}</option>
                                            <option value="{{ Transaction::TYPE_DEBIT }}">
                                                {{ Transaction::TYPE_DEBIT }}</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6 mt-3">
                                    <label>{{ __('lang.METHOD') }}</label>
                                    <div class="form-group">
                                        <input type="radio" id="cash" name="method" value="Cash"
                                            style="margin-left: 2em;" checked>
                                        <label>{{ __('lang.CASH') }}</label>
                                        <input type="radio" id="transfer" name="method" value="Transfer"
                                            style="margin-left: 2em;">
                                        <label>{{ __('lang.TRANSFER') }}</label>
                                        <input type="radio" id="online" name="method" value="Online"
                                            style="margin-left: 2em;">
                                        <label>{{ __('lang.ONLINE') }}</label>
                                        <input type="radio" id="cheque" name="method" value="Cheque"
                                            style="margin-left: 2em;">
                                        <label>{{ __('lang.CHEQUE') }}</label>
                                        <input type="radio" id="other" name="method" value="Other"
                                            style="margin-left: 2em;">
                                        <label>{{ __('lang.OTHER') }}</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 mt-3">
                                    <div class="form-group">
                                        <label>{{ __('lang.DOC') }}</label>
                                        <input id="docNo" name="doc_no" class="form-control"
                                            placeholder="Enter Doc No">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 mt-3">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.NARRATION') }}</label>
                                        <input id="narration" name="narration" class="form-control"
                                            placeholder="Enter Narration" required>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 mt-3">
                                    <label>{{ __('lang.IMAGE') }}</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input file-input-preview"
                                                name="image" id="image" accept="image/*">
                                            <label class="custom-file-label" for="image">{{ __('lang.IMAGE') }}
                                                (JPG, JPEG, PNG)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center" style="margin-top: 1em;">
                                <div class="col-auto">
                                    <button type="reset" class="btn btn-secondary">Cancel</button>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-success">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            {{-- <Account form> --}}
            <div class="modal fade" id="addAccountModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Account Info</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.ACCOUNT_TYPE') }}</label>
                                        <select class="form-control select2" name="account_type" id="accountType"
                                            style="width: 100%;">
                                            @foreach ($types as $type)
                                                <option value="{{ $type }}">
                                                    {{ __('lang.' . strtoupper($type)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.COMPANY') }}</label>
                                        <input id="company" name="company" class="form-control"
                                            placeholder="Enter Company Name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.NAME') }}</label>
                                        <input type="text" id="name" name="name" class="form-control"
                                            placeholder="Enter Name">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.CONTACT') }}</label>
                                        <input type="text" id="contact" onkeypress="preventNonNumeric(event)"
                                            name="contact_person" class="form-control" maxlength="12"
                                            pattern="[0-9]{4}-[0-9]{7}" title="xxxx-xxxxxxx"
                                            placeholder="Cell No *Exp(xxxx-xxxxxxx)">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.ADDRESS') }}</label>
                                        <input type="text" id="address" name="address" class="form-control"
                                            placeholder="Enter Adress">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('lang.CNIC') }}</label>
                                        <input type="text" id="cnic" name="cnic" maxlength="15"
                                            onkeypress="preventNonNumeric(event)" class="form-control"
                                            pattern="[0-9]{5}-[0-9]{7}-[0-9]{1}" title="xxxxx-xxxxxxx-x"
                                            placeholder="CNIC No *Exp(xxxxx-xxxxxxx-x)">
                                    </div>
                                </div>


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="submit" id="saveAccount" class="btn btn-success">Save
                                        changes</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
        </section>
        <section class="content">
            <div class="card" style="margin-top: 2em; margin-left: 1em; margin-right: 1em;">
                <div class="card-header">
                    <h3 class="card-title">Recent Transactions</h3>
                </div>
                <div class="card-body">
                    <table id="transactionTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('lang.SR') }}</th>
                                <th>{{ __('lang.TYPE') }}</th>
                                <th>{{ __('lang.ACCOUNT') }}</th>
                                <th>{{ __('lang.TRANSACTION_TYPE') }}</th>
                                <th>{{ __('lang.AMOUNT') }}</th>
                                <th>{{ __('lang.METHOD') }}</th>
                                <th>{{ __('lang.BALANCE') }}</th>
                                <th>{{ __('lang.IMAGE') }}</th>
                            </tr>
                        </thead>
                        <tbody id="bodyData">

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
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-editable/jquery-editable-poshytip.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('plugins/scripts/numberFormatter.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>
    <!-- page script -->

    <script>
        $(document).ready(function() {
            $('.select2').select2();
            const table = $("#transactionTable").DataTable({
                responsive: false,
                autoWidth: false,
                scrollX: true,
                dom: "Bfrtip",
                buttons: [{
                    extend: "excelHtml5",
                    title: "Transaction Sheet",
                    text: "Export to Excel",
                    className: "btn btn-secondary",
                    exportOptions: {
                        columns: ":not(.noExport)",
                    },
                }, ],
            });
            $('#selectedId').change(function() {
                var id = $(this).val();

                $.ajax({
                    type: 'GET',
                    url: '/getAccountTransaction/' + id,
                    success: function(data) {
                        let bodyData = "";
                        let i = 1;

                        $.each(data, function(index, row) {
                            let transactionType = '';
                            if (row.type) {
                                transactionType = row.type.toLowerCase() === 'debit' ?
                                    'Debit' :
                                    'Credit';
                            }

                            let imageIcon = '';
                            if (row.image) {
                                imageIcon =
                                    `<a href="/transaction-images/${row.image}" target="_blank"><i class="fas fa-image"></i></a>`;
                            }

                            bodyData += `
                        <tr>
                            <td>${i++}</td>
                            <td>${row.account ? row.account.type : ''}</td>
                            <td>${row.account ? row.account.name : ''}</td>
                            <td>${transactionType}</td>
                            <td>${row.amount ? Number(row.amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : ''}</td>
                            <td>${row.payment_mode ?? ''}</td>
                            <td>${row.balance}</td>
                            <td>${imageIcon}</td>
                        </tr>
                    `;
                        });

                        $('#bodyData').html(bodyData);
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                    }
                });
            });
            $(document).on("click", "#saveAccount", function() {
                var csrfToken = $('meta[name="csrf-token"]').attr("content");
                const accountType = $("#accountType").val();
                const name = $('#name').val();
                const company = $('#company').val();
                const cnic = $('#cnic').val();
                const contact = $('#contact').val();
                const address = $('#address').val();

                if (accountType === 'Expense') {

                    if (!name) {
                        alert('Name field is empty');
                        return;
                    }
                }
                if (accountType != 'Expense') {

                    if (!accountType || !name || !company || !cnic || !contact || !address) {
                        toastr('Some fields are empty');
                        return;
                    }
                }

                $.ajax({
                    url: "/manageAccounts",
                    type: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    data: {
                        account_type: accountType,
                        name: name,
                        company: company,
                        cnic: cnic,
                        contact_person: contact,
                        address: address
                    },
                    success: function(response) {
                        $("#selectedId").append(
                            $("<option>", {
                                value: response.data.id,
                                text: response.data.name + ' - ' +
                                    response.data.account_type,
                            })
                        );
                        $("#addAccountModal").modal("hide");
                        toastr.options = {
                            closeButton: true,
                            progressBar: true,
                        };
                        toastr.success(
                            accountType + " " + name + " Created Successfully",
                            "Success!"
                        );
                    },
                    error: function(error) {
                        toastr.error(
                            accountType + " " + name + " Could Not Created",
                            "Error!"
                        );
                    },
                });
            });
        });
    </script>
    @include('components.notification')
</body>
