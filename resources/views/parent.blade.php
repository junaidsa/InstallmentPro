@include('components.header')
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
<link rel="stylesheet" href="{{ asset('plugins/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

<!-- Custom CSS -->
<style>
    .content-wrapper {
        padding-top: 20px;
    }

    .card {
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .card-header {
        background-color: #007bff;
        color: white;
        border-bottom: none;
    }

    .card-title {
        font-size: 1.3rem;
        font-weight: bold;
    }

    .table {
        border-radius: 8px;
        overflow: hidden;
        background-color: #f8f9fa;
    }

    .table thead {
        background-color: #343a40;
        color: white;
    }

    .table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .btn-info {
        background-color: #007bff;
        border-color: #007bff;
        border-radius: 50px;
        padding: 0.375rem 1.75rem;
    }

    .btn-info:hover {
        background-color: #0056b3;
        border-color: #004085;
    }

    /* Breadcrumbs */
    .breadcrumb-item a {
        color: #007bff;
    }

    .breadcrumb-item.active {
        font-weight: bold;
        color: #495057;
    }
</style>

<body class="hold-transition sidebar-mini layout-fixed">
    @include('components.navbar')
    @include('components.sidebar')

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Child Profiles</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="../../dashboard">Home</a></li>
                            <li class="breadcrumb-item active">{{ __('lang.CHILD_PROFILES') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('lang.LIST_OF_CHILDS') }}</h3>
                </div>
                <div class="card-body">
                    <table id="childProfileTable" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('lang.STUDENT_NAME') }}</th>
                                <th>{{ __('lang.ACTION') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($studentDetails->isNotEmpty())
                                @foreach ($studentDetails as $studentDetail)
                                    <tr>
                                        <td>{{ $loop->index }}</td>
                                        <td>{{ $studentDetail?->student?->first_name }}
                                            {{ $studentDetail?->student?->last_name }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('childInsight.show', ['studentId' => $studentDetail->student->id ?? 0]) }}"
                                                target="_blank">
                                                <button class="btn btn-info">
                                                    <i class="fas fa-eye"></i> {{ __('lang.VIEW_PROFILE') }}
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                        {{ __('lang.NO_STUDENT_RECORDS_FOUND') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    @include('components.footer')

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
    </aside>

    <!-- Scripts -->
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    @include('components.notification')
    <script>
        $(document).ready(function() {
            $("#childProfileTable").DataTable({
                "responsive": true,
                "autoWidth": false,
                scrollX: true,
            });
        });
    </script>
</body>
