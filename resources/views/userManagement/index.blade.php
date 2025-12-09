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
<!-- Password Strength -->
<link rel="stylesheet" href="{{ asset('plugins/passwordStrength/passwordStrength.css') }}">

<body class="hold-transition sidebar-mini layout-fixed">
    @include('components.navbar')
    @include('components.sidebar')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- Main content -->
        <section class="content-header">
            <form id = "nameForm" action="{{ route('userManagement.store') }}" method="POST">
                @csrf
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>{{ __('lang.ADD_USER') }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="../../dashboard">Home</a></li>
                                <li class="breadcrumb-item active">Add User</li>
                            </ol>
                        </div>
                    </div>
                </div>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">User</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="required">{{ __('lang.EMPLOYEE') }}</label>
                                <select class="form-control select2" name="employee_id" id="employeeId"
                                    style="width: 100%;" required>
                                    <option value="" selected="selected" disabled="disabled">Select</option>
                                    @foreach ($accounts as $account)
                                        <option data-email="{{ $account->email }}" value="{{ $account->id }}">
                                            {{ $account->name }} - {{ $account?->designation }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="required">{{ __('lang.USER_NAME') }}</label>
                                <input id="userName" name="user_name" class="form-control" placeholder="Enter Username"
                                    required>
                                <span id = "message"></span>
                                <span id = "userMessages"></span>
                            </div>
                            <div class="form-group">
                                <label class="required">{{ __('lang.EMAIL') }}</label>
                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="Enter Email" required readonly>
                            </div>
                            <div class="form-group">
                                <label class="required">{{ __('lang.PASSWORD') }}</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password" class="form-control"
                                        placeholder="Enter Password" required>
                                    <div class="input-group-append">
                                        <a class="input-group-text showPassword"><span class="fas fa-eye"></span></a>
                                    </div>
                                </div>
                                <p id="passwordPolicy"></p>
                                <div class="strength-bar">
                                    <div id="strength">
                                        <p id="strength-text"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center" style="margin-top: 1em;">
                                <div class="col-auto">
                                    <button type="reset" class="btn btn-secondary">Cancel</button>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" id="submit" class="btn btn-success">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                </form>
                <div class="col-md-6">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Roles</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fas fa-minus"></i></button>
                            </div>
                            <form action="{{ route('userManagement.storeId') }}" method="POST">
                                @csrf
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="required">{{ __('lang.USER') }}:</label>
                                <select class="form-control select2" name="user_id" id="user_id"
                                    style="width: 100%;" required>
                                    <option value="" selected="selected" disabled="disabled">Select</option>
                                    @foreach ($users as $user)
                                        @php
                                            $currentUserName = trim(str_replace(' ', '', $user?->user_name));
                                        @endphp
                                        <option value="{{ $user?->id }}">
                                            {{ $user?->account?->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="required">{{ __('lang.ROLES') }}:</label>
                                <select class="form-control select2" name="role_id[]" style="width: 100%;" multiple
                                    required>
                                    <option value = "" disabled="disabled">Select</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row justify-content-center" style="margin-top: 1em;">
                                <div class="col-auto">
                                    <button type="reset" class="btn btn-secondary">Cancel</button>
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-success">Save</button>
                                </div>
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
                    <h3 class="card-title">Available Users</h3>
                </div>
                <div class="card-body">
                    <table id="userTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('lang.SR') }}</th>
                                <th>{{ __('lang.ACCOUNT_TYPE') }}</th>
                                <th>{{ __('lang.NAME') }}</th>
                                <th>{{ __('lang.USER_NAME') }}</th>
                                <th>{{ __('lang.ROLE_NAME') }}</th>
                                <th>{{ __('lang.EMAIL') }}</th>
                                <th>{{ __('lang.ACTION') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>{{ $user->account->type ?? 'N/A' }}</td>
                                    <td>

                                        {{ $user?->account?->name ?? 'N/A' }}

                                    </td>


                                    <td class="update" data-name="user_name" id="userName" data-type="text"
                                        data-pk="{{ $user->id }}" data-title="Enter username">
                                        {{ $user?->user_name }}
                                    </td>
                                    <td>
                                        {{ ucwords($user->roles->implode('name', ',  ')) }}
                                    </td>
                                    <td class="update" data-name="email" id="userName" data-type="text"
                                        data-pk="{{ $user->id }}" data-title="Enter email">
                                        {{ $user?->email }}
                                    </td>
                                    <td>
                                        <a href="delete/{{ $user->id }}"><button
                                                class="btn btn-danger btn-delete"
                                                onclick=" return confirm('Are you sure?')">Delete</button></a>
                                        <button type="submit" id="changePasswordBtn" class="btn btn-warning"
                                            data-toggle="modal" data-target="#changePasswordModal"
                                            data-userid="{{ $user->id }}">
                                            Change Password
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <div class="modal fade" id="changePasswordModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('lang.CHANGE_PASSWORD') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="">
                            <div class="form-group">
                                <label class="required">{{ __('lang.NEW_PASSWORD') }}</label>
                                <div class="input-group">
                                    <input type="password" id="createPassword" name="password"
                                        class="newPassword form-control" placeholder="Enter New Password" required>
                                    <div class="input-group-append">
                                        <a class="input-group-text showPassword"><span class="fas fa-eye"></span></a>
                                    </div>
                                </div>
                                <p id="passwordPolicy3"></p>
                                <div class="strength-bar">
                                    <div id="strength3">
                                        <p id="strength-text3"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="required">{{ __('lang.CONFIRM_PASSWORD') }}</label>
                                <div class="input-group">
                                    <input type="password" name="confirm_password" class="form-control"
                                        id="confirmPassword" placeholder="Confirm Password" required>
                                    <div class="input-group-append">
                                        <a class="input-group-text showPassword"><span class="fas fa-eye"></span></a>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="reset" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" id="changePassword" class="submit btn btn-primary">Save
                            changes</button>
                    </div>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-editable/jquery-editable-poshytip.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('plugins/scripts/checkPasswordStrength.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>
    <script src="{{ asset('plugins/scripts/users.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>
    <script src="{{ asset('plugins/scripts/utils.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>
    <script>
        const allRoles = @json($roles);
    </script>
    @include('components.notification')
</body>
