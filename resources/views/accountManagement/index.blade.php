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
                        <h1>{{ __('lang.ADD_ACCOUNT') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">{{ __('lang.HOME') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('lang.ADD_ACCOUNT') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header ">
                        <h3 class="card-title">{{ __('lang.ADD_ACCOUNT') }}</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                        </div>
                    </div>

                    <div class="card-body">
                        <form id="accountForm" action="{{ route('accountManagement.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.ACCOUNT_TYPE') }}</label>
                                        <select class="form-control select2" name="type" id="accountType" required>
                                            <option value="" disabled {{ old('type') ? '' : 'selected' }}>Select
                                            </option>
                                            @foreach (Account::types() as $type)
                                                <option value="{{ $type }}"
                                                    {{ old('type') == $type ? 'selected' : '' }}>
                                                    {{ __('lang.' . strtoupper($type)) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 userAccount">
                                    <div class="form-group">
                                        <label>{{ __('lang.OLD_ACCOUNT_NUMBER') }}</label>
                                        <input name="account_no" value="{{ old('account_no') }}" type="number"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 d-none employeeFields" id="designationField">
                                    <div class="form-group">
                                        <label>{{ __('lang.DESIGNATION') }}</label>
                                        <select class="form-control select2" name="designation" id="designation"
                                            style="width: 100%;">
                                            <option value="" selected="selected" disabled="disabled">
                                                Select
                                            </option>
                                            <option value="{{ Account::ADMINISTRATOR }}">
                                                {{ Account::ADMINISTRATOR }}
                                            </option>
                                            <option value="{{ Account::MANAGING_DIRECTOR }}">
                                                {{ Account::MANAGING_DIRECTOR }}
                                            </option>
                                            <option value="{{ Account::CLEANER }}">
                                                {{ Account::CLEANER }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.NAME') }}</label>
                                        <input name="name" class="form-control" placeholder="{{ __('lang.NAME') }}"
                                            value="{{ old('name') }}" required>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 d-none" id="accountBalance">
                                    <div class="form-group">
                                        <label>{{ __('lang.ACCOUNT_BALANCE') }}</label>
                                        <input name="balance" class="form-control" placeholder="Enter Balance">
                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-6 userAccount">
                                    <div class="form-group">
                                        <label>{{ __('lang.FATHER_NAME') }}</label>
                                        <input name="father_name" class="form-control" placeholder="Enter Father Name"
                                            value="{{ old('father_name') }}">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 userAccount">
                                    <div class="form-group">
                                        <label>{{ __('lang.EMAIL') }}</label>
                                        <input type="email" name="email" class="form-control"
                                            placeholder="Enter Email" value="{{ old('email') }}">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 userAccount">
                                    <div class="form-group">
                                        <label class="required">{{ __('lang.CONTACT') }}</label>
                                        <input type="text" name="contact"
                                            class="form-control @error('contact') is-invalid @enderror" maxlength="20"
                                            required placeholder="Cell No *Exp(xxxx-xxxxxxx)"
                                            value="{{ old('contact') }}" oninput="mobileFormat(this)">
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
                                <div class="col-sm-12 col-md-6 d-none employeeFields">
                                    <div class="form-group">
                                        <label>{{ __('lang.WAGE_TYPE') }}</label>
                                        <select class="form-control select2" name="wage_type" id="wageType"
                                            style="width: 100%;">
                                            <option value="" selected="selected" disabled="disabled">
                                                Select
                                            </option>
                                            <option value="{{ Account::HOURLY }}">{{ Account::HOURLY }}
                                            </option>
                                            <option value="{{ Account::DAILY }}">{{ Account::DAILY }}
                                            </option>
                                            <option value="{{ Account::WEEKLY }}">{{ Account::WEEKLY }}
                                            </option>
                                            <option value="{{ Account::MONTHLY }}">{{ Account::MONTHLY }}
                                            </option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 d-none employeeFields">
                                    <div class="form-group">
                                        <label>{{ __('lang.WAGE') }}</label>
                                        <input type="number" id="wage" name="wage" class="form-control"
                                            min="1" placeholder="Enter Wage">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 userAccount">
                                    <div class="form-group">
                                        <label>{{ __('lang.ADDRESS') }}</label>
                                        <input name="address" class="form-control" placeholder="Enter Address"
                                            value="{{ old('address') }}">
                                    </div>
                                </div>
                                <div class="col-sm-12 d-none customerAccount">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="required">{{ __('lang.CNIC_FRONT') }}</label>
                                                <div class="input-group mb-3">
                                                    <div class="custom-file">
                                                        <input type="file"
                                                            class="custom-file-input file-input-preview"
                                                            name="cnic_front" id="cnic_front">
                                                        <label class="custom-file-label"
                                                            for="inputGroupFile02">{{ __('lang.CNIC_FRONT') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="required">{{ __('lang.CNIC_BACK') }}</label>
                                                <div class="input-group mb-3">
                                                    <div class="custom-file">
                                                        <input type="file"
                                                            class="custom-file-input file-input-preview"
                                                            name="cnic_back" id="cnic_back">
                                                        <label class="custom-file-label"
                                                            for="inputGroupFile02">{{ __('lang.CNIC_BACK') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-6 d-none customerAccount">
                                    <label>{{ __('lang.IMAGE') }}</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input file-input-preview"
                                                name="image" id="image" accept="image/*">
                                            <label class="custom-file-label"
                                                for="inputGroupFile02">{{ __('lang.IMAGE') }} (JPG, JPEG, PNG)</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 d-none customerAccount">
                                    <label>{{ __('lang.DOCUMENT') }}</label>
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input file-input-preview"
                                                name="document" id="document" accept="application/pdf">
                                            <label class="custom-file-label"
                                                for="inputGroupFile02">{{ __('lang.DOCUMENT') }} (PDF)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="m-5 customerAccount">
                            <div class="row mt-3 d-none" id="guarantor">
                                <div class="col-md-6 guarantor-box" id="guarantor1">
                                    <h4>{{ __('lang.GUARANTOR_1_INFORMATION') }}</h4>
                                    <div class="form-group">
                                        <label>{{ __('lang.NAME') }}</label>
                                        <input type="text" name="guarantors[0][name]" class="form-control"
                                            placeholder="{{ __('lang.NAME') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('lang.FATHER_NAME') }}</label>
                                        <input type="text" name="guarantors[0][father_name]" class="form-control"
                                            placeholder="Enter Father Name">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('lang.ADDRESS') }}</label>
                                        <input type="text" name="guarantors[0][address]" class="form-control"
                                            placeholder="Enter Address">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('lang.CONTACT') }}</label>
                                        <input type="text" name="guarantors[0][phone]" maxlength="12"
                                            class="form-control" placeholder="Cell No *Exp(xxxx-xxxxxxx)"
                                            oninput="mobileFormat(this)">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('lang.CNIC') }}</label>
                                        <input type="text" name="guarantors[0][cnic]" class="form-control"
                                            placeholder="CNIC No *Exp(xxxxx-xxxxxxx-x)" oninput="cnicFormat(this)">
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label
                                                class="form-label fw-bold d-block">{{ __('lang.CNIC_FRONT') }}</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input file-input-preview"
                                                        name="guarantors[0][cnic_front]" accept="image/*">
                                                    <label class="custom-file-label"
                                                        for="cnic_front">{{ __('lang.CNIC_FRONT') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label
                                                class="form-label fw-bold d-block">{{ __('lang.CNIC_BACK') }}</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input file-input-preview"
                                                        name="guarantors[0][cnic_back]" accept="image/*">
                                                    <label class="custom-file-label"
                                                        for="cnic_back">{{ __('lang.CNIC_BACK') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 guarantor-box" id="guarantor2">
                                    <h4>{{ __('lang.GUARANTOR_2_INFORMATION') }}</h4>
                                    <div class="form-group">
                                        <label>{{ __('lang.NAME') }}</label>
                                        <input type="text" name="guarantors[1][name]" class="form-control"
                                            placeholder="{{ __('lang.NAME') }}">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('lang.FATHER_NAME') }}</label>
                                        <input type="text" name="guarantors[1][father_name]" class="form-control"
                                            placeholder="Enter Father Name">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('lang.ADDRESS') }}</label>
                                        <input type="text" name="guarantors[1][address]" class="form-control"
                                            placeholder="Enter Address">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('lang.CONTACT') }}</label>
                                        <input type="text" name="guarantors[1][phone]"
                                            oninput="mobileFormat(this)" maxlength="12" class="form-control"
                                            placeholder="Cell No *Exp(xxxx-xxxxxxx)">
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('lang.CNIC') }}</label>
                                        <input type="text" name="guarantors[1][cnic]" class="form-control"
                                            placeholder="CNIC No *Exp(xxxxx-xxxxxxx-x)" oninput="cnicFormat(this)">
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label
                                                class="form-label fw-bold d-block">{{ __('lang.CNIC_FRONT') }}</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input file-input-preview"
                                                        name="guarantors[1][cnic_front]" accept="image/*">
                                                    <label class="custom-file-label"
                                                        for="cnic_front">{{ __('lang.CNIC_FRONT') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label
                                                class="form-label fw-bold d-block">{{ __('lang.CNIC_FRONT') }}</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="custom-file-input file-input-preview"
                                                        name="guarantors[1][cnic_back]" accept="image/*">
                                                    <label class="custom-file-label"
                                                        for="cnic_front">{{ __('lang.CNIC_FRONT') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center" style="margin-top: 1em;">
                                <div class="col-auto">
                                    <button type="reset" class="btn btn-secondary">Cancel</button>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-success" id="saveAccountBtn">Save</button>
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
                    <h3 class="card-title">Available Accounts</h3>
                </div>
                <div class="card-body">
                    <table id="accountsTable" class="table table-bordered table-striped display">
                        <thead>
                            <tr>
                                <th>{{ __('lang.SR') }}</th>
                                <th>{{ __('lang.ACCOUNT_TYPE') }}</th>
                                <th>{{ __('lang.ACCOUNT_NO') }}</th>
                                <th>{{ __('lang.OLD_ACCOUNT_NO') }}</th>
                                <th>{{ __('lang.NAME') }}</th>
                                <th>{{ __('lang.SURNAME') }}</th>
                                <th>{{ __('lang.CNIC') }}</th>
                                <th>{{ __('lang.CONTACT') }}</th>
                                <th>{{ __('lang.EMAIL') }}</th>
                                <th>{{ __('lang.ADDRESS') }}</th>
                                <th>{{ __('lang.DESIGNATION') }}</th>
                                <th>{{ __('lang.BALANCE') }}</th>
                                <th>{{ __('lang.WAGE') }}</th>
                                <th>{{ __('lang.WAGE_TYPE') }}</th>
                                <th class="noExport">{{ __('lang.ACTION') }}</th>
                            </tr>

                        </thead>
                        <tbody>
                            @foreach ($accounts as $account)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td
                                        @if ($account->type !== Account::INVESTOR) class="update" data-name="account_type" data-type="select"
                                            data-pk="{{ $account->id }}" data-title="Enter account type" @endif>
                                        {{ $account->type }}
                                    </td>
                                    @if ($account->type == Account::CUSTOMER)
                                        <td>
                                            {{ $account->cus_account_id }}
                                        </td>
                                    @else
                                        <td>
                                            Nill
                                        </td>
                                    @endif

                                    <td class="update" data-name="name" data-type="text"
                                        data-pk="{{ $account->id }}" data-title="Enter name">
                                        {{ $account->name != 0 ? $account->name : 'Nill' }}
                                    </td>
                                    <td class="update" data-name="father_name" data-type="text"
                                        data-pk="{{ $account->id }}" data-title="Enter father name">
                                        {{ $account->father_name != 0 ? $account->father_name : 'Nill' }}
                                    </td>

                                    <td class="update" data-name="cnic" data-type="text"
                                        data-pk="{{ $account->id }}" data-title="Enter cnic">
                                        {{ $account->cnic != 0 ? $account->cnic : 'Nill' }}
                                    </td>
                                    <td class="update" data-name="contact" data-type="text"
                                        data-pk="{{ $account->id }}" data-title="Enter contact">
                                        {{ $account->contact }}
                                    </td>
                                    <td class="update" data-name="email" data-type="text"
                                        data-pk="{{ $account->id }}" data-title="Enter email">
                                        {{ $account->email != 0 ? $account->email : 'Nill' }}
                                    </td>
                                    <td class="update" data-name="address" data-type="text"
                                        data-pk="{{ $account->id }}" data-title="Enter address">
                                        {{ $account->address != 0 ? $account->address : 'Nill' }}
                                    </td>
                                    <td
                                        @if ($account->type == Account::EMPLOYEE) class="update" data-name="designation" data-type="select"
                                        data-pk="{{ $account->id }}" data-title="Enter Designation" @endif>
                                        {{ $account->designation ?? 'Nill' }}
                                    </td>
                                    <td class="update" data-name="account_no" data-type="text"
                                        data-pk="{{ $account->id }}" data-title="Enter Account No">
                                        {{ $account->account_no }}
                                    </td>

                                    <td>
                                        {{ number_format($account->balance) }}
                                    </td>
                                    <td
                                        @if ($account->type == Account::EMPLOYEE) class="update" data-name="wage" data-type="text"
                                        data-pk="{{ $account->id }}" data-title="Enter wage" @endif>
                                        {{ $account->wage != 0 ? $account->wage : 'Nill' }}
                                    </td>
                                    <td
                                        @if ($account->type == Account::EMPLOYEE) class="update" data-name="wage_type" data-type="select"
                                        data-pk="{{ $account->id }}" data-title="Enter wage type" @endif>
                                        {{ $account->wage_type != 0 ? $account->wage_type : 'Nill' }}
                                    </td>
                                    <td>
                                        <a href="accountDelete/{{ $account->id }}" class="btn btn-danger mb-2"
                                            onclick="return confirm('Are you sure you want to delete this account?');">
                                            Delete
                                        </a>
                                        @if ($account->type == Account::CUSTOMER)
                                            <a href="blacklistManagement/{{ $account->id }}"
                                                class="btn btn-warning"
                                                onclick="return confirm('Are you sure you want to Block this customer?');">
                                                {{ __('lang.BLOCK') }}
                                            </a>

                                            <a href="javascript:void(0);" data-account-id="{{ $account->id }}"
                                                class="btn btn-warning mt-2 guarantorBtn">
                                                {{ __('lang.GUARANTOR') }}
                                            </a>

                                            <a href="javascript:void(0);" data-account-id="{{ $account->id }}"
                                                class="btn btn-info mt-2 viewDocumentsBtn">
                                                View Doc
                                            </a>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <div id="guarantorModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('lang.GUARANTOR_DETAILS') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mt-3" id="guarantor">
                            <div class="col-md-6 guarantor-box" id="guarantor1">
                                <h4>{{ __('lang.GUARANTOR_1_INFORMATION') }}</h4>
                                <input type="text" name="guarantors[0][id]" id="guarantors[0][id]"
                                    class="d-none">
                                <div class="form-group">
                                    <label>{{ __('lang.NAME') }}</label>
                                    <input type="text" name="guarantors[0][name]" class="form-control"
                                        placeholder="{{ __('lang.NAME') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('lang.FATHER_NAME') }}</label>
                                    <input type="text" name="guarantors[0][father_name]" class="form-control"
                                        placeholder="Enter Father Name">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('lang.ADDRESS') }}</label>
                                    <input type="text" name="guarantors[0][address]" class="form-control"
                                        placeholder="Enter Address">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('lang.CONTACT') }}</label>
                                    <input type="text" name="guarantors[0][phone]" maxlength="12"
                                        class="form-control" placeholder="Cell No *Exp(xxxx-xxxxxxx)">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('lang.CNIC') }}</label>
                                    <input type="text" name="guarantors[0][cnic]" class="form-control"
                                        placeholder="CNIC No *Exp(xxxxx-xxxxxxx-x)" oninput="cnicFormat(this)">
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label fw-bold d-block">{{ __('lang.CNIC_FRONT') }}</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input"
                                                    name="guarantors[0][cnic_front]" accept="image/*">
                                                <label class="custom-file-label">{{ __('lang.CNIC_FRONT') }}</label>
                                            </div>
                                        </div>
                                        <img class="img-preview mt-2 img-thumbnail"
                                            style="max-height: 120px; display: none;" alt="CNIC Front">
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label fw-bold d-block">{{ __('lang.CNIC_BACK') }}</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input"
                                                    name="guarantors[0][cnic_back]" accept="image/*">
                                                <label class="custom-file-label">{{ __('lang.CNIC_BACK') }}</label>
                                            </div>
                                        </div>
                                        <img class="img-preview mt-2 img-thumbnail"
                                            style="max-height: 120px; display: none;" alt="CNIC Back">
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-6 guarantor-box" id="guarantor2">
                                <h4>{{ __('lang.GUARANTOR_2_INFORMATION') }}</h4>
                                <input type="text" name="guarantors[1][id]" id="guarantors[1][id]"
                                    class="d-none">
                                <div class="form-group">
                                    <label>{{ __('lang.NAME') }}</label>
                                    <input type="text" name="guarantors[1][name]" class="form-control"
                                        placeholder="{{ __('lang.NAME') }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('lang.FATHER_NAME') }}</label>
                                    <input type="text" name="guarantors[1][father_name]" class="form-control"
                                        placeholder="Enter Father Name">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('lang.ADDRESS') }}</label>
                                    <input type="text" name="guarantors[1][address]" class="form-control"
                                        placeholder="Enter Address">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('lang.CONTACT') }}</label>
                                    <input type="text" name="guarantors[1][phone]" maxlength="12"
                                        class="form-control" placeholder="Cell No *Exp(xxxx-xxxxxxx)"
                                        oninput="mobileFormat(this)">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('lang.CNIC') }}</label>
                                    <input type="text" name="guarantors[1][cnic]" class="form-control"
                                        placeholder="CNIC No *Exp(xxxxx-xxxxxxx-x)" oninput="cnicFormat(this)">
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label fw-bold d-block">{{ __('lang.CNIC_FRONT') }}</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input"
                                                    name="guarantors[1][cnic_front]" accept="image/*">
                                                <label class="custom-file-label"
                                                    for="cnic_front">{{ __('lang.CNIC_FRONT') }}</label>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-center">
                                            <img class="img-preview img-thumbnail" src=""
                                                alt="CNIC Front Preview" style="max-height: 120px; display: none;">
                                        </div>
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label class="form-label fw-bold d-block">{{ __('lang.CNIC_BACK') }}</label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input"
                                                    name="guarantors[1][cnic_back]" accept="image/*">
                                                <label class="custom-file-label"
                                                    for="cnic_back">{{ __('lang.CNIC_BACK') }}</label>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-center">
                                            <img class="img-preview img-thumbnail" src=""
                                                alt="CNIC Back Preview" style="max-height: 120px; display: none;">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success" onclick="updateGuarantor()">
                            Update Guarantor
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Modal -->
        <div id="documentsModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Customer Documents</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="updateDocumentsForm" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="updateCustomerId" name="customer_id">
                            <div id="documentsContent">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" onclick="updateCustomerDocuments()">Update
                            Documents</button>
                    </div>
                </div>
            </div>
        </div>

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
    <!-- bs-custom-file-input -->
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/vfs_fonts.js') }}"></script>
    <script>
        const invester = "{{ Account::INVESTOR }}"
        const expense = "{{ Account::EXPENSE }}"
        const employee = "{{ Account::EMPLOYEE }}"
        const customer = "{{ Account::CUSTOMER }}"
        const hourly = "{{ Account::HOURLY }}";
        const daily = "{{ Account::DAILY }}";
        const weekly = "{{ Account::WEEKLY }}";
        const monthly = "{{ Account::MONTHLY }}";
        const cleaner = "{{ Account::CLEANER }}";
        const administrator = "{{ Account::ADMINISTRATOR }}";
        const managerdirector = "{{ Account::MANAGING_DIRECTOR }}";
        var accountType = @json(Account::types());

        // Document Type Constants
        const DOCUMENT_TYPES = {
            CNIC_FRONT: "{{ Account::File_CNIC_FRONT }}",
            CNIC_BACK: "{{ Account::File_CNIC_BACK }}",
            IMAGE: "{{ Account::File_IMAGE }}",
            DOCUMENT: "{{ Account::File_DOCUMENT }}"
        };

        // Document Display Names
        const DOCUMENT_DISPLAY_NAMES = {
            "{{ Account::File_CNIC_FRONT }}": "{{ __('lang.CNIC_FRONT') }}",
            "{{ Account::File_CNIC_BACK }}": "{{ __('lang.CNIC_BACK') }}",
            "{{ Account::File_IMAGE }}": "{{ __('lang.IMAGE') }}",
            "{{ Account::File_DOCUMENT }}": "{{ __('lang.DOCUMENT') }}"
        };

        // File Accept Types
        const FILE_ACCEPT_TYPES = {
            "{{ Account::File_DOCUMENT }}": "application/pdf",
            "{{ Account::File_IMAGE }}": "image/*",
            "{{ Account::File_CNIC_FRONT }}": "image/*",
            "{{ Account::File_CNIC_BACK }}": "image/*"
        };
    </script>
    <script src="{{ asset('plugins/scripts/account.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>

    @include('components.notification')
</body>
