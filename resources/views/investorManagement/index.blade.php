@include('components.header')

@php
    use App\Models\Account;
@endphp

<meta name="csrf-token" content="{{ csrf_token() }}">
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
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/flatPicker/flatpickr.min.css') }}">


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
                        <h1>{{ __('lang.ADD_INVESTOR') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">Home</a></li>
                            <li class="breadcrumb-item active">{{ __('lang.ADD_INVESTOR') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('lang.ADD_INVESTOR') }}</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="investorForm" action="{{ route('investorManagement.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="type" id="type" value="{{ ACCOUNT::INVESTOR }}">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.NAME') }}</label>
                                        <input name="name" class="form-control" placeholder="{{ __('lang.NAME') }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.INVESTMENT_DATE') }}</label>
                                        <input type="number" name="investment_date" id="investment_date"
                                            class="form-control bg-white" required>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6" id="investmentAmountWrapper">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.INVESTMENT_AMOUNT') }}</label>
                                        <input type="number" name="amount" id="investmentAmount" class="form-control"
                                            placeholder="Enter Investment Amount" required min="1">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 userAccount">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.CONTACT') }}</label>
                                        <input type="number" name="contact" class="form-control" maxlength="20"
                                            placeholder="Cell No *Exp(xxxx-xxxxxxx)" required>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 userAccount">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.CNIC') }}</label>
                                        <input type="text" name="cnic" id="cnic" maxlength="30"
                                            class="form-control" oninput="cnicFormat(this)"
                                            placeholder="CNIC No *Exp(xxxxx-xxxxxxx-x)" value="{{ old('cnic') }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 userAccount">
                                    <div class="form-group">
                                        <label>{{ __('lang.ADDRESS') }}</label>
                                        <input name="address" class="form-control" placeholder="Enter Address">
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
                </div>

            </div>
        </section>
        <section class="content">
            <div class="card" style="margin-top: 2em; margin-left: 1em; margin-right: 1em;">
                <div class="card-header">
                    <h3 class="card-title">{{ __('lang.AVAILABLE_INVESTOR') }}</h3>
                </div>
                <div class="card-body">
                    <table id="investorTable" class="table table-bordered table-striped display">
                        <thead>
                            <tr>
                                <th>{{ __('lang.SR') }}</th>
                                <th>{{ __('lang.NAME') }}</th>
                                <th>{{ __('lang.CONTACT') }}</th>
                                <th>{{ __('lang.CNIC') }}</th>
                                <th>{{ __('lang.TOTAL_INVESTMENT') }}</th>
                                <th>{{ __('lang.ADDRESS') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts as $account)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td
                                        @if ($account->name !== $businessAccount) class="update"
        data-name="name"
        data-type="text"
        data-pk="{{ $account->id }}"
        data-title="Enter name" @endif>
                                        {{ $account->name != 0 ? $account->name : 'Nill' }}
                                    </td>

                                    <td class="update" data-name="contact_person" data-type="text"
                                        data-pk="{{ $account->id }}" data-title="Enter contact">
                                        {{ $account->contact }}
                                    </td>
                                    <td data-name="cnic" data-type="text" data-pk="{{ $account->id }}"
                                        data-title="Enter Cnic">
                                        {{ $account->cnic }}
                                    </td>
                                    <td>
                                        {{ $account->total_investment }}
                                    </td>

                                    <td class="update" data-name="address" data-type="text"
                                        data-pk="{{ $account->id }}" data-title="Enter address">
                                        {{ $account->address != 0 ? $account->address : 'Nill' }}
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
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <!-- Buttons -->
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/flatPicker/flatpickr.js') }}"></script>
    <script src="{{ asset('plugins/scripts/investor.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>
    @include('components.notification')
</body>
