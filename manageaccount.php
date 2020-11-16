<?php 
include('include/manage-db.php');
if (!empty($_POST)){
	//HANDLE REMOVE ACCOUNT !!
	session_start();
	$username = htmlspecialchars($_SESSION["username"]);
	$email = htmlspecialchars($_POST['email']);
	$new_password = htmlspecialchars($_POST['new_password']);
	$password = htmlspecialchars($_POST['password']);

	// CSELECT CURRENT USER
	$bdd = getdb();
	$user = $bdd->prepare('SELECT * FROM users WHERE username=?');
	$user->execute(array($username));
	$user = $user->fetch();

	// CHECK PASSWORD
	if ($user['password']==$password){

		// UPDATE ACCOUNT
		if (isset($_POST['update'])){
			// PREPARE CHANGES
			if (!empty($_POST['email'])){
				$email=htmlspecialchars($_POST['email']);
			} else {
				$email=$user['email'];
			}
			if (!empty($_POST['new_password'])){ 
				$password=htmlspecialchars($_POST['new_password']); 
			} else {
				$password=$user['password'];
			}

			if (isset($_POST['rm_pfp'])){
				// REMOVE PFP
				if (!empty($user['pfp_path'])){
					unlink($user['pfp_path']);
					$pfp_path='';			
				}
			} else if (isset($_FILES['pfp']) AND $_FILES['pfp']['error'] == 0 AND $_FILES['pfp']['size'] < 1000000){
				if (!empty($user['pfp_path'])) {
					unlink($user['pfp_path']); // REMOVE OLD PIC IF ANY
				}
				$pfp_path = 'pfp/'.$username.'.'.pathinfo($_FILES['pfp']['name'],PATHINFO_EXTENSION);
				move_uploaded_file($_FILES['pfp']['tmp_name'], $pfp_path);
			} else {
				$pfp_path=$user['pfp_path'];
			}

			// UPDATE DATABASE
			$update_user = $bdd->prepare('UPDATE users SET email=:email, password=:new_password, pfp_path=:pfp_path WHERE username=:username');
			$update_user->execute(array(
				'username'=>$username,
				'email'=>$email,
				'new_password'=>$password,
				'pfp_path'=>$pfp_path));

			// UPDATE SESSION
			$_SESSION["user_email"] = $email;
			$_SESSION["user_pfp"] = $pfp_path;
			if ($_SESSION["user_pfp"]==""){
				$_SESSION["user_pfp"]="images/default-pfp.jpg";
			}

			header("Location: index.php?message_id=1");
			exit();

		} elseif (isset($_POST['delete'])) {
			$delete_user = $bdd->prepare('DELETE from users WHERE username=:username');
			$delete_user->execute(array('username'=>$username));
			session_destroy();
			header("Location: index.php?message_id=2");
			exit();
		}
	} else {
		$message = "Wrong password";
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
		<form method='post' action='manageaccount.php' enctype='multipart/form-data'>
			<fieldset>
				<legend> <?php echo($_SESSION["username"]); ?>'s account management </legend>

				<label> Username </label>
				<input type='text' name='username' readonly title='Non-editable' value=<?php echo("'".$_SESSION["username"]."'");?> ><br/>

				<label> Email </label>
				<input type='email' name='email'
				value=<?php if (isset($email)) echo("'".$email."'"); else echo("'".$_SESSION["user_email"]."'"); ?> 
				maxlength=100  title="Please enter a valid email adress."/><br/>

				<label> Profile picture </label>
				<input type='file' name='pfp' accept='image/*'
				title="Files over 1 Mo will be ignored."/>
				<input type='checkbox' name='rm_pfp' id='rm_pfp'
				title="Check this if you want to remove your profile picture"/> Remove profile picture<br/>

				<label> New password </label>
				<input type='password' name='new_password' 
				pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*[\d])(?=.*[@$!%+*#?&])[a-zA-Z\d@$!%*#?&]{8,100}$"
				title="Rules :&#10-At least eight characters.&#10-At least one lowercase letter&#10-At least one uppercase letter&#10-At least one number&#10-At least one special character."/>
				<br/><br/><br/><br/><br/>

				<label> Current password <em>*</em></label>
				<input type='password' name='password' required title="Type your current password to update your information."/><br/>

				<p>
					<input type='submit' name='update' value='Update' />
					<em> <?php if (isset($message)) echo($message); ?> </em>
				</p>
				<p>
					<strong> DANGER ZONE ! Click below ONLY if you want to PERMANENTLY delete your account. </strong><br/>
					<input type='submit' name='delete' value='Delete my account' />
				</p>

			</fieldset>
		</form>
	</section>
</body>
</html>

