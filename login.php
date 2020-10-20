<?php 
if (!empty($_POST)){
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);

	$file = 'users.csv';
	if (!file_exists($file)){
		$users_file = fopen($file, 'a+');
		fputcsv($users_file,array('username','email','password','picture'));
		fclose($users_file);
	}
	$users_file = fopen($file, 'a+');
	$fields = fgetcsv($users_file);

	$username_index = array_search('username', $fields);
	$password_index = array_search('password', $fields);
	$picture_index = array_search('picture', $fields);

	$username_found = False;
	$password_ok = False;
	while (!$password_ok && !feof($users_file)){
		$fields = fgetcsv($users_file);
		if ($fields[$username_index] == $username){
			$username_found = True;
			if ($fields[$password_index] == $password){
				$password_ok = True;
			}
		}
	}
	fclose($users_file);

	if ($username_found){
		if ($password_ok){
			session_start();
			$_SESSION["user_connected"] = $fields[$username_index];
			$_SESSION["user_picture"] = $fields[$picture_index];
			if ($_SESSION["user_picture"]==""){
				$_SESSION["user_picture"]="profile-pictures/default.jpg";
			}
			header("Location: index.php");			
			exit();
		}
		else{
			$error_message = "Wrong password.";
		}
	} else {
		$error_message = "Username not found.";
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8' />
	<link rel='stylesheet' href='styles/main-style.css' />
	<title> myst </title>
</head>
<body>
	<?php 
	include('parts/header.php'); 
	include('parts/main-menu.php'); 
	?>
	<form method='post' action='login.php'>
		<fieldset>
			<legend> Sign in form </legend>
			<label class='formlabel'> Username </label>
			<input type='text' name='username' required
				<?php
					if (isset($username)) echo('value="'.$username.'"');
				?>
			/><br/>
			<label class='formlabel'> Password </label>
			<input type='password' name='password' required/><br/>
			<p><input type='submit' value='Sign in' />
				<em> <?php if (isset($error_message)) echo($error_message); ?> </em>
			</p>
		</fieldset>
	</form>
	<p> New member ?   <a href="register.php"><input type="button" value="Register here"/></a> </p>
</body>
</html>

