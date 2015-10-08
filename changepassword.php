<?php
require 'header.php';
require_once 'core/init.php';

$user = new User();

if (!$user->isLoggedIn()) {
	Redirect::to('index.php');
}

if (Input::exists()) {
	if (Token::check(Input::get('token'))) {
		
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'password_current' => array(
				'required' => true,
				'min' => 6
			),
			'password_new' => array(
				'required' => true,
				'min' => 6,
			),
			'password_new_again' => array(
				'required' => true,
				'min' => 6,
				'matches' => 'password_new'
			)
		));

		if ($validation->passed()) {
			if (Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->password) {
				echo "Your current password is wrong";
			}
			else{
				$salt = Hash::salt(32);
				$user->update(array(
					'password' => Hash::make(Input::get('password_new'), $salt),
					'salt' => $salt
				));

				Session::flash('home', 'Your password has been changed');
				Redirect::to('profile.php');
			}
		}
		else{
			$valError[] = $validation->errors();
		}
	}
}
?>


<section>
	<div class="changepassword-box">
		<h2>Change your password </h2><br/><br/>
		<form action="" method="post">
			<div class="form-group">
				<label>Current Password</label>	
				<input type="password" name="password_current" class="form-control" autocorrect="off" placeholder="Enter your current password">
				<?php if(isset($valError[0]['password_current'])) {echo "<span class='error'>" . $valError[0]['password_current'] . "</span>";} ?>
			</div>

			<div class="form-group">
				<label>New Password</label>	
				<input type="password" name="password_new" class="form-control" autocorrect="off" placeholder="Enter at least 6 characters">
				<?php if(isset($valError[0]['password_new'])) {echo "<span class='error'>" . $valError[0]['password_new'] . "</span>";} ?>
			</div>

			<div class="form-group">
				<label>Confirm New Password</label>	
				<input type="password" name="password_new_again" class="form-control" autocorrect="off" placeholder="Confirm your password">
				<?php if(isset($valError[0]['password_new_again'])) {echo "<span class='error'>" . $valError[0]['password_new_again'] . "</span>";} ?>
			</div>
			<br>

			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			<input type="submit" class="btn btn-success" value="Login"><a href="profile.php"> Cancel</a>
		</form>
	</div>
</section>

</body>
</html>
