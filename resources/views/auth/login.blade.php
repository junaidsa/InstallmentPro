<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Onlease System| Log in</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

    <link rel="shortcut icon" type="image/png" href="{{ asset('dist/images/logos/favicon.ico') }}" />
    <!-- Core Css -->
    <link id="themeColors" rel="stylesheet" href="{{ asset('dist/css/style.min.css') }}" />
</head>

<body class="hold-transition login-page">
    {{-- <img src="../dist/img/logo.png" alt="Logo" width="auto" height="300px"> --}}

    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div
            class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">

                                <a href="javascript:void(0)"
                                    class="text-nowrap logo-img text-center d-block mb-4 w-100">
                                    <img src="{{ asset('dist/images/logos/dark-logo.svg') }}" width="180"
                                        alt="">
                                </a>
                                <div class="position-relative text-center my-4">
                                    <p
                                        class="mb-0 fs-4 px-3 d-inline-block bg-white text-dark z-index-5 position-relative">
                                        Login Here</p>
                                    <span
                                        class="border-top w-100 position-absolute top-50 start-50 translate-middle"></span>
                                </div>

                                {{-- SUCCESS MESSAGE --}}
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success">{{ $message }}</div>
                                @endif

                                {{-- ERROR MESSAGE --}}
                                @if ($message = Session::get('error'))
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @endif

                                <form action="{{ route('auth.login') }}" method="POST">
                                    @csrf

                                    {{-- USERNAME --}}
                                    <div class="mb-3">
                                        <label class="form-label">Username</label>
                                        <input type="text"
                                            class="form-control @error('user_name') is-invalid @enderror"
                                            name="user_name" value="{{ old('user_name') }}"
                                            placeholder="Enter Username" required>

                                        @error('user_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- PASSWORD --}}
                                    <div class="mb-4 position-relative">
                                        <label class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password" id="password"
                                            placeholder="Enter Password" required>

                                        <span
                                            class="position-absolute top-50 end-0 translate-middle-y pe-3 showPassword"
                                            style="cursor:pointer">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </div>

                                    {{-- REMEMBER ME --}}
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input primary" type="checkbox" name="remember"
                                                id="remember">
                                            <label class="form-check-label text-dark" for="remember">
                                                Remember Me
                                            </label>
                                        </div>

                                        <a class="text-primary fw-medium" href="{{ route('forgetPassword') }}">
                                            Forgot Password?
                                        </a>
                                    </div>

                                    {{-- SUBMIT --}}
                                    <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2">
                                        Login
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    {{-- <div class="login-box">
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Login Here</p>
                <form action="{{ route('auth.login') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="User Name" name="user_name"
                            required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Password" name="password"
                            id="password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <a class="showPassword">
                                    <span class="fas fa-eye"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-8">
                            <div class="checkbox">
                                <label><input type="checkbox" name="remember"> Remember Me</label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                        @if ($errors->has('user_name'))
                            <span class="text-danger">{{ $errors->first('user_name') }}</span>
                        @endif

                    </div>
                </form>
                <div class="form-group col-md-12">
                    <a href="{{ route('forgetPassword') }}">Forgot Password</a>
                </div>
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p> {{ $message }}</p>
                    </div>
                @endif
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger">
                        <p> {{ $message }}</p>
                    </div>
                @endif
                <!-- /.social-auth-links -->
            </div>
            <!-- /.login-card-body -->
        </div>
    </div> --}}
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('plugins/scripts/checkPasswordStrength.js') }}"></script>



    <script src="{{ asset('dist/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('dist/libs/simplebar/dist/simplebar.min.js') }}"></script>
    <script src="{{ asset('dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <!--  core files -->
    <script src="{{ asset('dist/js/app.min.js') }}"></script>
    <script src="{{ asset('dist/js/app.init.js') }}"></script>
    <script src="{{ asset('dist/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>

    <script src="{{ asset('dist/js/custom.js') }}"></script>


    <script>
        document.querySelector('.showPassword').addEventListener('click', function() {
            let password = document.getElementById('password');
            password.type = password.type === 'password' ? 'text' : 'password';
        });
    </script>

</body>

</html>
