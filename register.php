<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8' />
	<link rel='stylesheet' href='styles/main-style.css' />
	<title> myst </title>
</head>
<body>
	<?php include('header.php'); include('main-menu.php'); ?>
	<form method='post' action='register-process.php' enctype='multipart/form-data'>
		<fieldset>
			<legend> Register form </legend>
			<p> Mandatory fields are marked with <em>*</em>.</p>
			<label class='formlabel'> Username <em>*</em></label>
			<input type='text' name='username' minlength=4 maxlength=20 required/><br/>
			<label class='formlabel'> Email <em>*</em></label>
			<input type='email' name='email' required/><br/>
			<label class='formlabel'> Profile picture </label>
			<input type='file' name='profilepic' accept='image/*'/><br/>
			<label class='formlabel'> Password <em>*</em></label>
			<input type='password' name='password' minlength=8 maxlength=100 required/><br/>
			<!-- <label class='formlabel'> Password check <em>*</em></label>
			<input type='password' name='password-bis' minlength=8 maxlength=100 required/><br/> -->
			<p><input type='submit' value='Submit' /></p>
		</fieldset>
	</form>
</body>
</html>

