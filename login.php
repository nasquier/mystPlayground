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
	<form method='post' action='login-process.php'>
		<fieldset>
			<legend> Sign in form </legend>
			<label class='formlabel'> Username </label>
			<input type='text' name='username' required/><br/>
			<label class='formlabel'> Password </label>
			<input type='password' name='password' required/><br/>
			<p><input type='submit' value='Sign in' /></p>
		</fieldset>
	</form>
	<p> New member ?   <a href="register.php"><input type="button" value="Register here"/></a> </p>
</body>
</html>

