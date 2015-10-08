<?php
require 'header.php';
require_once 'core/init.php';

if (Session::exists('success')) {
	echo Session::flash('success');
}

$user = new User();

if ($user->isLoggedIn()) {
		echo "logged in";
	}	
?>


<section id="home">
	<div class="home-header">
		<img src="images/logo.png">
		<br/><br/><h1>Welcome!</h1>
	</div>

	<div class="menu-box">
		<div class="row">
			<div class="col-md-6 login-hover">
				<a href="login.php"><img src="images/login.png"></a>
			</div>

			<div class="col-md-6 register-hover">
				<a href="register.php"><img src="images/register.png"></a>
			</div>
		</div>
	</div>
</section>