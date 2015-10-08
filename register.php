<?php
require 'header.php';
require_once 'core/init.php';

if (Input::exists()) {
	if (Token::check(Input::get('token'))) {
	
		$validate = new Validate();
		$validation = $validate->check($_POST, array(
			'username' => array(
				'required' => true,
				'min' => 2,
				'max' => 20,
				'unique' => 'users'
			),
			'password' => array(
				'required' => true,
				'min' => 6
			),
			'confirmpassword' => array(
				'required' => true,
				'matches' => 'password'
			),
			'name' => array(
				'required' => true,
				'min' => 2,
				'max' => 50
			),
		));

		if ($validation->passed()) {

			$user = new User();
			
			$salt = Hash::salt(32);

			try{
				$user->create(array(
					'username' 	=> Input::get('username'),
					'password' 	=> Hash::make(Input::get('password'), $salt),
					'salt' 		=> $salt,
					'name' 		=> Input::get('name'),
					'joined' 	=> date('Y-m-d H:i:s'),
					'group' 	=> 1
				));

				Redirect::to('thankyou.php');
			}
			catch(Exception $e){
				die($e->getMessage());
			}

		}else{
			$valError[] = $validation->errors();
		}
	}
}
?>

<section id="register">
	<div class="register-box">
		<h1>Registration</h1><br/>
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

			<div class="form-group">
				<label>Confirm Password</label>	
				<input type="password" name="confirmpassword" class="form-control" autocorrect="off" placeholder="Confirm Password">
				<?php if(isset($valError[0]['confirmpassword'])) {echo "<span class='error'>" . $valError[0]['confirmpassword'] . "</span>";} ?>
			</div>

			<div class="form-group">
				<label>Name</label>	
				<input type="text" name="name" class="form-control" autocorrect="off" placeholder="Your Name" value="<?php echo escape(Input::get('name')); ?>">
				<?php if(isset($valError[0]['name'])) {echo "<span class='error'>" . $valError[0]['name'] . "</span>";} ?>
			</div>

			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			<input type="submit" class="btn btn-success" value="Register">
		</form>
	</div>
</section>

</body>
</html>