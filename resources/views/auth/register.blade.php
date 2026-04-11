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
				<form method="POST" action="{{ route('register') }}" class="login100-form validate-form">
                    @csrf
					<span class="login100-form-title p-b-43">
						Create Account
					</span>
					
					
                    <div class="wrap-input100 validate-input" data-validate = "Valid name is required">
						
                         <x-text-input id="name" class="block input100 mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
						<span class="focus-input100"></span>
						<span class="label-input100">Name</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">

             <x-text-input id="email" class="block input100 mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
           
            <span class="focus-input100"></span>
						<span class="label-input100">Email</span>

            <x-input-error :messages="$errors->get('email')" class="mt-2" />

        </div>
					
					
					<div class="wrap-input100 mt-4 validate-input" data-validate="Password is required">
						<x-text-input id="password" class="block input100 mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
						<span class="focus-input100"></span>
						<span class="label-input100">Password</span>
					</div>

                     <div class="mt-4">
            

      
        </div>

                    <div class="wrap-input100 mt-4 validate-input" data-validate="Password is required">
						<x-text-input id="password_confirmation" class="block input100 mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
						<span class="focus-input100"></span>
						<span class="label-input100">password_confirmation</span>
					</div>

                   

					<div class="flex-sb-m w-full p-t-3 p-b-32">
						<div class="contact100-form-checkbox">
							<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
							<label class="label-checkbox100" for="ckb1">
								Remember me
							</label>
						</div>

						<div>
							<a href="{{url ('login')}}" class="txt1">
								Already have Account
							</a>
						</div>
					</div>
			

					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Register
						</button>
					</div>
					
					<div class="text-center p-t-46 p-b-20">
						<span class="txt2">
							or sign up using
						</span>
					</div>

					<div class="login100-form-social flex-c-m">
						<a href="{{url ('login/facebook')}}" class="login100-form-social-item flex-c-m bg1 m-r-5">
							<i class="fa fa-facebook-f" aria-hidden="true"></i>
						</a>

						<a href="{{url ('login/google')}}" class="login100-form-social-item flex-c-m bg2 m-r-5">
							<i class="fa fa-google" aria-hidden="true"></i>
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