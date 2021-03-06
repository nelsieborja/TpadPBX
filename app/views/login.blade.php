@extends('layout.master')

@section('extra_assets')
<script src="js/jquery.validate.js"></script>
@stop

@section('content')
<div class="login_wrap floating_box">

	<div class="container">

	<div class="pad">

		<header>
		
			<figure class="icon_logo">
				<img src="img/login/logo_blue.jpg" alt="Tpad">
			</figure>

			<h2 class="what"><small class="block thin">Sign in to your</small> Contact Centre <span class="thin">Login</span></h2>

			<div class="clearfix"></div>
			
		</header>

		<section>

			<form action="" id="frmLogin" method="post">
			
				<h2 class="title caption">User Login</h2>

				<div class="row wrap_inp">
					<label for="username" class="caption">Username:</label>
					<span class="text"><input type="text" id="username" name="username" class="inp" value="{{ $username }}" /></span>
				</div>

				<div class="row wrap_inp">
					<label for="password" class="caption">Password:</label>
					<span class="text"><input type="password" id="password" name="password" class="inp" value="{{ $password }}" /></span>
					<a href="" class="forgot_pwd">Forgot password?</a>
				</div>

				<div class="row">
					<span class="checkbox">
						<input type="checkbox" id="remember" name="remember" />
						<label for="remember">Remember my ID and password</label>
					</span>
				</div>
				
				@if (isset($error))
					<i class="error" style="display:block;margin:6px 0 -19px;font-weight:600">Incorrect username or password</i>
				@endif

				<div class="row">
					<span class="button_wrap">
						<input type="submit" value="SIGN IN" />
						<input type="reset" value="CANCEL" class="gray" />
					</span>
				</div>

			</form>

		</section>
		
	</div>
	
	<p>To provide you with the best possible experience, Tpad uses cookies. Using Tpad means you agree to our use of cookies. For information on cookies and how you can disable them see our cookie policy.</p>
	
	<span class="ie_shadow_top"></span>
	<span class="ie_shadow_bottom"></span>
	<span class="ie_shadow_left"></span>
	<span class="ie_shadow_right"></span>
	</div>

</div>
@stop