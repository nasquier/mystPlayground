<?php 
if (!empty($_POST)){
	//HANDLE REMOVE ACCOUNT !!
	$username = htmlspecialchars($_SESSION["user_connected"]);
	$password = htmlspecialchars($_POST['password']);

	try{
		$bdd = new PDO('mysql:host=localhost;dbname=mystWebsite;charset=utf8','root', '');
	}
	catch (Exception $e){
		die('Erreur : ' . $e->getMessage());
	}
	$users_list = $bdd->prepare('SELECT * FROM users WHERE username=?');
	$users_list->execute(array($username));

	$users_list = $users_list->fetch();
	if ($users_list['password']==$password){
		// FILL A CHANGES ARRAY
		if (isset($_POST['email'])){
			// Put changes in array
			
		}
		if (isset($_POST['password'])){
			// Put changes in array
			
		}
		if ($_POST['rmpfp']['cheked']){
			// Remove pfp form server and path from database

		} else if (isset($_FILES['profilepic']) AND $_FILES['profilepic']['error'] == 0 AND $_FILES['profilepic']['size'] < 1000000){
			// REMOVE OLD PIC IF ANY
			$picture_path = 'profile-pictures/'.$username.'_'.basename($_FILES['profilepic']['name']);
			move_uploaded_file($_FILES['profilepic']['tmp_name'], $picture_path);
			//Add path to database (Put changes in array)
		}


		// UPDATE DATABASE
		$update_user = $bdd->prepare('UPDATE * FROM users WHERE username=?');
		$update_user->execute($changes);

		// UPDATE SESSION
		$users_list = $bdd->prepare('SELECT * FROM users WHERE username=?');
		$users_list->execute(array($username));
		$_SESSION["user_picture"] = $users_list['picture_path'];
		if ($_SESSION["user_picture"]==""){
			$_SESSION["user_picture"]="profile-pictures/default.jpg";
		}

		$message = "Account updated.";
	}	
	else{
		$message = "Wrong password.";
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
		<form method='post' action='accountmanagement.php'>
			<fieldset>
				<legend> <?php echo($_SESSION["user_connected"]); ?>&#39s account management </legend>

				<label class='formlabel'> Email </label>
				<input type='email' name='email'
				<?php if (isset($email)) echo('value="'.$email.'"'); else echo('value="'.$_SESSION["user_email"].'"'); ?>  
				maxlength=100  title="Please enter a valid email adress."/><br/>

				<label class='formlabel'> Profile picture </label>
				<input type='file' name='profilepic' accept='image/*'
				title="Files over 1 Mo will be ignored."/>
				<input type='checkbox' name='rmpfp' if='rmpfp'
				title="Check this if you want to remove your profile picture"/> Remove profile picture<br/>

				<label class='formlabel'> New password </label>
				<input type='password' name='new_password' 
				pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d])(?=.*[@$!%+*#?&])[a-zA-Z\d@$!%*#?&]{8,100}$"
				title="Rules :&#10-At least eight characters.&#10-At least one lowercase letter&#10-At least one uppercase letter&#10-At least one number&#10-At least one special character."/>
				<br/><br/><br/><br/><br/>

				<label class='formlabel'> Current password <em>*</em></label>
				<input type='password' name='password' required title="Type your current password to update your information."/><br/>

				<p>
					<input type='submit' value='Submit' />
					<em> <?php if (isset($message)) echo($message); ?> </em>
				</p>
				<p>
					<em> DANGER ZONE ! Click below ONLY if you want to PERMANENTLY delete your account. </em><br/>
					<input type='submit' value='Delete my account' />
				</p>

			</fieldset>
		</form>
	</section>
</body>
</html>

