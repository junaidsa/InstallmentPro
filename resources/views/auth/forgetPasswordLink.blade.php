<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Kinder Byte | Recover Password</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
</head>
<style>
    
    #strength-bar {
        width: 300px;
        height: 20px;
        background-color: #fffff; 
        margin-top: 10px;
    }

    #strength {
        width: 0;
        height: 100%;
        transition: width 0.3s;
    }

    .weak {
        background-color: #ff4d4d;
    }

    .good {
        background-color: #ffc107;
    }

    .strong {
        background-color: #28a745;
    }
    </style>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>
  
      <form action="{{ route('submitPassword') }}" name="resetForm" method="POST">
        @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="input-group mb-3">
                <input type="email" id="email_address" class="form-control" name="email" required autofocus placeholder="Enter Email">
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
        <div class="input-group mb-3">
            <input type="password" id="password" class="form-control" name="password" required autofocus oninput="checkPasswordStrength()" placeholder="Enter Password">
            @if ($errors->has('password'))
                <span class="text-danger">{{ $errors->first('password') }}</span>
            @endif
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
        <input type="password" id="password-confirm" class="form-control" name="password_confirmation" required autofocus placeholder="Confirm Password">
        @if ($errors->has('password_confirmation'))
            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
        @endif
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div id="strength-bar">
            <div id="strength"></div>
        </div>
        <p id="strength-text"></p>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Change password</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mt-3 mb-1">
        <a href="{{ route ('login') }}">Login Back</a>
      </p>
      @if ($message = Session::get('error'))
      <div class="alert alert-success">
   <p> {{$message}}</p>
      </div>
    @endif
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<script>
 function checkPasswordStrength() {
            var password = document.getElementById('password').value;
            var strengthBar = document.getElementById('strength');
            var strengthText = document.getElementById('strength-text');
            var submitBtn = document.getElementById('submit');
            // Check password strength criteria
            var hasUpperCase = /[A-Z]/.test(password);
            var hasLowerCase = /[a-z]/.test(password);
            var hasNumber = /[0-9]/.test(password);

            var strength = 0;

            if (password.length == 8) {
                strength++;
            }

            if (hasUpperCase) {
                strength++;
            }

            if (hasLowerCase) {
                strength++;
            }

            if (hasNumber) {
                strength++;
            }
            var strengthPercentage = (strength / 4) * 100;
            strengthBar.style.width = strengthPercentage + '%';

            if (strength === 0) {
                strengthBar.className = '';
                strengthText.textContent = '';
                submitBtn.disabled = true;
            } else if (strength <= 2) {
                strengthBar.className = 'weak';
                strengthText.textContent = 'Weak';
                submitBtn.disabled = true;
            } else if (strength === 3) {
                strengthBar.className = 'good';
                strengthText.textContent = 'Good';
                submitBtn.disabled = false;
            } else {
                strengthBar.className = 'strong';
                strengthText.textContent = 'Strong';
                submitBtn.disabled = false;
            }
        }
</script>
</body>
</html>
