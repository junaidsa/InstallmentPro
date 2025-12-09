@include('components.header')

<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<!-- Font Awesome -->
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
<link rel="stylesheet" href="{{ asset('dist/css/bootstrap-datepicker.min.css') }}">
<style>
    .circle-icon {
        display: inline-block;
        background-color: #007bff;
        color: white;
        width: 25px;
        height: 25px;
        border-radius: 50%;
        text-align: center;
        line-height: 18px;
        border: 3px solid white;
        padding: 0;
        box-shadow: 0 0 0 1px black;
    }

    .toggle-icon {
        position: relative;
        top: 0;
        right: 4%;
        padding: 0;
        border: none;
        background: none;
    }
</style>

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
                        <h1>{{ __('lang.ADD_ROLE') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Add Role</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="col-12 col-sm-12">
                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-two-role-tab" data-toggle="pill"
                                    href="#custom-tabs-two-role" role="tab" aria-controls="custom-tabs-two-role"
                                    aria-selected="true">Add Role</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-two-assignScreen-tab" data-toggle="pill"
                                    href="#custom-tabs-two-assignScreen" role="tab"
                                    aria-controls="custom-tabs-two-assignScreen" aria-selected="false">
                                    Assign Screen</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-two-assignTab-tab" data-toggle="pill"
                                    href="#custom-tabs-two-assignTab" role="tab"
                                    aria-controls="custom-tabs-two-assignTab" aria-selected="false">Assign Tab</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="custom-tabs-two-tabContent">
                        <div class="tab-pane fade show active" id="custom-tabs-two-role" role="tabpanel"
                            aria-labelledby="custom-tabs-two-role-tab">
                            <div class="card card-primary">
                                <div class="card-body">
                                    <form id = "roleNameForm" action="{{ route('roleManagement.store') }}"
                                        method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="form-group">
                                                    <label class="required">{{ __('lang.ROLE_NAME') }}</label>
                                                    <input type="text" id="name" name="name"
                                                        class="form-control" placeholder="Enter Role Name" required>
                                                    <span id = "roleMessage"></span>
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
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                            </form>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-two-assignScreen" role="tabpanel"
                            aria-labelledby="custom-tabs-two-assignScreen-tab">
                            <div class="card card-secondary">
                                <div class="card-body">
                                    <form action="{{ route('roleManagement.assign') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label class="required" for="id">{{ __('lang.ROLES') }}:</label>
                                                    <select class="form-control select2" name="role_id"
                                                        id="id" style="width: 100%;" required>
                                                        <option value = "" selected="selected"
                                                            disabled="disabled">
                                                            Select
                                                        </option>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}">{{ $role->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label class="required">{{ __('lang.SCREENS') }}:</label>
                                                    <select class="form-control select2" name="screen_id"
                                                        style="width: 100%;" required>
                                                        <option value = "" selected="selected"
                                                            disabled="disabled">
                                                            Select
                                                        </option>
                                                        @foreach ($screens as $screen)
                                                            <option value="{{ $screen->id }}">
                                                                {{ $screen->screen_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label>{{ __('lang.EXPIRY_DATE') }}</label>
                                                    <input type="text" id="expiryDate" name="expiry_date"
                                                        class="form-control datepicker">
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6" style="margin-top: 2em">
                                                <div class="form-check">
                                                    <input type="hidden" name="can_write" value="0">
                                                    <input type="checkbox" class="form-check-input" id="can_write"
                                                        name="can_write" value="1">
                                                    <label>{{ __('lang.CAN_WRITE') }}</label>
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
                                </div>
                                <!-- /.card-body -->
                                <!-- /.card -->
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="custom-tabs-two-assignTab" role="tabpanel"
                            aria-labelledby="custom-tabs-two-assignTab-tab">
                            <div class="card card-secondary">
                                <div class="card-body">
                                    <form action="{{ route('screenTabs.assign') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label class="required" for="id">{{ __('lang.USER') }}:</label>
                                                    <select class="form-control select2" name="user_id"
                                                        id="userId" style="width: 100%;" required>
                                                        <option value = "" selected="selected"
                                                            disabled="disabled">
                                                            Select
                                                        </option>
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->id }}">
                                                                {{ $user->account?->name . ' ' . $user->account?->father_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label class="required">{{ __('lang.SCREENS') }}:</label>
                                                    <select class="form-control select2" name="screen_id"
                                                        style="width: 100%;" required id="screenId">
                                                        <option value = "" selected="selected"
                                                            disabled="disabled">
                                                            Select
                                                        </option>
                                                        @foreach ($screens as $screen)
                                                            <option data-screen="{{ $screen }}"
                                                                value="{{ $screen->id }}">
                                                                {{ $screen->screen_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label class="required">{{ __('lang.TAB') }}</label>
                                                    <select class="form-control select2" id="tabId"
                                                        name="tab_id[]" style="width: 100%;" required multiple>
                                                        <option value = "" disabled="disabled">
                                                            Select
                                                        </option>

                                                    </select>
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
                                </div>
                                <!-- /.card-body -->
                                <!-- /.card -->
                                </form>
                            </div>

                            <div class="card-body">
                                <table id="userTabsTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('lang.SR') }}</th>
                                            <th>{{ __('lang.USER') }}</th>
                                            <th>{{ __('lang.TAB') }}</th>
                                            <th>{{ __('lang.STATUS') }}</th>
                                            <th>{{ __('lang.ACTION') }}</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($userTabs as $index => $tab)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    {{ $tab->user?->account->name }}
                                                </td>

                                                <td>
                                                    {{ $tab->screenTab->name }}
                                                </td>

                                                <td>
                                                    {{ $tab->status }}
                                                </td>

                                                <td>
                                                    @if ($user->hasRole(Config('titleConstants.ADMIN')))
                                                        <a href="screenTab/{{ $tab->id }}">
                                                            <button class="btn btn-danger"
                                                                onclick="return confirm('Are you sure?')">Unassign
                                                                Tab</button>
                                                        </a>
                                                    @endif
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="card" style="margin-top: 2em; margin-left: 1em; margin-right: 1em;">
                <div class="card-header">
                    <h3 class="card-title">Available Roles</h3>
                </div>
                <div class="card-body">
                    <table id="userTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('lang.SR') }}</th>
                                <th>{{ __('lang.ROLE_NAME') }}</th>
                                <th>{{ __('lang.ACTION') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td>
                                        <butoon class="toggle-icon" data-toggle="collapse"
                                            data-target="#screens_{{ $role->id }}" aria-expanded="false"
                                            aria-controls="screens_{{ $role->id }}">
                                            <span class="circle-icon">+</span>
                                            </button> &nbsp;
                                            {{ ++$i }}
                                    </td>

                                    <td>

                                        {{ $role->name }}

                                    </td>
                                    <td>
                                        @if ($user->hasRole(Config('titleConstants.ADMIN')))
                                            <button data-id="{{ $role->id }}" id="roleDelete"
                                                class="btn btn-danger">Delete Role</button>
                                        @endif
                                    </td>
                                </tr>
                                <tr id="screens_{{ $role->id }}" class="collapse">
                                    <td colspan="3">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('lang.SCREEN_NAME') }}</th>
                                                    <th>{{ __('lang.EXPIRY_DATE') }}</th>
                                                    <th>{{ __('lang.ACTION') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($roleScreens as $roleScreen)
                                                    @if ($roleScreen->role_id == $role->id)
                                                        <tr>
                                                            <td>
                                                                @if ($roleScreen->softwareScreen)
                                                                    {{ $roleScreen->softwareScreen->screen_name }}
                                                                @endif
                                                            </td>
                                                            <td class="update" data-name="expiry_date"
                                                                data-type="text" data-pk="{{ $roleScreen->id }}"
                                                                data-title="Enter Expiry Date">
                                                                <input type="text" class="datepicker"
                                                                    value="{{ $roleScreen->expiry_date }}">
                                                            </td>
                                                            <td>
                                                                @if ($user->hasRole(Config('titleConstants.ADMIN')))
                                                                    <button id="permissionDelete"
                                                                        class="btn btn-danger"
                                                                        data-id="{{ $roleScreen->id }}">Delete
                                                                        Permission</button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        @include('components.ajaxLoader')
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
    <script src="{{ asset('plugins/bootstrap/js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('plugins/scripts/utils.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>
    <script src="{{ asset('plugins/scripts/role.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>

    @include('components.notification')

</body>
