<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="{{ asset('plugins/fonts/fonts.css') }}">
<style>
    @media print {
        button#printButton {
            display: none;
        }
    }
</style>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6" style="margin-left:25%;">

                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">

                        <h3 class="profile-username text-center">{{$account->company}}</h3>

                        <p class="text-muted text-center">{{$account->designation}}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>{{__('lang.ACCOUNT_TYPE')}}</b> <a class="float-right">{{$account->account_type}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{__('lang.NAME')}}</b> <a class="float-right">{{$account->name}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{__('lang.SURNAME')}}</b> <a class="float-right">{{$account->father_name}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{__('lang.CONTACT')}}</b> <a class="float-right">{{$account->contact_person}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{__('lang.EMAIL')}}</b> <a class="float-right">{{$account->email}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{__('lang.CNIC')}}</b> <a class="float-right">{{$account->cnic}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>{{__('lang.ADDRESS')}}</b> <a class="float-right">{{$account->address}}</a>
                            </li>
                        </ul>
                    </div>
                    <button class="btn btn-primary btn-block"  id="printButton" onclick="printPage()"><b>Print</b></button>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('dist/js/demo.js') }}"></script>
<script>
    function printPage() {
        window.print();
    }
</script>
