@include('components.header')
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

<body class="hold-transition sidebar-mini">
    @include('components.navbar')
    @include('components.sidebar')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Cache Management Page</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Cache Management Page</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="buttons-container">
                <div class="row" style="margin-left: 5em;">
                    <form method="post" action="{{ route('cache.clear') }}">
                        @csrf
                        <div class="row" style="margin-left: 1em;">
                            <button type="submit" class="btn btn-success">Clear Cache</button>
                        </div>
                    </form>
                    <form method="post" action="{{ route('cache.clear.view') }}">
                        @csrf
                        <div class="row" style="margin-left: 1em;">
                            <button type="submit" class="btn btn-success">Clear View Cache</button>
                        </div>
                    </form>
                    <form method="post" action="{{ route('cache.clear.config') }}">
                        @csrf
                        <div class="row" style="margin-left: 1em;">
                            <button type="submit" class="btn btn-success">Clear Config Cache</button>
                        </div>
                    </form>
                    <form method="post" action="{{ route('cache.dump.autoload') }}">
                        @csrf
                        <div class="row" style="margin-left: 1em;">
                            <button type="submit" class="btn btn-success">Dump Autoload</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    @include('components.footer')
    <aside class="control-sidebar control-sidebar-dark">
    </aside>
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

    @include('components.notification')
</body>
