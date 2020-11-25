<?php  
if(!isset($_SESSION)) { 
	ini_set('session.cookie_lifetime', 0); 
	session_set_cookie_params(0);
	session_start();
}
if (empty($_SESSION) && isset($_COOKIE['username']) && isset($_COOKIE['pwd_hash'])){
	$bdd = getdb();

	$user = $bdd->prepare('SELECT * FROM users WHERE username=?');
	$user->execute(array(htmlspecialchars($_COOKIE['username'])));
	if ($user->rowCount()){
		$user = $user->fetch();
		if (password_verify(htmlspecialchars($_COOKIE['pwd_hash']),$user['password'])){
			$_SESSION["user_id"] = $user['id'];
			$_SESSION["username"] = $user['username'];
			$_SESSION["user_email"] = $user['email'];
			$_SESSION["user_pfp"] = $user['pfp_path'];
			if ($_SESSION["user_pfp"]==""){
				$_SESSION["user_pfp"]="images/default-pfp.jpg";
			}
		}
	} 
}
?>