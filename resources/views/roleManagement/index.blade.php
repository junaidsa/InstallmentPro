@include('components.header')

<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="shortcut icon" type="image/png" href="../../dist/images/logos/favicon.ico" />
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/libs/footable-v3/compiled/footable.bootstrap.min.css') }}">
<link id="themeColors" rel="stylesheet" href="{{ asset('dist/css/style.min.css') }}" />
<!-- Google Font: Source Sans Pro -->
{{-- <link rel="stylesheet" href="{{ asset('plugins/jquery-editable/jquery-editable.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist2/css/bootstrap-datepicker.min.css') }}"> --}}

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="page-wrapper" id="main-wrapper" data-theme="blue_theme" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        @include('components.sidebar')
        <div class="body-wrapper">
            @include('components.navbar')
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <!-- Main content -->

                {{-- <section class="content">
                    <div class="col-12 col-sm-12">
                        <div class="card card-primary card-tabs">
                            <div class="card-header p-0 pt-1">
                                <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-two-role-tab" data-toggle="pill"
                                            href="#custom-tabs-two-role" role="tab"
                                            aria-controls="custom-tabs-two-role" aria-selected="true">Add Role</a>
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
                                            aria-controls="custom-tabs-two-assignTab" aria-selected="false">Assign
                                            Tab</a>
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
                                                                class="form-control" placeholder="Enter Role Name"
                                                                required>
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
                                                            <label class="required"
                                                                for="id">{{ __('lang.ROLES') }}:</label>
                                                            <select class="form-control select2" name="role_id"
                                                                id="id" style="width: 100%;" required>
                                                                <option value = "" selected="selected"
                                                                    disabled="disabled">
                                                                    Select
                                                                </option>
                                                                @foreach ($roles as $role)
                                                                    <option value="{{ $role->id }}">
                                                                        {{ $role->name }}
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
                                                            <input type="checkbox" class="form-check-input"
                                                                id="can_write" name="can_write" value="1">
                                                            <label>{{ __('lang.CAN_WRITE') }}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row justify-content-center" style="margin-top: 1em;">
                                                    <div class="col-auto">
                                                        <button type="reset"
                                                            class="btn btn-secondary">Cancel</button>
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
                                                            <label class="required"
                                                                for="id">{{ __('lang.USER') }}:</label>
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
                                                                name="tab_id[]" style="width: 100%;" required
                                                                multiple>
                                                                <option value = "" disabled="disabled">
                                                                    Select
                                                                </option>

                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row justify-content-center" style="margin-top: 1em;">
                                                    <div class="col-auto">
                                                        <button type="reset"
                                                            class="btn btn-secondary">Cancel</button>
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
                </section> --}}

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- Tabs Header -->
                            <ul class="nav nav-pills mb-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#add-role" role="tab">
                                        Add Role
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#assign-screen" role="tab">
                                        Assign Screen
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#assign-tab" role="tab">
                                        Assign Tab
                                    </a>
                                </li>
                            </ul>

                            <!-- Tabs Content -->
                            <div class="tab-content border">

                                <!-- ================= ADD ROLE ================= -->
                                <div class="tab-pane fade show active p-3" id="add-role" role="tabpanel">
                                    <form id="roleNameForm" action="{{ route('roleManagement.store') }}" method="POST">
                                        @csrf

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="required">{{ __('lang.ROLE_NAME') }}</label>
                                                    <input type="text" name="name" class="form-control"
                                                        placeholder="Enter Role Name" required>
                                                    <span id="roleMessage"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center mt-3">
                                            <div class="col-auto">
                                                <button type="reset" class="btn btn-secondary">Cancel</button>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- ================= ASSIGN SCREEN ================= -->
                                <div class="tab-pane fade p-3" id="assign-screen" role="tabpanel">
                                    <form action="{{ route('roleManagement.assign') }}" method="POST">
                                        @csrf

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="required">{{ __('lang.ROLES') }}</label>
                                                <select class="form-control select2" name="role_id" required>
                                                    <option value="" disabled selected>Select</option>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}">{{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="required">{{ __('lang.SCREENS') }}</label>
                                                <select class="form-control select2" name="screen_id" required>
                                                    <option value="" disabled selected>Select</option>
                                                    @foreach ($screens as $screen)
                                                        <option value="{{ $screen->id }}">
                                                            {{ $screen->screen_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label>{{ __('lang.EXPIRY_DATE') }}</label>
                                                <input type="text" name="expiry_date"
                                                    class="form-control datepicker">
                                            </div>

                                            <div class="col-md-6 mt-4">
                                                <input type="hidden" name="can_write" value="0">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" name="can_write"
                                                        value="1">
                                                    <label class="form-check-label">
                                                        {{ __('lang.CAN_WRITE') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center mt-3">
                                            <div class="col-auto">
                                                <button type="reset" class="btn btn-secondary">Cancel</button>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- ================= ASSIGN TAB ================= -->
                                <div class="tab-pane fade p-3" id="assign-tab" role="tabpanel">
                                    <form action="{{ route('screenTabs.assign') }}" method="POST">
                                        @csrf

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="required">{{ __('lang.USER') }}</label>
                                                <select class="form-control select2" name="user_id" required>
                                                    <option value="" disabled selected>Select</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">
                                                            {{ $user->account?->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="required">{{ __('lang.SCREENS') }}</label>
                                                <select class="form-control select2" name="screen_id" required>
                                                    <option value="" disabled selected>Select</option>
                                                    @foreach ($screens as $screen)
                                                        <option value="{{ $screen->id }}">
                                                            {{ $screen->screen_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6 mt-3">
                                                <label class="required">{{ __('lang.TAB') }}</label>
                                                <select class="form-control select2" name="tab_id[]" multiple
                                                    required>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row justify-content-center mt-3">
                                            <div class="col-auto">
                                                <button type="reset" class="btn btn-secondary">Cancel</button>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit" class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>

                                    <hr>

                                    <table class="table table-bordered mt-3">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User</th>
                                                <th>Tab</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($userTabs as $i => $tab)
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td>{{ $tab->user?->account->name }}</td>
                                                    <td>{{ $tab->screenTab->name }}</td>
                                                    <td>{{ $tab->status }}</td>
                                                    <td>
                                                        <a href="screenTab/{{ $tab->id }}"
                                                            onclick="return confirm('Are you sure?')"
                                                            class="btn btn-danger btn-sm">
                                                            Unassign
                                                        </a>
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

                {{-- <section class="content">
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
                                                                        data-type="text"
                                                                        data-pk="{{ $roleScreen->id }}"
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
                </section> --}}

                <div class="table-responsive">
                    <table id="roleFooTable" class="table table-bordered footable" data-toggle-column="first">

                        <thead>
                            <tr>
                                <th data-breakpoints="xs"># ID</th>
                                <th>{{ __('lang.ROLE_NAME') }}</th>
                                <th data-breakpoints="xs sm">{{ __('lang.ACTION') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($roles as $index => $role)
                                <tr>
                                    <td>
                                        <span class="footable-toggle fooicon fooicon-plus"></span>
                                        {{ $index + 1 }}
                                    </td>

                                    <td>{{ $role->name }}</td>

                                    <td>
                                        @if ($user->hasRole(Config('titleConstants.ADMIN')))
                                            <button class="btn btn-danger btn-sm" data-id="{{ $role->id }}">
                                                Delete Role
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Hidden expandable row --}}
                                <tr class="footable-row-detail">
                                    <td colspan="3">

                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('lang.SCREEN_NAME') }}</th>
                                                    <th>{{ __('lang.EXPIRY_DATE') }}</th>
                                                    <th>{{ __('lang.ACTION') }}</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($roleScreens->where('role_id', $role->id) as $roleScreen)
                                                    <tr>
                                                        <td>
                                                            {{ $roleScreen->softwareScreen->screen_name ?? '-' }}
                                                        </td>

                                                        <td>
                                                            <input type="text" class="form-control datepicker"
                                                                value="{{ $roleScreen->expiry_date }}">
                                                        </td>

                                                        <td>
                                                            @if ($user->hasRole(Config('titleConstants.ADMIN')))
                                                                <button class="btn btn-danger btn-sm"
                                                                    data-id="{{ $roleScreen->id }}">
                                                                    Delete Permission
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @include('components.ajaxLoader')
            </div>
            @include('components.footer')
        </div>

        {{-- <aside class="control-sidebar control-sidebar-dark">

    </aside> --}}



        <!-- Select2 -->
        <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

        <!-- DataTables -->
        {{-- <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('plugins/jquery-editable/jquery-editable-poshytip.min.js') }}"></script> --}}
        <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
        {{-- <script src="{{ asset('plugins/bootstrap/js/bootstrap-datepicker.min.js') }}"></script> --}}

        {{-- <script src="{{ asset('plugins/scripts/utils.js?v=' . config('miscConstant.JS_VERSION')) }}"></script> --}}
        {{-- <script src="{{ asset('plugins/scripts/role.js?v=' . config('miscConstant.JS_VERSION')) }}"></script> --}}

        <script src="{{ asset('dist/libs/jquery/dist/jquery.min.js') }}"></script>
        <script src="{{ asset('dist/libs/simplebar/dist/simplebar.min.js') }}"></script>
        <script src="{{ asset('dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
        <!--  core files -->
        <script src="{{ asset('dist/js/app.min.js') }}"></script>
        <script src="{{ asset('dist/js/app.init.js') }}"></script>
        <script src="{{ asset('dist/js/app-style-switcher.js') }}"></script>
        <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
        <script src="{{ asset('dist/js/custom.js') }}"></script>
        {{-- <script src="{{ asset('plugins/scripts/dashboard.js?v=' . config('miscConstant.JS_VERSION')) }}"></script> --}}
        {{-- <script src="{{ asset('dist/js/apps/notes.js') }}"></script> --}}


        {{-- <script src="{{ asset('dist/libs/moment-js/build/moment.min.js') }}"></script> --}}
        <script src="{{ asset('dist/libs/footable-v3/compiled/footable.min.js') }}"></script>
        {{-- <script src="{{ asset('dist/js/plugins/footable-init.js') }}"></script> --}}

        @include('components.notification')

        <script>
            jQuery(function($) {
                $('#roleFooTable').footable();
            });
        </script>

</body>
