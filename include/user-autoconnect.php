<?php  
if(!isset($_SESSION)) { 
	ini_set('session.cookie_lifetime', 0); 
	session_set_cookie_params(0);
	session_start();
}
if (empty($_SESSION) && isset($_COOKIE['username']) && isset($_COOKIE['pwd_hash'])){
	include('manage-db.php');
	$bdd = getdb();

	$username = htmlspecialchars($_COOKIE['username']);
	$password = htmlspecialchars($_COOKIE['pwd_hash']);

	$users_list = $bdd->prepare('SELECT * FROM users WHERE username=?');
	$users_list->execute(array($username));
	if ($users_list->rowCount()){
		$users_list = $users_list->fetch();
		if ($users_list['password']==$password){
			$_SESSION["user_id"] = $users_list['id'];
			$_SESSION["username"] = $users_list['username'];
			$_SESSION["user_email"] = $users_list['email'];
			$_SESSION["user_pfp"] = $users_list['pfp_path'];
			if ($_SESSION["user_pfp"]==""){
				$_SESSION["user_pfp"]="images/default-pfp.jpg";
			}
		}
	} 
}
?>