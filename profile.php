<?php
require 'header.php';
require_once 'core/init.php';

if (!$username = Input::get('user')) {
	Redirect::to('index.php');
}
else{
	$user = new User($username);
	if (!$user->exists()) {
		Redirect::to(404);
	}
	else{
		echo "User Exists";
	}
}

if ($user->hasPermission('admin')) {
	echo "You are allowed";
}

?>

<section>
	<div class="profile-box">

	<p>Hello <a href="#"><?php echo escape($user->data()->username); ?></a>!</p>

		<a href="logout.php">Logout</a><br/>
		<a href="update.php">Edit Name</a><br/>
		<a href="changepassword.php">Change Password</a>
	</div>
</section>