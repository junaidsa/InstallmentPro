@include('components.header')
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

<body class="hold-transition sidebar-mini layout-fixed">
    <!-- Content Wrapper. Contains page content -->
    <div class="wrapper">
        <!-- Header with Logo -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-4">
                    <div class="col-md-12 text-center">
                        <a href="{{ route('dashboard') }}"
                            class="d-inline-flex align-items-center text-decoration-none">
                            <img src="{{ asset('dist/img/logo.png') }}" alt="Kinder Byte Logo" class="rounded-circle"
                                style="height:60px; width:60px; object-fit:cover;">
                            <span class="ml-2 font-weight-bold text-dark" style="font-size:1.5rem;">
                                Kinder Byte
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Expired Link Content -->
        <section class="content">
            <div class="text-center py-5">
                <!-- Expired Icon -->
                <div class="mb-3">
                    <i class="fas fa-exclamation-triangle text-danger" style="font-size:4rem;"></i>
                </div>

                <h3 class="text-danger font-weight-bold">
                    Admission form already submitted.
                </h3>

                <p class="mt-3 text-muted" style="font-size:1.1rem;">
                    The admission form has already been submitted.
                    Please contact our support team for any queries related the admission form.
                </p>
            </div>
        </section>

    </div>

    <!-- /.content-wrapper -->
    @include('components.footer')

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

    @include('components.notification')
</body>
