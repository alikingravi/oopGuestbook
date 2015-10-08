<?php
require 'header.php';
require_once 'core/init.php';

if (Input::exists()) {
	if (Token::check(Input::get('token'))) {
		
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array('required' => true),
			'password' => array('required' => true)
		));

		if ($validation->passed()) {
			$user = new User();

			$remember = (Input::get('remember') === 'on') ? true : false;
			$login = $user->login(Input::get('username'), Input::get('password'), $remember);

			if ($login) {
				Redirect::to('profile.php');
			}
			else{
				echo "Login failed";
			}
		}	
		else{
			$valError[] = $validation->errors();
		}
	}
}
?>

<section id="login">
	<div class="login-box">
		<h1>Login </h1><br/><br/>
		<form action="" method="post">
			<div class="form-group">
				<label>Username</label>	
				<input type="text" name="username" class="form-control" autocorrect="off" placeholder="User Name" value="<?php echo escape(Input::get('username')); ?>">
				<?php if(isset($valError[0]['username'])) {echo "<span class='error'>" . $valError[0]['username'] . "</span>";} ?>
			</div>

			<div class="form-group">
				<label>Password</label>	
				<input type="password" name="password" class="form-control" autocorrect="off" placeholder="Enter at least 6 characters">
				<?php if(isset($valError[0]['password'])) {echo "<span class='error'>" . $valError[0]['password'] . "</span>";} ?>
			</div>

			<div class="checkbox">
					<label for="remember">
					  <input type="checkbox" name="remember" id="remember"> Remember me
					</label>
			</div>
			<br>

			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			<input type="submit" class="btn btn-success" value="Login">
		</form>
	</div>
</section>

</body>
</html>