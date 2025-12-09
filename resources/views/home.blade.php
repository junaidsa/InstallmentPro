@php
    $user = Session::get('user');
@endphp
@include('components.header')
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
<style>
    .card-custom {
        border: none;
        border-radius: 15px;
        color: #fff;
        margin-bottom: 10px;
        transition: transform 0.2s;
        text-decoration: none;
    }

    .card-custom:hover {
        transform: scale(1.05);
    }

    .card-title-custom {
        font-size: 2.5rem;
        padding: 15px;
        margin: 0;
    }

    .card-body-custom {
        padding: 10px;
        font-size: 1.5rem;
    }

    .list-group-item-custom {
        background-color: transparent;
        color: #fff;
        cursor: pointer;
    }

    .custom-button {
        padding: 10px;
    }

    .time-clock {
        padding: 10px;
    }
</style>

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
                        <h1 class="m-0 text-dark">Dashboard</h1><br>
                        <center>
                            <h1><i><b>Welcome To {{ $user->groups->name }}</b></i></h1>
                        </center>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="card" style="margin-top: 2em; margin-left: 1em; margin-right: 1em;">
                <div class="card-header">
                    <h4 class="m-0 text-dark">Employee Attendance</h4>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                class="fas fa-minus"></i>
                        </button>
                    </div>
                    <br>
                    @php
                        $currentTime = now()->format('d M, Y - h:i A');
                    @endphp
                    <h4 class="time-clock">Time Clock <br>
                        <hr>
                        {{ $currentTime }}
                    </h4>
                    <br>
                    @if ($checkinStatus == 0)
                        <form action="{{ route('dashboard.store') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success custom-button">Check In</button>
                        </form>
                    @elseif ($checkinStatus == 1)
                        <form action="{{ route('dashboard.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger custom-button">Check Out</button>
                        </form>
                    @endif
                </div>
                <div class="card-body">
                    <table id="employeeAttendanceTable" class="table table-bordered table-striped display">
                        <thead>
                            <tr>
                                <th>{{ __('lang.CHECKIN_TIME') }}</th>
                                <th>{{ __('lang.TOTAL_WORKING_TIME_FOR_THE_DAY') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($employeeAttendance)
                                <tr>
                                    <td>
                                        {{ $employeeAttendance->checkin_time }}
                                    </td>
                                    <td>
                                        @if ($employeeAttendance->checkout_time)
                                            {{ date('H:i:s', $employeeAttendance->checkin_time->diffInSeconds($employeeAttendance->checkout_time)) }}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
        <!-- Main content -->
        <div class="container mt-5">
            <div class="row">
                @php
                    $colors = ['#17A2B8', '#28A745', '#FFA500', '#DC3545', '#6495ED'];
                    $colorIndex = 0;
                @endphp

                @foreach ($user->parentScreens as $parentScreen)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card card-custom"
                            style="background-color: {{ $colors[$colorIndex % count($colors)] }};">
                            <div class="card-title-custom">
                                {{ $parentScreen->screen_name }}
                            </div>
                            <div class="card-body card-body-custom">
                                <ul class="list-group list-group-flush">
                                    @foreach ($user->childScreens[$parentScreen->id] ?? [] as $childScreen)
                                        <a href="{{ $childScreen->directory }}"
                                            class="list-group-item list-group-item-custom">
                                            {{ $childScreen->screen_name }}
                                        </a>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    @php $colorIndex++; @endphp
                @endforeach
            </div>

        </div>
    </div>
    <!-- /.content-wrapper -->

    <!-- Include footer -->
    @include('components.footer')

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside><!-- /.control-sidebar -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

    <!-- Include notification component -->
    @include('components.notification')
</body>

</html>
