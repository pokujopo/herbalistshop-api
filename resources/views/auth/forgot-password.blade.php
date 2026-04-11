<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login V18</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="account/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="account/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="account/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="account/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="account/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="account/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="account/vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="account/vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="account/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="account/css/util.css">
	<link rel="stylesheet" type="text/css" href="account/css/main.css">
</head>
<body style="background-color: #666666;">
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form method="POST" action="{{ route('password.email') }}"
        class="login100-form validate-form">
         @csrf
					<span class="login100-form-title p-b-43">
						Forgot Password
					</span>
					<div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        Forgot your password?<br> No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
    </div>

    <small><x-auth-session-status class="mb-4 success" :status="session('status')" /></small>

					
					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
					
                        <x-text-input id="email" class="block input100 mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
						<span class="focus-input100"></span>
						<span class="label-input100">Email</span>
					</div>
				
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Email password reset link
						</button>
					</div>
					
					<div class="text-center p-t-46 p-b-20">
						<span class="txt2">
							or <a href="{{ url ('register') }}"> click to create account </a>/ using
						</span>
					</div>

					<div class="login100-form-social flex-c-m">
						<a href="#" class="login100-form-social-item flex-c-m bg1 m-r-5">
							<i class="fa fa-facebook-f" aria-hidden="true"></i>
						</a>

						<a href="#" class="login100-form-social-item flex-c-m bg2 m-r-5">
							<i class="fa fa-twitter" aria-hidden="true"></i>
						</a>
					</div>
				</form>

				<div class="login100-more" style="background-image: url('account/images/bg-01.jpg');">
				</div>
			</div>
		</div>
	</div>
	
	

	
	
<!--===============================================================================================-->
	<script src="account/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="account/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="account/vendor/bootstrap/js/popper.js"></script>
	<script src="account/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="account/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="account/vendor/daterangepicker/moment.min.js"></script>
	<script src="account/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="account/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="account/js/main.js"></script>

</body>
</html>