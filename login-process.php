<?php 
session_start();

$username = htmlspecialchars($_POST['username']);
$password = htmlspecialchars($_POST['password']);

$file = 'users.csv';
if (!file_exists($file)){
	$users_file = fopen($file, 'a+');
	fputcsv($users_file,array('username','email','password','picture'));
	fclose($users_file);
}
$users_file = fopen($file, 'a+');
$fields = fgetcsv($users_file);

$username_index = array_search('username', $fields);
$password_index = array_search('password', $fields);
$picture_index = array_search('picture', $fields);

$username_found = False;
$password_ok = False;
while (!$password_ok && !feof($users_file)){
	$fields = fgetcsv($users_file);
	if ($fields[$username_index] == $username){
		$username_found = True;
		if ($fields[$password_index] == $password){
			$password_ok = True;
		}
	}
}

if ($username_found){
	if ($password_ok){
		$_SESSION["username"] = $fields[$username_index];
		$_SESSION["picture"] = $fields[$picture_index];
		if ($_SESSION["picture"]==""){
			$_SESSION["picture"]="profile-pictures/default.jpg";
		}
		$message = "<p> Hello ".$_SESSION["username"].", nice to see you !</p>";
	}
	else{
		$message = "<p> Wrong password !</p>";
	}
} else {
	$message = "<p> User name not found !</p>";
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
	echo($message);
	?>
</body>
</html>

