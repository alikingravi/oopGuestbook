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
			'name' => array(
				'required' => true,
				'min' => 2,
				'max' => 50
			)
		));

		if ($validation->passed()) {
			try{
				$user->update(array(
					'name' => Input::get('name')
				));

				Session::flash('profile', 'Your details have been updated.');
				Redirect::to('profile.php');
			}
			catch(Exception $e){
				die($e->getMessage());
			}
		}
		else{
			$valError[] = $validation->errors();
		}
	}
}
?>
<section>
	<div class="update-box">
		<h2>Update your Name</h2><br/>
		<form action="" method="post">
			<div class="form-group">
				<label>Name</label>	
				<input type="text" name="name" class="form-control" autocorrect="off" placeholder="User Name" value="<?php echo escape($user->data()->name); ?>">
				<?php if(isset($valError[0]['name'])) {echo "<span class='error'>" . $valError[0]['name'] . "</span>";} ?>
			</div>
			<br>
			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
			<input type="submit" class="btn btn-success" value="Update"><a href="profile.php"> Cancel</a>
		</form>
	</div>	
</section>

</body>
</html>