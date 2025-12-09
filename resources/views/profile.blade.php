@include('components.header')
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fonts/fonts.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        @include('components.navbar')
        @include('components.sidebar')
        <!-- Content Wrapper. Contains page content -->

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ __('lang.MY_PROFILE') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">{{ __('lang.MY_PROFILE') }}</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <section class="content-wrapper">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-3">
                        <!-- Profile Image Card -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile text-center position-relative">
                                <!-- Profile Image Wrapper -->
                                <div class="position-relative d-inline-block">
                                    <img src="{{ asset(auth()->user()->profile_image ?? 'profile_pictures/noImg.png') }}"
                                        class="profile-user-img img-fluid img-circle" width="100" height="100"
                                        alt="Profile Picture" id="profilePreview">

                                    <!-- Pen Button -->
                                    <button type="button" class="btn btn-sm btn-light rounded-circle shadow"
                                        id="triggerProfileImage">
                                        <i class="fas fa-pen text-primary"></i>
                                    </button>

                                    <!-- Hidden File Input -->
                                    <input type="file" name="profileImage" class="d-none" id="profileImage"
                                        accept="image/*" />
                                </div>


                                <!-- Name and Designation -->
                                <h3 class="profile-username text-center mt-3">{{ auth()->user()->account?->name }}</h3>
                                <p class="text-muted text-center">{{ auth()->user()->account?->designation }}</p>

                                <!-- Change Password -->
                                <a href="javascript:void(0)" data-bs-toggle="modal"
                                    data-bs-target="#updatePasswordModal" class="btn btn-primary btn-block mt-2">
                                    <b>{{ __('lang.CHANGE_PASSWORD') }}</b>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /.col -->
                    <!-- /.col -->
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link" href="#settings"
                                            data-toggle="tab">{{ __('lang.SETTINGS') }}</a></li>
                                </ul>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="settings">
                                        <form class="form-horizontal" action="{{ route('profile.update') }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group row">
                                                <label for="inputName"
                                                    class="col-sm-2 col-form-label">{{ __('lang.NAME') }}</label>
                                                <div class="col-sm-10">
                                                    <input id="name" name="name" class="form-control" readonly
                                                        placeholder="Enter  Name"
                                                        value="{{ old('name', auth()->user()->account?->name) }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputName"
                                                    class="col-sm-2 col-form-label">{{ __('lang.FATHER_NAME') }}</label>
                                                <div class="col-sm-10">
                                                    <input id="father_name" name="father_name" class="form-control"
                                                        placeholder="Enter  Name"
                                                        value="{{ old('father_name', auth()->user()->account?->father_name) }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputName2"
                                                    class="col-sm-2 col-form-label">{{ __('lang.USER_NAME') }}</label>
                                                <div class="col-sm-10">
                                                    <input name="user_name" id="userName"
                                                        class="form-control @error('user_name') is-invalid @enderror"
                                                        value="{{ old('user_name', auth()->user()->user_name) }}">
                                                    @error('user_name')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                    <span id = "message"></span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputEmail"
                                                    class="col-sm-2 col-form-label">{{ __('lang.EMAIL') }}</label>
                                                <div class="col-sm-10">
                                                    <input type="email" class="form-control" id="inputEmail"
                                                        placeholder="Email"
                                                        value="{{ old('email', auth()->user()->email) }}" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputName2"
                                                    class="col-sm-2 col-form-label">{{ __('lang.CONTACT_NUMBER') }}</label>
                                                <div class="col-sm-10">
                                                    <input id="contact_person" name="contact_person"
                                                        class="form-control"
                                                        placeholder="{{ __('lang.CONTACT_NUMBER') }}"
                                                        value="{{ auth()->user()->account?->contact_person }}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputExperience"
                                                    class="col-sm-2 col-form-label">{{ __('lang.ADDRESS') }}</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" id="address" name="address" placeholder="{{ __('lang.ADDRESS') }}">{{ auth()->user()->account?->address }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group text-center">
                                                <button type="submit" class="btn btn-primary">
                                                    Update {{ __('lang.PROFILE') }}
                                                </button>
                                            </div>
                                    </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                </div>
                <!-- /.col -->
            </div>
        </section>

        <!-- /.content-wrapper -->
        @include('components.footer')

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
    </div>
    <!-- /.control-sidebar -->
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('dist/js/demo.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('plugins/scripts/profile.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>
    <script src="{{ asset('plugins/scripts/utils.js?v=' . config('miscConstant.JS_VERSION')) }}"></script>
    @include('components.notification')


</body>
