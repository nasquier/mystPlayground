<?php 
if (!empty($_POST)){
	$username = htmlspecialchars($_POST['username']);
	$email = htmlspecialchars($_POST['email']);
	$password = htmlspecialchars($_POST['password']);


	$file = 'users.csv';
	if (!file_exists($file)){
		$users_file = fopen($file, 'a+');
		fputcsv($users_file,array('username','email','password','picture'));
		fclose($users_file);
	}
	$users_file = fopen($file, 'a+');
	$fields = fgetcsv($users_file);

	$user_inputs = array_fill(0, count($fields), "");
	$user_inputs[array_search('username', $fields)] = $username;
	$user_inputs[array_search('email', $fields)] = $email;
	$user_inputs[array_search('password', $fields)] = $password;

	if (isset($_FILES['profilepic']) AND $_FILES['profilepic']['error'] == 0 AND $_FILES['profilepic']['size'] < 1000000){
		$picturepath = 'profile-pictures/'.$username.'_'.basename($_FILES['profilepic']['name']);
		move_uploaded_file($_FILES['profilepic']['tmp_name'], $picturepath);
		$user_inputs[array_search('picture', $fields)]=$picturepath;
	}

	fputcsv($users_file,$user_inputs);
	fclose($users_file);

	$message = 'Successfully registered as '.$username.'.';
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
	<form method='post' action='register.php' enctype='multipart/form-data'>
		<fieldset>
			<legend> Register form </legend>
			<p> Mandatory fields are marked with <em>*</em>.</p>
			<label class='formlabel'> Username <em>*</em></label>
			<input type='text' name='username' required pattern="^[a-zA-Z\d_-]{4,20}$" 
			title="Rules :&#10-Between 4 and 20 characters.&#10-Only alphanumerics and dashes"/><br/>
			<label class='formlabel'> Email <em>*</em></label>
			<input type='email' name='email' maxlength=100 required
			title="Please enter a valid email adress."/><br/>
			<label class='formlabel'> Profile picture </label>
			<input type='file' name='profilepic' accept='image/*'
			title="Files over 1 Mo will be ignored."/><br/>
			<label class='formlabel'> Password <em>*</em></label>
			<input type='password' name='password' 
			required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d])(?=.*[@$!%+*#?&])[a-zA-Z\d@$!%*#?&]{8,100}$"
			title="Rules :&#10-At least eight characters.&#10-At least one lowercase letter&#10-At least one uppercase letter&#10-At least one number&#10-At least one special character."/><br/>
			<!-- <input type='password' name='password' 
			title="No required pattern for test purposes."/><br/> -->

			<p><input type='submit' value='Submit' />
				<?php 
				if (isset($message)){
					echo($message);
				}
				?>
			</p>
		</fieldset>
	</form>
</body>
</html>

