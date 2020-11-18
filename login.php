<?php 
include('include/manage-db.php');
if (!empty($_POST)){
	$username = htmlspecialchars($_POST['username']);
	$password = htmlspecialchars($_POST['password']);

	$bdd = getdb();
	$users_list = $bdd->prepare('SELECT * FROM users WHERE username=?');
	$users_list->execute(array($username));

	if ($users_list->rowCount()){
		$users_list = $users_list->fetch();
		if ($users_list['password']==$password){
			session_start();
			$_SESSION["user_id"] = $users_list['id'];
			$_SESSION["username"] = $users_list['username'];
			$_SESSION["user_email"] = $users_list['email'];
			$_SESSION["user_pfp"] = $users_list['pfp_path'];
			if ($_SESSION["user_pfp"]==""){
				$_SESSION["user_pfp"]="images/default-pfp.jpg";
			}
			if (isset($_POST['setcookies'])){
				setcookie('username', $username, time()+60*60*24,null,null,false,true);
				setcookie('pwd_hash', $password, time()+60*60*24,null,null,false,true); 
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
	<section>
		<form method='post' action='login.php'>
			<fieldset>
				<legend> Sign in form </legend>
				<label> Username </label>
				<input type='text' name='username' required
				<?php if (isset($username)) echo('value="'.$username.'"'); ?>
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
		<p> New member ?   <a href="register.php"><input type="button" value="Register here"/></a> </p>
	</section>
</body>
</html>

