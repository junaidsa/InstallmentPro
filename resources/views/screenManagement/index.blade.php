@include('components.header')

@php
    use App\Models\SoftwareScreen;
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
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('lang.ADD_SOFTWARE_SCREEN') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">Home</a></li>
                            <li class="breadcrumb-item active">Add Software Screen</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Software Screen</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <form id="screenNameForm" action="{{ route('screenManagement.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label class="required">{{ __('lang.SCREEN_NAME') }}</label>
                                            <input type="text" id="screen_name" name="screen_name"
                                                class="form-control" required placeholder="Enter Screen Name">
                                            <span id="screenMessage"></span>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('lang.PARENT') }}</label>
                                            <select class="form-control select2" name="parent_id" style="width: 100%;">
                                                <option selected="selected" disabled="disabled">Select</option>
                                                @foreach ($softwareScreens->where('is_parent', SoftwareScreen::PARENT) as $softwareScreen)
                                                    <option value="{{ $softwareScreen->id }}">
                                                        {{ $softwareScreen->screen_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label class="required">{{ __('lang.DIRECTORY') }}</label>
                                            <input type="text" id="directory" name="directory" class="form-control"
                                                placeholder="Enter Directory" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-check">
                                            <input type="hidden" name="is_parent" value="0">
                                            <input type="checkbox" class="form-check-input" id="is_parent"
                                                name="is_parent" value="1">
                                            <label class="form-check-label">{{ __('lang.IS_PARENT') }}</label>
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
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Software TAB</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <form id="tabNameForm" action="{{ route('tabManagement.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label class="required">{{ __('lang.TAB_NAME') }}</label>
                                            <input type="text" id="name" name="name" class="form-control"
                                                required placeholder="Enter Tab Name">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <label class="required">{{ __('lang.SOFTWARE_SCREEN') }}</label>
                                            <select class="form-control select2" name="screen_id" required
                                                style="width: 100%;">
                                                <option selected="selected" disabled="disabled">Select</option>
                                                @foreach ($softwareScreens as $softwareScreen)
                                                    <option value="{{ $softwareScreen->id }}">
                                                        {{ $softwareScreen->screen_name }}</option>
                                                @endforeach
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
                        </form>
                    </div>
                </div>
            </div>

        </section>
        <section class="content">
            <div class="card" style="margin-top: 2em; margin-left: 1em; margin-right: 1em;">
                <div class="card-header">
                    <h3 class="card-title">Available Screens</h3>
                </div>
                <div class="card-body">
                    <table id="screenTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('lang.SR') }}</th>
                                <th>{{ __('lang.SCREEN_NAME') }}</th>
                                <th>{{ __('lang.PARENT_ID') }}</th>
                                <th>{{ __('lang.DIRECTORY') }}</th>
                                <th>{{ __('lang.ACTION') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($softwareScreens as $softwareScreen)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td class="update" data-name="screen_name" data-type="text"
                                        data-pk="{{ $softwareScreen->id }}" data-title="Enter Screen name">
                                        {{ $softwareScreen->screen_name }}
                                    </td>
                                    <td class="update" data-name="parent_id" data-type="text"
                                        data-pk="{{ $softwareScreen->id }}" data-title="Enter Screen name">
                                        {{ $softwareScreen->parent_id }}
                                    </td>
                                    <td class="update" data-name="directory" data-type="text"
                                        data-pk="{{ $softwareScreen->id }}" data-title="Enter Screen name">
                                        {{ $softwareScreen->directory }}
                                    </td>
                                    <td>
                                        <a href="softDelete/{{ $softwareScreen->id }}"><button
                                                class="btn btn-danger btn-delete"
                                                onclick=" return confirm('Are you sure?')">Delete</button></a>
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
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-editable/jquery-editable-poshytip.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

    <!-- page script -->

    <script type="text/javascript">
        $.fn.editable.defaults.mode = 'inline';

        $.ajaxSetup({
            headers: {

                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        $('.update').editable({
            url: "/screenManagement/1",
            method: 'POST',
            params: {
                '_method': 'POST',
                '_token': '{{ csrf_token() }}'
            },

            success: function(response) {
                toastr.option = {
                    "closeButton": true,
                    "progressBar": true
                }
                toastr.success(response.name + ' to ' + response.value + ' Updated successfully.', 'Success!');
            },

            pk: 1,
            name: 'name',
            title: 'Enter name'
        });

        $('.select2').select2();
        $(document).ready(function() {
            $("#screenTable").DataTable({
                "responsive": false,
                "autoWidth": false,
                scrollX: true,
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#screenNameForm').submit(function(e) {
                e.preventDefault();
                const name = $('#screen_name').val();

                const regex = /^[A-Za-z\s'\-’]+$/;
                if (!regex.test(name)) {
                    $('#screenMessage').html(
                        "<span class='text-danger'>'The Screen Name should not contain digits and only allow spaces as special characters.'</span>"
                    );
                } else {
                    //Submit Form if validation is pass.
                    this.submit();
                }
            });
        });
    </script>


    @include('components.notification')
</body>
