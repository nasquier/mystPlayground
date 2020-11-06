<?php 
if (!empty($_POST)){
	$username = htmlspecialchars($_POST['username']);
	$email = htmlspecialchars($_POST['email']);
	$password = htmlspecialchars($_POST['password']);

	try{
		$bdd = new PDO('mysql:host=localhost;dbname=mystWebsite;charset=utf8','root', '');
	}
	catch (Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	ini_set('display_errors','on');
	error_reporting(E_ALL);


	$users_list = $bdd->prepare('SELECT * FROM users WHERE username=?');
	$users_list->execute(array($username));

	if ($users_list->rowCount()){
		$message = "This username is already taken.";
	} else {
		$pfp_path = '';
		if (isset($_FILES['pfp']) AND $_FILES['pfp']['error'] == 0 AND $_FILES['pfp']['size'] < 1000000){
			$pfp_path = 'pfp/'.$username.'.'.pathinfo($_FILES['pfp']['name'],PATHINFO_EXTENSION);
			move_uploaded_file($_FILES['pfp']['tmp_name'], $pfp_path);
		}

		$query = $bdd->prepare('INSERT INTO users (username, email, password, pfp_path) VALUES (:username, :email, :password, :pfp_path);');
		$query->execute(array(
			'username'=>$username,
			'email'=>$email,
			'password'=>$password,
			'pfp_path'=>$pfp_path));

		$message = 'Successfully registered as '.$username.'.';
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
	<section>
		<form method='post' action='register.php' enctype='multipart/form-data'>
			<fieldset>
				<legend> Register form </legend>
				<p> Mandatory fields are marked with <em>*</em>.</p>

				<label class='formlabel'> Username <em>*</em></label>
				<input type='text' name='username' 
				<?php if (isset($username)) echo('value="'.$username.'"'); ?> 
				required pattern="^[a-zA-Z\d_-]{4,20}$" title="Rules :&#10-Between 4 and 20 characters.&#10-Only alphanumerics and dashes"/><br/>

				<label class='formlabel'> Email <em>*</em></label>
				<input type='email' name='email'
				<?php if (isset($email)) echo('value="'.$email.'"'); ?>  
				maxlength=100 required title="Please enter a valid email adress."/><br/>

				<label class='formlabel'> Profile picture </label>
				<input type='file' name='pfp' accept='image/*'
				title="Files over 1 Mo will be ignored."/><br/>

				<label class='formlabel'> Password <em>*</em></label>
				<input type='password' name='password' 
				required pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d])(?=.*[@$!%+*#?&])[a-zA-Z\d@$!%*#?&]{8,100}$"
				title="Rules :&#10-At least eight characters.&#10-At least one lowercase letter&#10-At least one uppercase letter&#10-At least one number&#10-At least one special character."/><br/>

				<p><input type='submit' value='Submit' />
					<em> <?php if (isset($message)) echo($message); ?> </em>
				</p>
			</fieldset>
		</form>
	</section>
</body>
</html>

