<?php 
include('include/manage-db.php');
if (!empty($_POST)){
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);

	$bdd = getdb();
	$user = $bdd->prepare('SELECT * FROM users WHERE username=?');
	$user->execute(array($username));

	if ($user->rowCount()){
		$user = $user->fetch();

		if (password_verify($password,$user['password'])){
			ini_set('session.cookie_lifetime', 0); 
			session_set_cookie_params(0);
			session_start();

			$_SESSION['user_id'] = $user['id'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['user_email'] = $user['email'];
			$_SESSION['user_pfp'] = $user['pfp_path'];
			if ($_SESSION['user_pfp']==''){
				$_SESSION['user_pfp']='images/default-pfp.jpg';
			}

			if (isset($_POST['setcookies'])){
				setcookie('username', $username, time()+60*60*24,null,null,false,true);
				setcookie('pwd_hash', password_hash($password, PASSWORD_DEFAULT), time()+60*60*24,null,null,false,true); 
			}

			header('Location: index.php');			
			exit();
		}
		else{
			$error_message = 'Wrong password.';
		}
	} else {
		$error_message = 'Username not found.';
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
		<form method='post' action='login.php'>
			<fieldset>
				<legend> Sign in form </legend>
				<label> Username </label>
				<input type='text' name='username' required
				<?php if (isset($username)) echo("value='".$username."'"); ?>
				/><br/>
				<label> Password </label>
				<input type='password' name='password' required/><br/>
				<p>
					<input type='checkbox' name='setcookies'/> Remember me<br/>
					<input type='submit' value='Sign in' />
					<em> <?php if (isset($error_message)) echo($error_message); ?> </em>
				</p>
			</fieldset>
		</form>
		<p> New member ?   <a href='register.php'><input type='button' value='Register here'/></a> </p>
	</section>
</body>
</html>

